<?php
class userController
{
    private $conector;
    private $connection;

    public function __construct()
    {
        require_once "core/conector.php";
        require_once "Model/user.php";

        $this->conector = new conector();
        $this->connection = $this->conector->connection();
    }

    public function run($action)
    {
        switch ($action) {
            case "create":
                $this->create();
                break;
            case "read":
                $this->read();
                break;
            case "update":
                $this->update();
                break;
            case "save":
                $this->save();
                break;
            case "delete":
                $this->delete();
                break;
            default:
                $this->index();
                break;
        }
    }

    public function view($visit, $entries)
    {
        require_once("View/" . $visit . ".php");
    }

    public function index()
    {
        $this->view("home", null);
    }

    public function save()
    {
        $user = new user($this->connection);
        $id = $user->getById($_GET['id']);

        if (isset($_POST["update"])) {

            $this->view("update", $id);

            // **************************************VALIDATION CODE*******************************************************
            $firstName = $lastName = $email = $password = $gender = $image = "";
            $firstNameErr = $lastNameErr = $emailErr = $passwordErr = $genderErr = $imageErr =  "";

            function test_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }


            if (empty($_POST["firstname"])) {
                $firstNameErr = "First name is required";
                $validationError = true;
            } else {
                $firstName = test_input($_POST["firstname"]);
            }

            // Validate last name
            if (empty($_POST["lastname"])) {
                $lastNameErr = "Last name is required";
                $validationError = true;
            } else {
                $lastName = test_input($_POST["lastname"]);
            }

            // Validate email
            if (empty($_POST["email"])) {
                $emailErr = "Email is required";
                $validationError = true;
            } else {
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }
                $email = test_input($_POST["email"]);
            }

            // Validate password
            if (empty($_POST["password"])) {
                $passwordErr = "Password is required";
                $validationError = true;
            } else {
                $password = test_input($_POST["password"]);
            }

            // Validate gender 
            if (empty($_POST["gender"])) {
                $genderErr = "Gender is required";
                $validationError = true;
            } else {
                $gender = test_input($_POST["gender"]);
            }

            // Validate image 
            if (empty($_FILES["image"]["name"])) {
                $imageErr = "Image is required";
                $validationError = true;
            } else {
                $image = $_FILES["image"]["name"];
            }

            if ($validationError) {

                // $this->view("update", $id);
                header("location: index.php?controller=user&action=index&firstNameErr=$firstNameErr&lastNameErr=$lastNameErr&emailErr=$emailErr&passwordErr=$passwordErr&genderErr=$genderErr&imageErr=$imageErr&firstname=$firstName&lastname=$lastName&email=$email&password=$password&gender=$gender");
                exit;
            }

            // *********************************************************************************************


            // *****************************Image Upload***********************************
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            // echo $_FILES["image"]["name"];
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                // echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }


            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
            ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // echo "The file " . htmlspecialchars(basename($_FILES["images"]["name"])) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            // ****************************************************************************

            $user->setId($_POST['id']);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setGender($gender);

            $user->setImage($_FILES["image"]["name"]);
            $user->update();
        }

        header("location: index.php?controller=user&action=read");
    }

    public function create()
    {
        if (isset($_POST['submit'])) {

            $user = new user($this->connection);
            // **************************************VALIDATION CODE*******************************************************
            // define variables and set to empty values
            $firstName = $lastName = $email = $password = $gender = $image = "";
            $firstNameErr = $lastNameErr = $emailErr = $passwordErr = $genderErr = $imageErr =  "";

            function test_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }


            if (!empty($_POST["firstname"])) {
                $firstName = test_input($_POST["firstname"]);
            } else {
                $firstNameErr = "First name is required";
                $validationError = true;
            }

            // Validate last name
            if (!empty($_POST["lastname"])) {
                $lastName = test_input($_POST["lastname"]);
            } else {
                $lastNameErr = "Last name is required";
                $validationError = true;
            }


            if (!empty($_POST["email"])) {
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                    $validationError = true;
                }
            } else {
                $emailErr = "Email is required";
                $validationError = true;
            }


            // Validate password
            if (!empty($_POST["password"])) {
                $password = test_input($_POST["password"]);
            } else {
                $passwordErr = "Password is required";
                $validationError = true;
            }

            // Validate gender 
            if (!empty($_POST["gender"])) {
                $gender = test_input($_POST["gender"]);
            } else {
                $genderErr = "Gender is required";
                $validationError = true;
            }

            // Validate image
            if (!empty($_FILES["image"]["name"])) {
                $image = $_FILES["image"]["name"];
            } else {
                $imageErr = "Image is required";
                $validationError = true;
            }
            if ($validationError) {
                header("location: index.php?controller=user&action=index&firstNameErr=$firstNameErr&lastNameErr=$lastNameErr&emailErr=$emailErr&passwordErr=$passwordErr&genderErr=$genderErr&imageErr=$imageErr&firstname=$firstName&lastname=$lastName&email=$email&password=$password&gender=$gender");
                exit;
            }

            // *********************************************************************************************
            // *******************************************IMAGE UPLOAD**************************************************
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if (isset($_POST['submit'])) {
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
            ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            // *********************************************************************************************
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setGender($gender);

            $user->setImage($_FILES["image"]["name"]);
            $user->create();
        }
        header("location: index.php?controller=user&action=read");
    }


    public function read()
    {
        $user = new user($this->connection);
        $user->read();
        $this->view(
            "read",
            $user->read()
        );
    }

    public function update()
    {

        if (isset($_GET['id'])) {
            $user = new user($this->connection);
            $id = $user->getById($_GET['id']);

            $this->view("update", $id);
        }
    }

    public function delete()
    {
        $user = new user($this->connection);
        $id = $user->getById($_GET["id"]);
        $user->delete($id['id']);
        header("location: index.php?controller=user&action=read");
    }
}