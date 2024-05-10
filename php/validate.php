<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// include "create-recipient-list.php";

function validateValid($recipientId, $recipientList) {
    $results = [];
    $sentMessages = [];

    foreach ($recipientList as $senderId => $recipients) {
        foreach ($recipients as $recipient) {
            if ($recipient["recipientID"] === $recipientId) {
                $sentPath = "../ecus/$senderId/sent-$senderId.txt";
                if (file_exists($sentPath)) {
                    $ecuSent = file($sentPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $sentMessages = array_merge($sentMessages, $ecuSent);
                } else {
                    $results[$senderId] = ["error" => "Sent file missing"];
                }
            }
        }
    }

    $receivedPath = "../ecus/$recipientId/received-$recipientId.txt";
    if (file_exists($receivedPath)) {
        $receivedMessages = file($receivedPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    } else {
        return ["error" => "Received file missing for " . $recipientId];
    }

    $missingMessages = array_diff($sentMessages, $receivedMessages);

    $results["sent"] = count($sentMessages);
    $results["received"] = count($receivedMessages);
    $results["missing"] = $missingMessages;

    if (!empty($missingMessages)) {
        $results["verdict"] = "failed";
    } else {
        $results["verdict"] = "passed";
    }

    return $results;
}


function validateMalicious($ecuId) {
    $results = [
        'replayAttack' => "false",
        'tamperingAttack' => "false",
        "lostConfidentiality" => "false"
    ];

    $receivedPath = "../ecus/$ecuId/received-$ecuId.txt";

    $messages = file($receivedPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (count($messages) !== count(array_unique($messages))) {
        $results['replayAttack'] = "true";
    }

    foreach ($messages as $message) {
        if (stripos($message, 'attack') == true) {
            $results['tamperingAttack'] = "true";
            break;
        }
    }

    foreach ($messages as $message) {
        if (preg_match('/\becu\b/i', $message)) {
            $results['lostConfidentiality'] = "true";
            break;
        }
    }

    return $results;
}

function validateConfidentiality($ecuId) {
    $results = [
        "lostConfidentiality" => "false"
    ];

    $sentPath = "../ecus/$ecuId/sent-$ecuId.txt";

    $messages = file($sentPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($messages as $message) {
        if (preg_match('/\becu/i', $message)) {
            $results['lostConfidentiality'] = "true";
            break;
        }
    }

    return $results;
}

// $json = file_get_contents("../user-inputs.json");
// $data = json_decode($json, true);
// $configs = createRecipientList($data);
// $result = validateValid("ecu3", $configs);

// echo '<pre>' . print_r($result, true) . '</pre>';