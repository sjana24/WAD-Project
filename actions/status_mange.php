<?php
require_once '../db/config.php';


class Add_User
{
    private $conn;
    private $id;

    // Constructor code
    public function __construct()
    {
        $myObj = new dbConnect();
        $this->conn = $myObj->connect();
        if (!$this->conn) {

            return json_encode([
                "success" => false,
                "error" => "db not connect<br>.",
                "message" => "Database connection failed. Please try again later.<br>"
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

    

    public function user_status_manage($id)
    {
        // $this->username = $username;
        $this->id = $id;
        // $this->mobileNumber = $mobileNumber;

        if (($this->isExistingUser($this->id))) {

        try {
            
        $sql = "UPDATE user_data 
        SET status = :status, updated_at = NOW() 
        WHERE code = :code";

        $stmt = $this->conn->prepare($sql);

        $success = $stmt->execute([
            ':status' => 'active',  // or 'inactive', or dynamic value
            ':code' => (int)$this->generatedCode
        ]);

            if ($success) {
                $affectedRows = $stmt->rowCount();  // <-- Get number of affected rows
                if ($affectedRows > 0) {
                    return json_encode([
                        "success" => true,
                        "username" => $this->username,
                        "code" => (int)$this->generatedCode,
                        "message" => "New user added successfully.<br>"
                    ]);
                }
            } else {
                return json_encode([
                    "success" => false,
                    "error" => "db insert issues",
                    "message" => "Unable to add new user.<br>"
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

      


      

        
    
