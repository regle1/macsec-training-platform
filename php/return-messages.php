<?php

$ecuId = $_GET['ecuId'] ?? '';
$type = $_GET['type'] ?? '';

$filePath = "../ecus/$ecuId/$type-$ecuId.txt";
$messages = [];

if (file_exists($filePath)) {
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $messages[] = $line;
    }
}

header('Content-Type: application/json');
echo json_encode($messages);
?>