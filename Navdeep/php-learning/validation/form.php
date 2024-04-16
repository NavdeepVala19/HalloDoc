<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation Form</title>
</head>

<body>
    <?php
    $fullname = $email = $gender = $comment = $number = $age = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullname = test_input($_POST["name"]);
        $email = test_input($_POST["email"]);
        $gender = test_input($_POST["gender"]);
        $comment = test_input($_POST["comment"]);
        $number = test_input($_POST["number"]);
        $age = test_input($_POST["age"]);
    }
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>
    <h2>Form Validation</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        Full Name: <input type="text" name="name">
        <br>
        <br>
        Email: <input type="text" name="email">
        <br>
        <br>
        Number (Optional): <input type="text" name="number">
        <br>
        <br>
        Age: <input type="text" name="age">
        <br>
        <br>
        Comment: <textarea name="comment" rows="10" cols="20"></textarea>
        <br>
        <br>
        Gender:
        <input type="radio" name="gender" value="female"> Female
        <input type="radio" name="gender" value="male"> Male
        <br>
        <br>
        <input type="submit" value="click here" name="click here">
    </form>

    <?php
    echo "<h2>Your Input: </h2>";
    echo $fullname;
    echo "<br>";
    echo $email;
    echo "<br>";
    echo $age;
    echo "<br>";
    echo $number;
    echo "<br>";
    echo $comment;
    echo "<br>";
    echo $gender;

    ?>

</body>

</html>