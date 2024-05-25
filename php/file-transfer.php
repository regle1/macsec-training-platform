<?php

function uploadFileToFTP($ftp_server, $filename) {

    $ftp_user_name = "ftp_client";
    $ftp_user_pass = "ftpconnect";
    $source_file = $filename;
    $directory = "/home/ftp_client/acpt/";

    $conn_id = ftp_connect($ftp_server);
    if (!$conn_id) {
        return "Could not connect to FTP server | error";
    }

    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

    if ($login_result) {

        ftp_pasv($conn_id, true);

        if (ftp_put($conn_id, $directory . basename($source_file), $source_file, FTP_BINARY)) {

            ftp_close($conn_id);
            return "File transfered successfully";

        } else {
            ftp_close($conn_id);
            return "Failed to upload file to FTP server | error";
        }

        ftp_close($conn_id);
    } else {
        return "Login failed for FTP server | error";
    }
}

?>
