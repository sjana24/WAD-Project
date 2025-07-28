<?php
require_once '../dbConnection/dbConnect.php';

class User{
    private $username;
    private $passcode;
    private $adminUsername;
    private $adminPasscode;
    private $conn;


    public function __construct() {
         
       $myObj = new dbConnect();
       $this->conn = $myObj->connect();
       var_dump($this->conn);
        
    }
     
    public function adminLogin($adminUsername, $adminPasscode) {
        $this->adminUsername = $adminUsername;
        $this->adminPasscode = $adminPasscode;

        $stmt = $this->conn->prepare("SELECT * FROM admin WHERE username = :username ");
        $stmt->bindParam(':username', $this->adminUsername);
        $stmt->execute();
        $admin = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            return true; // Admin login successful
        } else {
            return false; // Admin login failed
        }
    }

    public function newUserInsert($username, $passcode) {
        $this->username = $username;
        $this->passcode = $passcode;

        $stmt = $this->conn->prepare("INSERT INTO users (username, passcode) VALUES (:username, :passcode)");
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':passcode', $this->passcode);

        if ($stmt->execute()) {
            return true; // User inserted successfully
        } else {
            return false; // User insertion failed
        }
    }
    public function userStatusUpdate($username, $status) {
        $this->username = $username;

        $stmt = $this->conn->prepare("UPDATE users SET status = :status WHERE username = :username");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':username', $this->username);

        if ($stmt->execute()) {
            return true; // User status updated successfully
        } else {
            return false; // User status update failed
        }
    }


}

$myObj = new User();
