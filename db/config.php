<?php

class dbConnect {
    private $conn;
    // db/config.php
    private $host = "localhost";
    private $db = "security_system";
    private $user = "root";
    private $pass = "";

    public function connect() {
        require_once 'config.php'; // Include the database configuration file
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }
}
// db/config.php
// $host = "localhost";
// $db = "security_system";
// $user = "root";
// $pass = "";

// $conn = new mysqli($host, $user, $pass, $db);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
