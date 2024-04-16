<?php
session_start();

include "db_conn.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);

    if (empty($uname)) {
        header("Location: index.php?error=User Name is required");
        exit();
    } else if (empty($pass)) {
        header("Location: index.php?error=Password is required");
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE user_name='$uname' AND password='$pass'";
        $result = $conn->query($sql);
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if ($row['user_name'] === $uname && $row['password'] === $pass) {
                echo "Logged in!";
                // echo "<pre>";
                // print_r($row);
                // exit;
                $_SESSION['user_name'] = $row['user_name'];
                // $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];

                header('Location: home.php');
            } else {
                header("Location: index.php?error=Incorect User name or password");
                exit();
            }
        } else {
            header("Location: index.php?error=Incorect User name or password");
            exit();
        }
        $conn->close($conn);
    }
} else {
    header("Location: home.php");
    // echo("login Successfull");
    exit();
}
