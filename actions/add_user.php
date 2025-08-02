<?php
require_once '../db/config.php';


class Add_User
{
    private $conn;
    private $username;
    private $nic;
    private $mobileNumber;
    private $generatedCode;
    private $existTime;
    public $existTimeLimit = 5;

    // Constructor code here
    public function __construct()
    {
        $myObj = new dbConnect();
        $this->conn = $myObj->connect();
    }


    public function isExistingUser($code)
    {
        try {
            $sql = "SELECT COUNT(*) FROM user_data WHERE code = :code";
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
                "erroe"=>"Code already exists, please try again $this->existTimeLimit.",
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
                        "message" => "New user added successfully.<br>"
                    ]);
                } 
            } else {
                return json_encode([
                        "success" => false,
                        // "username" => $this->username,
                        // "code" => (int)$this->generatedCode,
                        "message" => "Unable to add new user.<br>"
                    ]);
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
}
