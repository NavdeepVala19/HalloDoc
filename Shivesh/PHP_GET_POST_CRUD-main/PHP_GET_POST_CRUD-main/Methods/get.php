
<?php


        if(isset($_GET["name"]) ||  isset($_GET["age"])){
            echo "Hii". $_GET['name']. "<br/>";
            echo "Your age is " . $_GET['age']. "years";
            exit();
        }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
            <form action="<?php $_PHP_SELF ?>" method="get">
                name: <input name="name" type="text"/>
                Age: <input name="age" type="text"/>
                <input type="submit">
        
            </form>

</body>
</html>