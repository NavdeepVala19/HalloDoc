<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP_MVC_CRUD</title>
</head>

<body>
    <h2>Registration Form</h2>
    <a href="index.php?controller=users&action=read">User's List</a>
    <br>
    <br>

    <form action="index.php?controller=users&action=create " method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Information:</legend>
            First name:<br>
            <input type="text" name="firstname" value="<?php if (isset($_GET['firstname'])) echo $_GET['firstname']; ?>">
            <span><?php if (isset($_GET['firstNameErr'])) echo $_GET['firstNameErr']; ?></span>
            </span>

            <br>
            Last name:<br>
            <input type="text" name="lastname" value="<?php if (isset($_GET['lastname'])) echo $_GET['lastname']; ?>">
            <span ><?php if (isset($_GET['lastNameErr'])) echo $_GET['lastNameErr']; ?></span>
            </span>

            <br>

            Email:<br>
            <input type="email" name="email" value="<?php if (isset($_GET['email'])) echo $_GET['email']; ?>" >
            <span >
            <?php if (isset($_GET['emailErr'])) echo $_GET['emailErr']; ?>
            </span>

            <br>
            Password:<br>
            <input type="password" name="password" >
            <span >
            <?php if (isset($_GET['passwordErr'])) echo $_GET['passwordErr']; ?>
            </span>

            <br>
            Gender:<br>
            <input type="radio" name="gender" value="Male">Male
            <input type="radio" name="gender" value="Female" >Female

            <span >
            <?php if (isset($_GET['genderErr'])) echo $_GET['genderErr']; ?>
            <br>
            <br>
                Select image to upload:
                <input type="file" name="image" id="fileToUpload"  value="<?php if (isset($_GET['image'])) echo $_GET['image']; ?>">
                <span>
                     <?php if (isset($_GET['imageErr'])) echo $_GET['imageErr']; ?>
                </span>
            <br>
            <br>

            <input type="submit" name="submit" value="submit">
        </fieldset>
    </form>

</body>

</html>


