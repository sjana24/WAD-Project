<?php

class dbConnect {
    private $conn;
    // db/config.php
    private $host = "localhost";
    private $db = "security_system";
    private $user = "root";
    private $pass = "";

    private function connect() {
       
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }

    public function getConnect(){
        return $this->connect();
    }
}


