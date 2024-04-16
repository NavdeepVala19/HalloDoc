<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC CRUD - Update Form</title>
</head>

<body>


    <h2>User Update Form</h2>
    <a href="index.php?controller=user&action=read">READ DATA</a>
    <form action="index.php?controller=user&action=save&id=<?php echo $entries['id'] ?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Personal information:</legend>
            First name:<br>
            <input type="text" name="firstname" value="<?php echo $entries["firstname"]; ?>">
            <span style="color: red;"><?php if (isset($_GET['firstNameErr'])) echo $_GET['firstNameErr']; ?></span>
            <input type="hidden" name="id" value="<?php echo $entries['id']; ?>">
            <br>
            Last name:<br>
            <input type="text" name="lastname" value="<?php echo $entries["lastname"]; ?>" required>
            <span style="color: red;"><?php if (isset($_GET['lastNameErr'])) echo $_GET['lastNameErr']; ?></span>
            <br>
            Email:<br>
            <input type="email" name="email" value="<?php echo $entries["email"]; ?>" required>
            <span style="color: red;"><?php if (isset($_GET['emailErr'])) echo $_GET['emailErr']; ?></span>

            <br>
            Password:<br>
            <input type="password" name="password" value="<?php echo $entries["password"]; ?>" required>
            <span style="color: red;"><?php if (isset($_GET['passwordErr'])) echo $_GET['passwordErr']; ?></span>

            <br>
            Gender:<br>
            <input type="radio" name="gender" value="Male" required <?php if ($entries["gender"] == 'Male') {
                                                                        echo "checked";
                                                                    } ?>>Male

            <input type="radio" name="gender" value="Female" <?php if ($entries["gender"] == 'Female') {
                                                                    echo "checked";
                                                                } ?>>Female

            <span style="color: red;"><?php if (isset($_GET['genderErr'])) echo $_GET['genderErr']; ?></span>

            <br><br>
            <input type="file" name="image" required>
            <span style="color: red;"><?php if (isset($_GET['imageErr'])) echo $_GET['imageErr']; ?></span>
            <br><br>
            <input type="submit" value="update" name="update">
        </fieldset>
    </form>

</body>

</html>