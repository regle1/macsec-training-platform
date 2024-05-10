<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

include "file-transfer.php";
include "execute-config.php";
include "remote-command.php";
include "create-recipient-list.php";
include "validate.php";

class ReceiveChannel {
    public $id;
    public $txMac;
    public $txKeyName;
    public $txKey;
    public $txPort;

    public function __construct($id, $txMac, $txKeyName, $txKey, $txPort) {
        $this->id = $id;
        $this->txMac = $txMac;
        $this->txKeyName = $txKeyName;
        $this->txKey = $txKey;
        $this->txPort = $txPort;
    }
}

class CommunicationChannel {
    public $id;
    public $interface;
    public $status;
    public $encryptionMode;
    public $cipher;
    public $frameValidation;
    public $replayProtection;
    public $replayWindow;
    public $keyName;
    public $key;
    public $rxChannels;
    public $ip;
    public $port;

    public function __construct($id, $interface, $status, $encryptionMode, $cipher, $frameValidation, $replayProtection, $replayWindow, $keyName, $key, $ip, $port) {
        $this->id = $id;
        $this->interface = $interface;
        $this->status = $status;
        $this->encryptionMode = $encryptionMode;
        $this->cipher = $cipher;
        $this->frameValidation = $frameValidation;
        $this->replayProtection = $replayProtection;
        $this->replayWindow = $replayWindow;
        $this->keyName = $keyName;
        $this->key = $key;
        $this->ip = $ip;
        $this->port = $port;
        $this->rxChannels = array();
    }

    public function addReceiveChannel($id, $txMac, $txKeyName, $txKey, $txPort) {
        $channel = new ReceiveChannel($id, $txMac, $txKeyName, $txKey, $txPort);
        $this->rxChannels[] = $channel;
    }

    public function generateConfig($ecuId, $macAddress) {

        $config = "";

        $config .= "# Communication Channel " . $this->port . ":\n\n";
        $config .= "# Create the MACsec device on top of the physical one\n";
        $config .= "execute_silently sudo ip link add link ens3 $this->interface type macsec port $this->port cipher $this->cipher encrypt $this->encryptionMode replay $this->replayProtection window $this->replayWindow validate $this->frameValidation\n\n";
        $config .= "# Configure the Transmit SA and keys\n";
        $config .= "execute_silently sudo ip macsec add $this->interface tx sa 0 pn 1 on key $this->keyName $this->key\n\n";
    
        foreach ($this->rxChannels as $recvChannel) {
            $config .= "# Configure the Receive Channel:\n";
            $config .= "execute_silently sudo ip macsec add $this->interface rx address $recvChannel->txMac port $recvChannel->txPort\n";
            $config .= "execute_silently sudo ip macsec add $this->interface rx address $recvChannel->txMac port $recvChannel->txPort sa 0 pn 1 on key $recvChannel->txKeyName $recvChannel->txKey\n\n";
        }

        $config .= "# Set the IP and bring the interface UP \n";
        $config .= "execute_silently sudo ifconfig $this->interface $this->ip\n";
        $config .= "execute_silently sudo ip link set dev $this->interface up\n\n";

        return $config;
    }
}

class ECU {
    public $id;
    public $macAddress;
    public $commChannels;

    public function __construct($id, $macAddress) {
        $this->id = $id;
        $this->macAddress = $macAddress;
        $this->commChannels = array();
    }

    public function addCommunicationChannel($id, $status, $encryptionMode, $cipher, $frameValidation, $replayProtection, $replayWindow, $keyName, $key, $ip, $port) {
        $interface = "macsec" . $port;
        $channel = new CommunicationChannel($id, $interface, $status, $encryptionMode, $cipher, $frameValidation, $replayProtection, $replayWindow, $keyName, $key, $ip, $port);
        $this->commChannels[] = $channel;
        return $channel; // Return the newly added channel for method chaining
    }

    public function generateConfigs() {
        
        $configs = "#! /bin/bash\n"; // Initialize an empty string to store configurations
        $configs .= "execute_silently() {\n";
        $configs .= "\"$@\" > /dev/null 2>&1\n";
        $configs .= "if [ $? -ne 0 ]; then\n";
        $configs .= "echo \"Failed to setup MACsec | error\"\n";
        $configs .= "exit 1\n";
        $configs .= "fi\n";
        $configs .= "}\n";
        
        foreach ($this->commChannels as $index => $channel) {
            $configs .= $channel->generateConfig($this->id, $this->macAddress);
        }

        $configs .= "echo \"MACsec setup completed successfully | success\"";

        return $configs; // Return the generated configurations
    }
}

$ecu1ip = "10.0.0.191";
$ecu2ip = "10.0.0.192";
$ecu3ip = "10.0.0.193";
$ecu4ip = "10.0.0.194";
$mim1ip = "10.0.0.201";
$mim2ip = "10.0.0.202";

function clientCommunication($ecuId) {
    global $ecu1ip, $ecu2ip, $ecu3ip, $ecu4ip;

    $ecuIps = [
        'ecu1' => $ecu1ip,
        'ecu2' => $ecu2ip,
        'ecu3' => $ecu3ip,
        'ecu4' => $ecu4ip
    ];

    executeCommand($ecuIps[$ecuId], "python3 /home/ftp_client/acpt/client-$ecuId.py &");
}

function serverCommunication($ecuId) {
    global $ecu1ip, $ecu2ip, $ecu3ip, $ecu4ip;

    $ecuIps = [
        'ecu1' => $ecu1ip,
        'ecu2' => $ecu2ip,
        'ecu3' => $ecu3ip,
        'ecu4' => $ecu4ip
    ];

    executeCommand($ecuIps[$ecuId], "python3 /home/ftp_client/acpt/server-$ecuId.py &");
}

function removeMacsec($ecuId) {
    global $ecu1ip, $ecu2ip, $ecu3ip, $ecu4ip;

    $ecuIps = [
        'ecu1' => $ecu1ip,
        'ecu2' => $ecu2ip,
        'ecu3' => $ecu3ip,
        'ecu4' => $ecu4ip
    ];

    $filePath = "../ecus/$ecuId/macsec-config-$ecuId.sh";
    $fileContent = file_get_contents($filePath);
    preg_match_all('/macsec\d+/', $fileContent, $matches);
    $interfaces = array_unique($matches[0]);

    foreach ($interfaces as $interface) {
        executeCommand($ecuIps[$ecuId], "sudo ip link del link ens3 $interface");
    }
}

$json = file_get_contents("php://input");
$data = json_decode($json, true);

if ($data) {

    $ecus = array();
    foreach ($data as $ecuData) {
        $ecu = new ECU($ecuData["id"], $ecuData["mac"]);
        foreach ($ecuData["commChannels"] as $commChannelData) {
            $commChannel = $ecu->addCommunicationChannel(
                $commChannelData["id"],
                $commChannelData["settings"]["comm-status"],
                $commChannelData["settings"]["encryption-mode"],
                $commChannelData["settings"]["cipher"],
                $commChannelData["settings"]["frame-validation"],
                $commChannelData["settings"]["replay-poretction"],
                $commChannelData["settings"]["replay-window"],
                $commChannelData["settings"]["key-name"],
                $commChannelData["settings"]["key"],
                $commChannelData["settings"]["channel-ip"],
                $commChannelData["settings"]["channel-port"],
            );
    
            foreach ($commChannelData["rxChannels"] as $rxChannelData) {
                $commChannel->addReceiveChannel(
                    $rxChannelData["id"],
                    $rxChannelData["settings"]["tx-mac"],
                    $rxChannelData["settings"]["tx-key-name"],
                    $rxChannelData["settings"]["tx-key"],
                    $rxChannelData["settings"]["tx-channel-port"]
                );
            }
        }
        $ecus[] = $ecu;
    }

    function sendMessage($message) {
        file_put_contents("../messages.txt", $message . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    $recipientList = createRecipientList($data);

    foreach ($ecus as $ecu) {
        $configs = $ecu->generateConfigs();
        $ecuId = $ecu->id;

        $configFile = "../ecus/$ecuId/macsec-config-$ecuId.sh";
        file_put_contents($configFile, $configs);
        chmod($configFile, 0777);

        $recipientsFile = "../ecus/$ecuId/ecu-recipients-$ecuId.txt";
        $ecuRecipients = createEcuRecipients($ecuId, $recipientList);
        file_put_contents($recipientsFile, $ecuRecipients);

        $ecuIps = [
            'ecu1' => $ecu1ip,
            'ecu2' => $ecu2ip,
            'ecu3' => $ecu3ip,
            'ecu4' => $ecu4ip
        ];

        sendMessage("$ecuId: Config file generated successfully");
        sendMessage("$ecuId: Recipient list: \n" . $ecuRecipients);
        sendMessage("$ecuId: " . uploadFileToFTP($ecuIps[$ecuId], $configFile));
        sendMessage("$ecuId: " . uploadFileToFTP($ecuIps[$ecuId], $recipientsFile));
        sendMessage("$ecuId: " . executeConfig($ecuIps[$ecuId], "macsec-config-$ecuId"));
        
    }

    sendMessage("System: Simulating communication...");

    # Execute SERVER side communication Python scrypt
    foreach ($ecus as $ecu) {
        $ecuId = $ecu->id;
        serverCommunication($ecuId);
    }

    # Execute CLIENT side communication Python scrypt
    foreach ($ecus as $ecu) {
        $ecuId = $ecu->id;
        clientCommunication($ecuId);
    }

    sleep(20);
    sendMessage("System: Comunication simulation finished");

    # Perform validation on just the valid message exchnage
    sendMessage("System: Analyzing recieved results");

    $verdicts = [];
    $validPassed = true;

    foreach ($ecus as $ecu) {
        $ecuId = $ecu->id;

        $validationResults = validateValid($ecuId, $recipientList);
        
        if (isset($validationResults["verdict"])) {
            $verdicts[$ecuId] = $validationResults["verdict"];

            if ($validationResults["verdict"] === "passed") {
                sendMessage("$ecuId: Valid message exchnage passed");
            } 
            
            if ($validationResults["verdict"] === "failed") {
                sendMessage("$ecuId: Valid message exchnage failed | error");
                $validPassed = false;
            }

        } else {
            $verdicts[$ecuId] = "unknown";
            sendMessage("$ecuId: Valid message exchnage unknown | error");
            // $validPassed = false;
        }
    }

    if ($validPassed) {

        sendMessage("System: Valid message exchnage simulation passed | success");
        sendMessage("System: Simulating attacks...");
        
        # Execute MACICIOUS Python scrypt
        executeCommand($mim1ip, "sudo python3 /home/ftp_client/acpt/attack.py &");
        executeCommand($mim2ip, "sudo python3 /home/ftp_client/acpt/attack.py &");

        # Execute SERVER side communication Python scrypt
        foreach ($ecus as $ecu) {
            $ecuId = $ecu->id;
            serverCommunication($ecuId);
        }

        # Execute CLIENT side communication Python scrypt
        foreach ($ecus as $ecu) {
            $ecuId = $ecu->id;
            clientCommunication($ecuId);
        }

        sleep(20);
        sendMessage("System: Attack simulation finished");
        sendMessage("System: Analyzing recieved results");

        # Perform validation on just the malicious message exchnage
        $maliciousPassed = true;

        foreach ($ecus as $ecu) {
            $ecuId = $ecu->id;
        
                $results = validateMalicious($ecuId);

                if ($results["replayAttack"] === "true") {
                    sendMessage("$ecuId: Recieved a replayed message | error");
                    $maliciousPassed = false;
                }
                if ($results["tamperingAttack"] === "true") {
                    sendMessage("$ecuId: Recieved a tampered message | error");
                    $maliciousPassed = false;
                }
                if ($results["lostConfidentiality"] === "true") {
                    sendMessage("$ecuId: A malicious device on the network was able to read the payload | error");
                    $maliciousPassed = false;
                }
        }

        $results = validateConfidentiality("mim1");

        if ($results["lostConfidentiality"] === "true") {
            sendMessage("$ecuId: A malicious device on the network was able to read the payload | error");
            $maliciousPassed = false;
        }

        $results = validateConfidentiality("mim2");

        if ($results["lostConfidentiality"] === "true") {
            sendMessage("$ecuId: A malicious device on the network was able to read the payload | error");
            $maliciousPassed = false;
        }


    } else {
        sendMessage("System: One or more ECUs failed to receive a valid message");
        sendMessage("System: Simulation aborted | error");
    }

    sendMessage("‎ \n");
    sendMessage("System: Generating report...");

    if ($validPassed) {
        sendMessage("Valid message exchnage: passed | success");

        if ($maliciousPassed) {
            sendMessage("Protection against attacks: passed | success");
        } else {
            sendMessage("Protection against attacks: failed | error");
        }

    } else {
        sendMessage("Valid message exchnage: failed | error");
    }

    if ($validPassed and $maliciousPassed) {
        sendMessage("Final verdict: Learning goal achieved, scenario compleated! | success");
    } else {
        sendMessage("Final verdict: Learning goal failed, scenario not compleated! | error");
    }

    # Clean up - Remove MACsec configurations
    foreach ($ecus as $ecu) {
        $ecuId = $ecu->id;
        removeMacsec($ecuId);
    }

    sendMessage("Close the stream| close");


} else {
    sendMessage("System: Network simulation failed | error");
    sendMessage("Close the stream| close");
}