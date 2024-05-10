<?php

header("Cache-Control: no-store");
header("Content-Type: text/event-stream");

$filePath = "../messages.txt";
$lastSize = 0;

$file = file_exists($filePath) ? fopen($filePath, "r") : null;
if ($file) {
    $lastSize = filesize($filePath);
}

while (true) {
    clearstatcache();
    $currentSize = file_exists($filePath) ? filesize($filePath) : 0;

    if ($currentSize > $lastSize && $file) {
        fseek($file, $lastSize);

        while (!feof($file)) {
            $line = fgets($file);
            if ($line === false) break;

            $line = trim($line);

            if (strpos($line, "| error") !== false) {
                echo "event: error\n";
                echo "data: " . str_replace('| error', '', $line) . "\n\n";
            } elseif (strpos($line, "| success") !== false) {
                echo "event: success\n";
                echo "data: " . str_replace('| success', '', $line) . "\n\n";
            } elseif (strpos($line, "| close") !== false) {
                echo "event: done\n";
                echo "data: Close stream \n\n";
            } else {
                echo "data: " . $line . "\n\n";
            }
        }

        $lastSize = ftell($file);

        ob_flush();
        flush();
        
    } elseif (!$file) {
        $file = fopen($filePath, "r");
        if ($file) $lastSize = 0;
    }

    if (connection_aborted()) {
        if ($file) fclose($file);
        break;
    }

    usleep($currentSize > $lastSize ? 100000 : 500000);
}