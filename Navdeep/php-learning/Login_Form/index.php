<!-- Login Form Code -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login Form</title>
</head>

<body>
    <form action="login.php" method="post">
        <h2>LOGIN</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <label for="name">User Name</label>
        <input type="text" id="name" name="uname" placeholder="User Name"><br>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password"><br>

        <button type="submit">Login</button>
    </form>
</body>

</html>