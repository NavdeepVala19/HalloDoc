<?php
$server_name = "localhost";
$u_name = "root";
$password = "";
$db_name = "test_db";
$port = 3308;

$conn =  new mysqli($server_name, $u_name, $password, $db_name, $port);

if ($conn->connect_error) {
    die("Connection Failed" . $conn->connect_error);
}
