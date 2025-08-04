<!-- 
require_once '../db/config.php';


class Edit_User
{
    private $conn;
    private $id;
    private $status;

    // Constructor code
    public function __construct()
    {
        $myObj = new dbConnect();
        $this->conn = $myObj->getConnect();
        if (!$this->conn) {

            return json_encode([
                "success" => false,
                "error" => "db not connect.",
                "message" => "Database connection failed. Please try again later."
            ]);
        }
    }


    public function isExistingUser($id)
    {
        try {
            $sql = "SELECT COUNT(*) FROM user_data WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => (int) $id]);
            $count = $stmt->fetchColumn();
            return $count >= 1;
        } catch (PDOException $e) {
            die("Database error (check existing): " . $e->getMessage());
        }
    }

    

    public function user_status_manage($id,$status)
    {
        // $this->username = $username;
        $this->id = $id;
        $this->status = $status;

        if (($this->isExistingUser($this->id))) {

        try {
            
        $sql = "UPDATE user_data 
        SET status = :status, updated_at = NOW() 
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $success = $stmt->execute([
            ':status' =>$this->status,  // or 'inactive', or dynamic value
            ':id' => (int)$this->id
        ]);

            if ($success) {
                $affectedRows = $stmt->rowCount();  // <-- Get number of affected rows
                if ($affectedRows > 0) {
                    return json_encode([
                        "success" => true,
                        // "username" => $this->username,
                        // "code" => (int)$this->generatedCode,
                        "message" => "User status updated successfully."
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
                    
        } else {
            return json_encode([
                "success" => false,
                "erroe" => "no user found.",
                "message" => "No user found,Try again!"
            ]);
                    
        }
            }
        }

      


      

        
     -->
