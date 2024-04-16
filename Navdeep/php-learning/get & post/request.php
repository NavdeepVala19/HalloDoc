<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REQUEST Method</title>
</head>
<body>
    <?php 
    if(isset($_REQUEST["name"]) ||isset($_REQUEST["age"])){
        echo "HII! ". $_REQUEST["name"] . "<br>";
        echo "Your age is ". $_REQUEST["age"] . " years";
    exit();
    }
    ?>

    <form action="<?php $_PHP_SELF  ?>" method="POST">
        Name: <input type="text" name="name">
        Age: <input type="text" name="age">
        <input type="submit">
    </form>
</body>
</html>