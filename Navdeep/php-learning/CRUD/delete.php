<?php
include "config.php";

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "DELETE FROM `user` WHERE `id`='$user_id'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        header('Location: read.php');
    } else {
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
}
