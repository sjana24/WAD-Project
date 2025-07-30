
<?php
// require_once "../db/config.php";
// $code = $_POST['code'];
// $conn->query("INSERT INTO user_codes (code) VALUES ('$code')");
// header("Location: ../users.php");

class delete_user {
    private $conn;
    public function __construct($conn) {
        // Constructor code here
        $myObj = new dbConnect();
        // $this->conn = $myObj->connect();
        $this->conn = $conn;
        
    }

    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM user_data WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
    


}
?>
