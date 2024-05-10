<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $ecuId = $_POST["ecuId"];
    $dir = "../ecus/". $ecuId ."/";
    $file = $dir . basename(($_FILES["file"]["name"]));
    
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
        echo "File uploaded successfully to $dir!";
    } else {
        echo "File upload failed!";
    }
}