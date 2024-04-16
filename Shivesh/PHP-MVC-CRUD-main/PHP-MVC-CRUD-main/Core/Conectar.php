<?php

class Conectar
{

    private $driver;
    private $host;
    private $user;
    private $pass;
    private $database ;
    private $port ;
    private $charset;

    private $upload;



    // The require_once keyword is used to embed PHP code from another file. 
    // If the file is not found, a fatal error is thrown and the program stops. If the file was already included previously, this statement will not include it again.


    public function __construct()
    {
        require_once ("Config/database.php");

        $this->driver = DB_DRIVER;
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->database = DB_DATABASE;
        $this->port = DB_PORT;
        $this->charset=DB_CHARSET;
        

    }

    public function Connection()
    {

        //$sql = ' mysql:host=localhost;dbname=mvc1;charset=utf8
        $SQL = $this->driver . ":host=" . $this->host . ';port='. $this->port.";dbname=" . $this->database;

        // $SQL =  mysql:host=localhost;dbname=my_db;charset=utf8;port=3308;




        try {

            $connection = new PDO($SQL, $this->user, $this->pass);

            // set the PDO error mode to exception
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           
          
            return $connection;
            
            echo "Connected successfully";
           

        } catch (PDOException $e) {

            echo "Connection failed: " . $e->getMessage();

            //We throw the exception
            throw new Exception('Problem establishing the connection.');
        }
      


    }

}

?>