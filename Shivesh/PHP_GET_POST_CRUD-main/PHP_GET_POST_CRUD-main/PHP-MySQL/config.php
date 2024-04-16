<?php

    $servername = "localhost";

    $username = "root";

    $password = "";

    $dbname = "tutorial"; 

    $port = 3308;

    $conn = new mysqli($servername, $username, $password, $dbname,$port);

    if($conn->connect_error) {

        die("Connection Failed" . $conn->connect_error);

    }

?>