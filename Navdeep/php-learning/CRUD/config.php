<?php 
$server_name = "localhost";
$username = "root";
$password = "";
$db_name = "crud";
$port = 3308;

$conn = new mysqli($server_name, $username, $password, $db_name, $port);

if ($conn->connect_error) {
    die("Connection Failed" . $conn->connect_error);
}
?>