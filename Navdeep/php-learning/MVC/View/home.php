<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <style>
        .error {
            color: #FF0000;
        }
    </style>
    <title>MVC CRUD - Home</title>
</head>

<body>
    <h2>Registration Form</h2>

    <a href="index.php?controller=user&action=read">READ DATA</a>
    <form action="index.php?controller=user&action=create" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Information:</legend>
            First name:<br>
            <input type="text" name="firstname" value="<?php echo isset($_GET['firstname']) ? htmlspecialchars($_GET['firstname']) : ''; ?>">
            <span style="color: red;"><?php if (isset($_GET['firstNameErr'])) echo $_GET['firstNameErr']; ?></span>
            <br>
            Last name:<br>
            <input type="text" name="lastname" value="<?php echo isset($_GET['lastname']) ? htmlspecialchars($_GET['lastname']) : ''; ?>">
            <span style="color: red;"><?php if (isset($_GET['lastNameErr'])) echo $_GET['lastNameErr']; ?></span>
            <br>
            Email:<br>
            <input type="email" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
            <span style="color: red;"><?php if (isset($_GET['emailErr'])) echo $_GET['emailErr']; ?></span>
            <br>
            Password:<br>
            <input type="password" name="password">
            <span style="color: red;"><?php if (isset($_GET['passwordErr'])) echo $_GET['passwordErr']; ?></span>
            <br>
            Gender:<br>
            <input type="radio" name="gender" value="Male" <?php if (isset($_GET['gender']) && $_GET['gender'] == 'Male') echo 'checked'; ?>>Male
            <input type="radio" name="gender" value="Female" <?php if (isset($_GET['gender']) && $_GET['gender'] == 'Female') echo 'checked'; ?>>Female

            <span style="color: red;"><?php if (isset($_GET['genderErr'])) echo $_GET['genderErr']; ?></span>
            <br>
            <br>
            <!-- *************************** -->
            <input type="file" name="image">
            <span style="color: red;"><?php if (isset($_GET['imageErr'])) echo $_GET['imageErr']; ?></span>
            <br>
            <br>
            <!-- *************************** -->

            <input type="submit" name="submit" value="submit">
        </fieldset>
    </form>
    <div>
        <h1 class="data">Click the button Below to change the text without reloading the page.</h1>
        <button class="btn">Click Here</button>
    </div>

    <script>
        $(document).ready(function() {
            $(".btn").click(function() {
                $(".data").load("new.html");

                // $.get('new.html', function(data, status) {
                //     $(".class").html(data);
                //     alert(status);
                // });

                // $.ajax();
                // $.post();
            });

        });
    </script>
</body>

</html>