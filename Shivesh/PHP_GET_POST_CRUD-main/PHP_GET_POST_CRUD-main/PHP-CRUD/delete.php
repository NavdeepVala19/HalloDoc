

<?php


        include "config.php";
        // echo "shgfhgv" ;
        if(isset($_GET['id'])){

            echo $id ;

            $user_id = $_GET['id'];

            $sql = "DELETE FROM `users` WHERE `id` = '$user_id'";

            $result = $conn->query($sql);
            echo "result: " . $result;


            if($result==TRUE){
                echo"record deleted successfully";
                    header('Location: view.php');
            
            }
            
            else{
                echo"Error: ". $sql. "<br>". $conn->error;
            }

        }
        

?>


