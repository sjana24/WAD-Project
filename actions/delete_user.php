
<?php
require_once "../db/config.php";
// $code = $_POST['code'];
// $conn->query("INSERT INTO user_codes (code) VALUES ('$code')");
// header("Location: ../users.php");

class Delete_User {
    private $conn;
    public function __construct() {
        // Constructor code here
        $myObj = new dbConnect();
        // $this->conn = $myObj->connect();
        $this->conn = $myObj->connect();
        
    }

    public function deleteUser($id) {
        try {
           $sql = "UPDATE user_data SET delete_status = 1,status ='inactive', updated_at = NOW() WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $success=$stmt->execute([':id' => $id]);

             if ($success) {
                $affectedRows = $stmt->rowCount();  // <-- Get number of affected rows
                if ($affectedRows > 0) {
                    return json_encode([
                        "success" => true,
                        // "username" => $this->username,
                        // "code" => (int)$this->generatedCode,
                        "message" => "User deleted successfully."
                    ]);
                }
            } else {
                return json_encode([
                    "success" => false,
                    "error" => "db insert issues",
                    "message" => "Unable to add new user."
                ]);
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
    


}
?>
