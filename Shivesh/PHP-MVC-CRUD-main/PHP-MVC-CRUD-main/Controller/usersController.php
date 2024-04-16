<?php
class usersController
{

    public $Conectar;
    public $Connection;

    public function __construct()
    {

        require_once 'Core/Conectar.php';
        require_once "Model/users.php";

        $this->Conectar = new Conectar();
        $this->Connection = $this->Conectar->Connection();

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


    /*
     Create the view that we pass to it with the indicated data.
     */
    public function view($visit, $entries)
    {

        require_once("View/" . $visit . ".php");


    }

    public function index()
    {
        $this->view("create", null);
    }



    public function update()
    {
        if (isset($_GET["id"])) {

            $users = new users($this->Connection);
            $id = $users->getById($_GET['id']);

            $this->view("update", $id);

            //We update a user
            // $users = new users($this->Connection);
            // $id = $users->getById($_GET["id"]);

            // $this->view('update',$id);
        }
        // header("location: index.php?controller=users&action=read");

    }

    /*
      Create a new users from the POST parameters and reload the create.php.
     */

    public function create()
    {

        if (isset($_POST['submit'])) {

            $users = new users($this->Connection);


            // *******VALIDATION ********

                $firstnameErr = $lastnameErr = $passwordErr = $emailErr = $genderErr = $imageErr = "";

               $firstname=$lastname= $password= $email= $gender= $image= "";


            function test_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
        

                if (empty($_POST["firstname"])) {
                    $firstnameErr = "*Please enter a first name";
                    $validationError = true;
                } else {
                    $firstname= test_input($_POST["firstname"]);
                    // if (!preg_match("/[^a-zA-Z-]/", $firstname)) {
                    //     $firstnameErrr = "only letters and white spaces are allowed";
                    // }
                }
        
                if (empty($_POST["lastname"])) {
                    $lastnameErr = "*Please enter a last name";
                    $validationError = true;
                } else {
                    $lastname = test_input($_POST["lastname"]);
                    // if (!preg_match("/[^a-zA-Z-]/", $lastname)) {
                    //     $lastnameErr = "only letters and white spaces are allowed";
                    // }
                }
        
                if (empty($_POST["email"])) {
                    $emailErr = "*Please enter an  valid email";
                    $validationError = true;
                } else {
                    $email = test_input($_POST["email"]);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr= "the email is incorrect";
                    }
        
                }
        
               if (empty($_POST["password"])) {
                    $passwordErr = "*Password is required";
                    $validationError = true;
                } else {
                    $password  = test_input($_POST["password"]);
                }
        
                if (empty($_POST["gender"])) {
                    $genderErr = "*Please select a gender";
                    $validationError = true;
                } else {
                    $gender = test_input($_POST['gender']);
                }

                // / Validate image (you may need to adjust this based on your actual form)
                if (empty($_FILES["image"]["name"])) {
                    $imageErr= "*Image is required";
                    $validationError = true;
                } else {
                    $image= $_FILES["image"]["name"];
                }

                if ($validationError) {
                    // Display validation errors or handle them as needed
                    // You can redirect to the form page with error messages, or display errors on the same page
                    
                    header("location: index.php?controller=user&action=index&firstNameErr=$firstnameErr&lastNameErr=$lastnameErr&emailErr=$emailErr&passwordErr=$passwordErr&genderErr=$genderErr&imageErr=$imageErr&firstname=$firstname&lastname=$lastname&email=$email&password=$password&gender=$gender&image=$image");
                    // Adjust the redirection URL based on your actual URL structure
                    exit;
                    // Stop further execution]

                  
                }



            // VALIDATION CODE COMPLETE


            // *******image upload ********

            $target_dir = "upload/";

            // $fileName = $_FILES["file"]["name"];
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            var_dump($target_file);
    
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            // Check if image file is a actual image or fake image
            
            $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
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
            if ($_FILES["image"]["name"]> 500000) {
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
                if (move_uploaded_file($_FILES["image"]["tmp_name"],$target_file)) {
                    echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }



            $users->setfirstname($firstname);
            $users->setlastname($lastname);
            $users->setEmail($email);
            $users->setpassword($password);
            $users->setgender($gender);

            $fileName = $_FILES["image"]["name"];
            $users->setimage($fileName);
          

            $users->create();

        }
        header("location: index.php?controller=users&action=read");

    }



    /* Update users from POST parameters and reload the read.php. */

    public function save()
    {
        $users = new users($this->Connection);
        $id = $users->getById($_GET['id']);

        if (isset($_POST["update"])) {

            $this->view("update", $id);


             // *******VALIDATION ********

             $firstnameErr = $lastnameErr = $passwordErr = $emailErr = $genderErr = $imageErr = "";

             $firstname=$lastname= $password= $email= $gender= $image= "";


          function test_input($data)
          {
              $data = trim($data);
              $data = stripslashes($data);
              $data = htmlspecialchars($data);
              return $data;
          }
      

              if (empty($_POST["firstname"])) {
                  $firstnameErr = "*Please enter a first name";
                  $validationError = true;
              } else {
                  $firstname= test_input($_POST["firstname"]);
                  // if (!preg_match("/[^a-zA-Z-]/", $firstname)) {
                  //     $firstnameErrr = "only letters and white spaces are allowed";
                  // }
              }
      
              if (empty($_POST["lastname"])) {
                  $lastnameErr = "*Please enter a last name";
                  $validationError = true;
              } else {
                  $lastname = test_input($_POST["lastname"]);
                  // if (!preg_match("/[^a-zA-Z-]/", $lastname)) {
                  //     $lastnameErr = "only letters and white spaces are allowed";
                  // }
              }
      
              if (empty($_POST["email"])) {
                  $emailErr = "*Please enter an  valid email";
                  $validationError = true;
              } else {
                  $email = test_input($_POST["email"]);
                  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                      $emailErr= "the email is incorrect";
                  }
      
              }
      
             if (empty($_POST["password"])) {
                  $passwordErr = "*Password is required";
                  $validationError = true;
              } else {
                  $password  = test_input($_POST["password"]);
              }
      
              if (empty($_POST["gender"])) {
                  $genderErr = "*Please select a gender";
                  $validationError = true;
              } else {
                  $gender = test_input($_POST['gender']);
              }

              // / Validate image (you may need to adjust this based on your actual form)
              if (empty($_FILES["image"]["name"])) {
                  $imageErr= "*Image is required";
                  $validationError = true;
              } else {
                  $image= $_FILES["image"]["name"];
              }

              if ($validationError) {
                  // Display validation errors or handle them as needed
                  // You can redirect to the form page with error messages, or display errors on the same page
                  
                  header("location: index.php?controller=user&action=save&firstNameErr=$firstnameErr&lastNameErr=$lastnameErr&emailErr=$emailErr&passwordErr=$passwordErr&genderErr=$genderErr&imageErr=$imageErr&firstname=$firstname&lastname=$lastname&email=$email&password=$password&gender=$gender&image=$image");
                  // Adjust the redirection URL based on your actual URL structure
                  exit;
                  // Stop further execution]

                
              }



          // VALIDATION CODE COMPLETE


            // *******  image upload *******

            $target_dir = "upload/";
            echo var_dump($_FILES["image"]);
            echo var_dump($_FILES["image"]['name']);

            // $fileName = $_FILES["file"]["name"];
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            // var_dump($target_file);
    
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            // Check if image file is a actual image or fake image
            
            $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
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
            if ($_FILES["image"]["name"]> 500000) {
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
                
            // *******************************************************************************

            $users->setId($_POST['id']);
            $users->setfirstname($_POST["firstname"]);
            $users->setlastname($_POST["lastname"]);
            $users->setEmail($_POST["email"]);
            $users->setpassword($_POST["password"]);
            $users->setgender($_POST["gender"]);
       
            $users->setimage($_FILES["image"]["name"]);
        
            $users->update();
        }
        header("location: index.php?controller=users&action=read");
    }

 

    /* view users from POST parameters and reload the index.php. */

    public function read()
    {
        $users = new users($this->Connection);
        $readData = $users->read();

        $this->view("read", $readData);
    }


    public function delete()
    {

        $users = new users($this->Connection);

        $id = $users->getById($_GET["id"]);

        $users->deleteById($id['id']);
        header("location: index.php?controller=users&action=read");
    }

}


?>
