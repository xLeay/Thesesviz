<?php

class DB {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4', PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8mb4");

    protected function cnx()
    {
        try {
            $conn = new PDO("mysql:host=$this->servername;dbname=theseviz", $this->username, $this->password, $this->options);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}