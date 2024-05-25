<?php

function executeConfig($server, $filename) {

    $username = "ftp_client";
    $password = "ftpconnect";
    $port = 22;
    $command = "sh acpt/$filename.sh";
    
    $connection = ssh2_connect($server, $port);

    if (!$connection) {
        return "Failed to connect to $server:$port | error";
    }

    if (!ssh2_auth_password($connection, $username, $password)) {
        return "Authentication failed for $username@$server | error";
    }
    
    $stream = ssh2_exec($connection, $command);
    
    stream_set_blocking($stream, true);

    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
    
    $output = stream_get_contents($stream_out);
    
    // echo output
    fclose($stream);
    fclose($stream_out);

    ssh2_disconnect($connection);

    return $output;

}

?>