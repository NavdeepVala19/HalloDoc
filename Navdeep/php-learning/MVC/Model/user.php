<?php

class user
{
    private $table = "user";
    private $connection;

    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $password;
    private $gender;
    private $image;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getImage()
    {
        return $this->image;
    }
    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getFirstName()
    {
        return $this->first_name;
    }
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }
    public function getLastName()
    {
        return $this->last_name;
    }
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function getGender()
    {
        return $this->gender;
    }
    public function setGender($gender)
    {
        $this->gender = $gender;
    }


    public function create()
    {
        // $query = mysqli_query("INSERT INTO " . $this->table . " (firstname, lastname, email, password, gender) VALUES (". $this->first_name . "," . $this->last_name . "," .$this->email . "," .$this->password . "," . $this->gender . ")";

        $query =  $this->connection->prepare("INSERT INTO " . $this->table . " (firstname, lastname, email, password, gender, image) VALUES (:first_name, :last_name, :email, :password, :gender, :image)");

        $result = $query->execute(
            array(
                "first_name" => $this->first_name,
                "last_name" => $this->last_name,
                "email" => $this->email,
                "password" => $this->password,
                "gender" => $this->gender,
                "image" => $this->image
            )
        );

        $this->connection =  null;

        return $result;
    }

    public function read()
    {
        $query = $this->connection->prepare("SELECT * FROM " . $this->table . " ORDER BY id");
        $query->execute();
        $readView = $query->fetchAll(PDO::FETCH_ASSOC);
        return $readView;
    }

    public function getById($id)
    {
        $query = $this->connection->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
        $query->execute(
            array("id" => $id)
        );
        $result = (array) $query->fetchObject();
        return $result;
    }
    // **********************************************************************
    public function update()
    {
        $query = $this->connection->prepare("UPDATE " . $this->table . " SET 
        firstname = :first_name,
        lastname = :last_name,
        email = :email,
        password = :password,
        gender = :gender,
        image = :image
        WHERE id = :id 
        ");

        $resultUpdate = $query->execute(
            array(
                "first_name" => $this->first_name,
                "last_name" => $this->last_name,
                "email" => $this->email,
                "password" => $this->password,
                "gender" => $this->gender,
                "image" => $this->image,
                "id" => $this->id
            )
        );
        $this->connection =  null;
        return $resultUpdate;
    }
    // **********************************************************************

    public function delete($id)
    {
        try {
            $query = $this->connection->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
            $query->execute(array("id" => $id));
        } catch (Exception $e) {
            echo "FAILED DELETE: " . $e->getMessage();
            return -1;
        }
    }
}
