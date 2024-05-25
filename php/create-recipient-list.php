<?php

function createRecipientList($data) {

    function getRecipient($id, $txPort, $data) {
        foreach ($data as $ecu) {
            if ($ecu['id'] === $id) {
                foreach ($ecu['commChannels'] as $commChannel) {
                    if ($commChannel['settings']['channel-port'] === $txPort) {
                        return $commChannel['settings']['channel-ip'];
                    }
                }
            }
        }
        return null;
    }

    $result = [];

    foreach ($data as $ecu) {
        $senderID = $ecu['id'];
        $result[$senderID] = [];

        foreach ($ecu['commChannels'] as $commChannel) {
            foreach ($commChannel['rxChannels'] as $rxChannel) {
                $txPort = $rxChannel['settings']['tx-channel-port'];
                $txMac = $rxChannel['settings']['tx-mac'];

                $recipientID = null;
                foreach ($data as $ecuCheck) {
                    if ($ecuCheck['mac'] === $txMac) {
                        $recipientID = $ecuCheck['id'];
                        break;
                    }
                }

                if ($recipientID !== null) {
                    $recipient_ip = getRecipient($recipientID, $txPort, $data);
                    if ($recipient_ip !== null) {
                        array_push($result[$senderID], ['recipientID' => $recipientID, 'ip' => $recipient_ip]);
                    }
                }
            }
        }
    }
    return $result;
}

function createEcuRecipients($ecuId, $recipientList) {
    $recipients = "";

    foreach ($recipientList[$ecuId] as $recipient) {

        $noMask = explode('/', $recipient['ip']);
        $ip = trim($noMask[0]);
        $recipients .= $ip . "\n";
    }

    return $recipients;
}

?>
