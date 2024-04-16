
<?php


            if(isset($_POST['name']) && isset($_POST['age'])){
                if (preg_match("/[^a-zA-Z-]/", $_POST['name'])) {
                    die("invalid name");
                }


                echo "Hello ". $_POST['name']. "<br/>";
                echo "Your age is ". $_POST['age']. "years";

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