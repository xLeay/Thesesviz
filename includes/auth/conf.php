<?php
require '/Laragon/www/Thesesviz/pdo_pass.php';

$servername = "localhost";
$username = "root";
$password = "$mdp";
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8mb4', PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8mb4");

try {
    $conn = new PDO("mysql:host=$servername;dbname=theseviz", $username, $password, $options);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>
