
<?php


        if(isset($_REQUEST["name"]) ||  isset($_REQUEST["age"])){
            echo "Hii". $_REQUEST['name']. "<br/>";
            echo "Your age is " . $_REQUEST['age']. "years";
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
    
            <form action="<?php $_PHP_SELF ?>" method="POST">
                name: <input name="name" type="text"/>
                Age: <input name="age" type="text"/>
                <input type="submit">
        
            </form>

</body>
</html>