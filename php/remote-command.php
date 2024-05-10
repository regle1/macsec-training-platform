<?php

function executeCommand($server, $command) {

    $username = "ftp_client";
    $password = "ftpconnect";
    $port = 22;
    
    // Establish a connection to the SSH Server. Port is the second param.
    $connection = ssh2_connect($server, $port);

    if (!$connection) {
        return "Failed to connect to $server:$port | error";
    }

    if (!ssh2_auth_password($connection, $username, $password)) {
        return "Authentication failed for $username@$server | error";
    }

    // Execute a command on the connected server and capture the response
    $stream = ssh2_exec($connection, $command);
    
    // Sets blocking mode on the stream
    stream_set_blocking($stream, False);

    fclose($stream);
    ssh2_disconnect($connection);

    return "Command executed successfully!";

}

?>