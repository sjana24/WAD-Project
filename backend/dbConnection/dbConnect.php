<?php

class DbConnect{
    protected $connection;
    protected $host = "localhost";
    protected  $dbUsername = "root";
    protected $dbPassword = "";
    protected $dbName = "security_system";

    
     public function connect()
    {
        
    
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->dbUsername, $this->dbPassword);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}