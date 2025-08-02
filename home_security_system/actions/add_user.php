<?php
// require_once "../db/config.php";
// $code = $_POST['code'];
// $conn->query("INSERT INTO user_codes (code) VALUES ('$code')");
// header("Location: ../users.php");

class add_user {
    private $conn;
    public function __construct($conn) {
        // Constructor code here
        $myObj = new dbConnect();
        // $this->conn = $myObj->connect();
        $this->conn = $conn;
        
    }

    // public function addCode($username,$code) {
    //     $sql = "INSERT INTO user_data (username,code, created_at, updated_at) VALUES (:username,:code, NOW(), NOW())";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute([':username'=>$username,':code' => $code]);
    
    //     // $stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    //     if ($stmt === false) {
    //         die("Prepare failed: " . $this->conn->error);
    //     }
    //     // $stmt->bind_param("ss", $username, $password);
    //     if (!$stmt->execute()) {
    //         die("Execute failed: " . $stmt->error);
    //     }
    //     $stmt->close();
    // }
    public function addUser($username, $code) {
    try {
        $sql = "INSERT INTO user_data (username, code, created_at, updated_at) VALUES (:username, :code, NOW(), NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':code' => $code
        ]);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

}
?>
