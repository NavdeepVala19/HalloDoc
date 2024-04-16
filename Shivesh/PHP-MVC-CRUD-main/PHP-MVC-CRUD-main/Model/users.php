<?php

include "Config/database.php";

class users
{

    private $table = "users";
    private $Connection;


    private $id;
    private $firstname;
    private $lastname;
    private $password;
    private $email;

    private $gender;

    private $image;



    public function __construct($Connection)
    {
        $this->Connection = $Connection;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getfirstname()
    {
        return $this->firstname;
    }

    public function setfirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getlastname()
    {
        return $this->lastname;
    }

    public function setlastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getpassword()
    {
        return $this->password;
    }

    public function setpassword($password)
    {
        $this->password = $password;
    }

    public function getgender()
    {
        return $this->gender;
    }

    public function setgender($gender)
    {
        $this->gender = $gender;
    }

    public function getimage()
    {
        return $this->image;
    }

    public function setimage($image)
    {
        $this->image = $image;
    }


    public function create()
    {
        // A prepared statement is a feature used to execute the same (or similar) SQL statements repeatedly with high efficiency.

        $saveCreated = $this->Connection->prepare("INSERT INTO " . $this->table .
            " (firstname,lastname,email,password,gender,image) VALUES (:firstname,:lastname,:email,:password,:gender,:image)");
        $createResult = $saveCreated->execute(
            array(
                "firstname" => $this->firstname,
                "lastname" => $this->lastname,
                "email" => $this->email,
                "password" => $this->password,
                "gender" => $this->gender,
                "image" => $this->image
            )
        );
        $this->Connection = null;

        return $createResult;
    }


    public function update()
    {
        $saveUpdate = $this->Connection->prepare
        ("UPDATE " . $this->table . " SET firstname = :firstname,lastname = :lastname, email = :email,password = :password,gender = :gender, image=:image WHERE id = :id ");
        $updateResult = $saveUpdate->execute(
            array(
                "id" => $this->id,
                "firstname" => $this->firstname,
                "lastname" => $this->lastname,
                "email" => $this->email,
                "password" => $this->password,
                "gender" => $this->gender,
                "image" => $this->image
            )
        );
        $this->Connection =  null;

        return $updateResult;
    }


    // view data on screen(UI)
    public function read()
    {
        try {
            $query = $this->Connection->prepare("SELECT * FROM " . $this->table);
            $query->execute();
            $readView = $query->fetchAll(PDO::FETCH_ASSOC);
            return $readView;
        } catch (PDOException $e) {
            // Handle the exception
            echo "Error reading users: " . $e->getMessage();
            return null; // or you can return an empty array
        }
    }

    // public function getById($id){

    //         $fetchId = $this->Connection->prepare("SELECT * FROM " . $this->table . "WHERE id =:id");
    //         $fetchId->execute(
    //             array("id" => $id)
    //         );

    //         $result_fetchId = (array) $fetchId->fetchObject();

    //         return $result_fetchId;

    // }

    public function getById($id)
    {
        $query = $this->Connection->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");

        $query->execute(
            array("id" => $id)
        );

        $result = (array) $query->fetchObject();

        return $result;
    }


    public function deleteById($id)
    {
        try {
            $deleteData = $this->Connection->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
            $deleteData->execute(
                array(
                    "id" => $id
                )
            );
            
        } catch (Exception $e) {
            echo 'Failed DELETE (deleteById): ' . $e->getMessage();
            return -1;
        }
    }

}


?>