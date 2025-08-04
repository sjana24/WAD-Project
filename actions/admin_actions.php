<?php
require_once '../db/config.php';
require '../interface/admin.php';


class Admin_Actions implements admin
{
    private $conn;
    private $username;
    private $nic;
    private $mobileNumber;
    private $generatedCode;
    private $existTime;
    public $existTimeLimit = 5;

    // private $conn;
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
                "error" => "db not connect<br>.",
                "message" => "Database connection failed. Please try again later.<br>"
            ]);
        }
    }


    public function isExistingCode($code)
    {
        try {
            $sql = "SELECT COUNT(*) FROM user_data WHERE code = :code AND status = 'active'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':code' => (int) $code]);
            $count = $stmt->fetchColumn();
            return $count > 0;
        } catch (PDOException $e) {
            die("Database error (check existing): " . $e->getMessage());
        }
    }

    public function generate_code($nationalId, $mobile, $count)
    {
        $input = $mobile . $nationalId;
        // Step 1: Create base hash (secure and irreversible)
        $baseHash = hash('sha256', $input);
        $codes = [];

        // Step 2: Generate multiple codes by varying the hash input (salted)
        for ($i = 0; $i < $count; $i++) {
            // Modify hash slightly by appending index
            $newHash = hash('sha256', $baseHash . $i);

            // Convert part of hash to number
            $decimal = crc32($newHash);

            // Get 4-digit code
            $code = $decimal % 10000;

            // Pad with zeros
            $codePadded = str_pad($code, 4, '0', STR_PAD_LEFT);

            // Ensure uniqueness in the array (optional)
            if (!in_array($codePadded, $codes)) {
                $codes[] = $codePadded;
            }
        }

        return $codes;
    }

    public function add_user($username, $mobileNumber, $nic)
    {
        $this->username = $username;
        $this->nic = $nic;
        $this->mobileNumber = $mobileNumber;

        $generatedCodes = $this->generate_code($this->mobileNumber, $this->nic, $this->existTimeLimit);

        if (isset($generatedCodes)) {
            $this->existTime = 0;
            foreach ($generatedCodes as $gCode) {
                if (($this->isExistingUser($gCode))) {
                    $this->existTime = $this->existTime + 1;
                } else {
                    $this->generatedCode = $gCode;
                    break;
                }
            }
        }

        if ($this->existTime >= $this->existTimeLimit) {
            return json_encode([
                "success" => false,
                "erroe" => "Code already exists, please try again $this->existTimeLimit.",
                "message" => "Credincials already used,Try again!"
            ]);
        }


        echo "$this->username";

        print_r($this->generatedCode);

        try {
            $sql = "INSERT INTO user_data (username, code, created_at, updated_at) VALUES (:username, :code, NOW(), NOW())";
            $stmt = $this->conn->prepare($sql);
            $success = $stmt->execute([
                ':username' => $this->username,
                ':code' => (int)$this->generatedCode
            ]);
            if ($success) {
                $affectedRows = $stmt->rowCount();  // <-- Get number of affected rows
                if ($affectedRows > 0) {
                    return json_encode([
                        "success" => true,
                        "username" => $this->username,
                        "code" => (int)$this->generatedCode,
                        "message" => "New user added successfully."
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
