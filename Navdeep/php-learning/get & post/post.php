<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POST Method </title>
</head>

<body>
    <?php
    if (isset($_POST["name"]) || isset($_POST["age"])) {
        if (preg_match("/[^A-Za-z'-]/", $_POST["name"])) {
            die("Invalid name. Name should be alphabet");
        }
        echo "HII " . $_POST["name"] . "<br>";
        echo "Your age is " . $_POST["age"] . "<br>";
    }
    ?>

    <form action="<?php $_PHP_SELF ?>" method="POST">
        Name: <input type="text" name="name">
        Age: <input type="text" name="age">
        <input type="submit">
    </form>
</body>

</html>