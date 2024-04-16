<?php

class conector
{
    private $driver;
    private $host;
    private $user;
    private $pass;
    private $database;
    private $port;

    public function __construct()
    {
        require_once 'config/database.php';
        $this->driver = DB_DRIVER;
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->database = DB_DATABASE;
        $this->port = DB_PORT;
    }

    public function connection()
    {

        // $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE, DB_PORT);
        $link = $this->driver . ":host=" . $this->host . ";port=" . $this->port . ';dbname=' . $this->database;

        try {
            $connection = new PDO($link, $this->user, $this->pass);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            throw new Exception("Problem establishing the connection.");
        }
    }
}
