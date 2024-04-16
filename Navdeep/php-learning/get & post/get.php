<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GET Method</title>
</head>
<body>
    <?php 
    if(isset($_GET["name"]) ||isset($_GET["age"])){
        echo "HII! ". $_GET["name"] . "<br>";
        echo "Your age is ". $_GET["age"] . " years";
    exit();
    }
    ?>

    <form action="<?php $_PHP_SELF  ?>" method="GET">
        Name: <input type="text" name="name">
        Age: <input type="text" name="age">
        <input type="submit">
    </form>
</body>
</html>