<!-- 
<?php
// session_start();
    // include 'db_conn.php';

    $sname ="localhost";
    $uname = "root";
    $password="";

    $db_name="login_db";


    $conn = mysqli_connect("localhost","root","","login_db" ,"3308");

    if (!$conn) {
        die("Connection failed: ". mysqli_connect_error());
    }

    if(isset($_POST['uname']) && isset($_POST['password']))
    {
        // function validate ($data){
        //     $data = trim($data);
        //     $data = stripslashes($data);
        //     $data = htmlspecialchars($data);
        //     return $data;
        // }
    }

    $uname = validate($_POST['uname']);
    $password = validate($_POST['password']);

     if(empty($uname)){
        header ("location:index.php? error= Username is required");
        exit();
     }
     elseif(empty($password)){
        header ("location:index.php? error= Password is required");
        exit();
     }


     $sql = "SELECT * FROM users WHERE uname = '$uname' AND password = '$password'";
     $result = mysqli_query($conn, $sql);
      echo($result);
     if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row['user_name'] === $uname && $row['password'] === $pass) {
        echo "Logged in!";
        $_SESSION['user_name'] = $row['user_name'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['id'] = $row['id'];
        header("Location: home.php");
        exit();
        }
        else{
            header("Location: index.php?error=Incorect User name or password");exit();
            }
    }
?> 

*/


<?php
session_start();

$sname = "localhost";
$uname = "root";
$password = "";
$db_name = "login_db";
$port = 3308;

$conn = mysqli_connect($sname, $uname, $password, $db_name, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['uname']) && isset($_POST['password'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);

    if (empty($uname)) {
        header("Location: index.php?error=Username is required");
        exit();
    } elseif (empty($pass)) {
        header("Location: index.php?error=Password is required");
        exit();
    }

    // Prepared statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE uname = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $uname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row['password'] === $pass) {  // This should ideally be a password verification step
            echo "Logged in!";
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['id'] = $row['id'];
            header("Location: home.php");
            exit();
        } else {
            header("Location: index.php?error=Incorrect Username or password");
            exit();
        }
    } else {
        header("Location: index.php?error=Incorrect Username or password");
        exit();
    }
}
?>