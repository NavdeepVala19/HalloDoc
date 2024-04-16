<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=], initial-scale=1.0">
    <title>Validation-Form</title>
</head>
<body>
    
        <?php 
        
                // define variables and set empty

                $fullname = $email = $gender = $age = $comment = $number ="";
           

                if($_SERVER["REQUEST_METHOD"]=="POST"){
                    $fullname = test_input($_POST["name"]);
                    $email = test_input($_POST["email"]);
                    $gender = test_input($_POST["gender"]);
                    $age = test_input($_POST["age"]);
                    $comment = test_input($_POST["comment"]);
                    $number = test_input($_POST["number"]);
                }

                function test_input($data){
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }

        ?>
        
        <h2>Form Validation</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
    
                Full Name:
                <input type="text" name="name">
                <br><br>

                E-mail:
                <input type="text" name="email">
                <br><br>

                Number(optional):
                <input type="text" name="number">
                <br><br>

                Age:
                <input type="text" name="age">
                <br><br>

                Comment:
                <textarea name="comment" cols="30" rows="10"></textarea>
                <br><br>

                Gender:
                <input type="radio" name="gender" value="female">Female
                <input type="radio" name="gender" value="male">Male
                <br><br>
    
                <input type="submit" name="click here" value="click here"> 
        </form>


        <?php
                echo "<h2> Your input </h2>";

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
                echo "<br>";
        ?>

</body>
</html>