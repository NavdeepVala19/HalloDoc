
<?php

        $sname ="localhost";
        $uname = "root";
        $password="";

        $db_name="login_db";


        $conn = mysqli_connect("localhost","root","","login_db" ,"3308");

        if (!$conn) {
            die("Connection failed: ". mysqli_connect_error());
        }

?>