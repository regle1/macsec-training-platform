<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

function uploadFileToFTP($ftp_server, $filename) {

    // FTP server credentials
    $ftp_user_name = "ftp_client";
    $ftp_user_pass = "ftpconnect";

    // Source file path
    $source_file = $filename;

    // Destination directory on the FTP server
    $directory = "/home/ftp_client/acpt/";

    // Connect to FTP server
    $conn_id = ftp_connect($ftp_server);
    if (!$conn_id) {
        return "Could not connect to FTP server | error";
    }

    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

    if ($login_result) {

        // Change to passive mode
        ftp_pasv($conn_id, true);

        // Upload file
        if (ftp_put($conn_id, $directory . basename($source_file), $source_file, FTP_BINARY)) {

            ftp_close($conn_id);
            return "File transfered successfully";

        } else {
            ftp_close($conn_id);
            return "Failed to upload file to FTP server | error";
        }

        // Close the connection to the FTP server
        ftp_close($conn_id);
    } else {
        return "Login failed for FTP server | error";
    }
}

?>
