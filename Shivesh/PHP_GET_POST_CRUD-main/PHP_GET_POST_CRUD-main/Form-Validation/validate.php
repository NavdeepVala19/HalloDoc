<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>


    <?php

    $nameErr = $emailErr = $genderErr = $websiteErr = "";
    $name = $email = $gender = $website = "";

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $nameErr = "Please enter a name";
        } else {
            $fullname = test_input($_POST["name"]);
            if (!preg_match("/[^a-zA-Z-]/", $name)) {
                $nameErr = "only letters and white spaces are allowed";
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Please enter an  valid email";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "the email is incorrect";
            }

        }

        if (empty($_POST["$website"])) {
            $website = '';
        } else {
            $website = test_input($_POST['website']);
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
                $websiteErr = "Enter a valid Webiste URL";
            }

        }

        if (empty($_POST["comment"])) {
            $comment = '';
        } else {
            $comment = test_input($_POST['comment']);
        }

        if (empty($_POST["gender"])) {
            $genderErr = "Please select a gender";
        } else {
            $gender = test_input($_POST['gender']);
        }

    }

    ?>


    <h2>PHP form Validation</h2>

    <p><span class="Error">*required field</span></p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

        FullName: <input type="text" name="name">

        <span class="error">*
            <?php echo $nameErr; ?>
        </span>

        <br><br>

        E-mail address: <input type="text" name="email">

        <span class="error">*
            <?php echo $emailErr; ?>
        </span>

        <br><br>

        Website: <input type="text" name="website">

        <span class="error">
            <?php echo $websiteErr ?>
        </span>

        <br><br>

        Comment: <textarea name="comment" rows="2" cols="10"></textarea>

        <br><br>

        Gender:

        <input type="radio" name="gender" value="female">Female

        <input type="radio" name="gender" value="male">Male

        <span class="error">*
            <?php echo $genderErr; ?>
        </span>

        <br><br>

        <input type="submit" name="submit" value="Submit">

    </form>

    <?php

    echo "<h2> Final Output:</h2>";

    echo $name;

    echo "<br>";

    echo $email;

    echo "<br>";

    echo $website;

    echo "<br>";

    echo $comment;

    echo "<br>";

    echo $gender;

    ?>


</body>

</html>