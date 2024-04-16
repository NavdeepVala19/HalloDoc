<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
</head>
<body>
    <h1>Blood Donation Camp</h1>
    <div>
        <h2>Registration Form</h2>
    </div>
    <form action="connect.php" method="POST">
        <label for="user">Name: </label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="phone">Phone: </label>
        <input type="text" name="phone" id="phone" required>
        <br>
        <label for="bgroup">Blood Group: </label>
        <input type="text" name="bgroup" id="bgroup" required>
        <br>
        <input type="submit" name="submit" id="submit">
    </form>
    <?php ?>
</body>
</html>