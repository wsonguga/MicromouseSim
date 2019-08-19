<?php
    function getDBConnection() {
        $db_hostname = '127.0.0.1';
        $db_database = 'micromouse';
        $db_username = 'root';
        $db_password = 'luoeniac43';

        // Create connection
        $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        return $conn;
        // Remember to close the connection after calling this function
    }
?>