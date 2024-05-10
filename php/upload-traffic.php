<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $dir = "../network-logs/";
    $file = $dir . basename(($_FILES["file"]["name"]));
    
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
        echo "Network traffic recieved \n";
    } else {
        echo "Filed to recive network traffic | error";
    }
}

