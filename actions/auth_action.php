<?php

session_start();
require_once '../db/config.php';
require_once './abstract_user.php';

class Auth_Action extends User
{


    private $conn;
    

    public function __construct()
    {
        // Initialize message and type
        $this->conn = (new dbConnect())->getConnect();
        if (!$this->conn) {

            return json_encode([
                "success" => false,
                "error" => "db not connect<br>.",
                "message" => "Database connection failed. Please try again later.<br>"
            ]);
        }
    }

//  1.admin login function
     public function admin_login($submittedUsername, $submittedPassword)
    {

        $this->submittedUsername = $submittedUsername;
        $this->submittedPassword = $submittedPassword;


        try {
            // Validate access code
            $sql = "SELECT * FROM admins WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $this->submittedUsername, PDO::PARAM_STR);

            $stmt->execute();

            // Fetch associative array or false if not found
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$admin) {
                return json_encode([
                    "success" => false,
                    "error" => "user not found.",
                    "message" => "Invalid credintials."
                ]);
            } else {
                if ($admin && password_verify($this->submittedPassword, $admin['password_hash'])) {
                //  Code  match and active
                    date_default_timezone_set('Asia/Colombo');
                    $_SESSION['user_id'] = $admin['id'];  // Example user ID
                    $_SESSION['auth_times'] = date('Y-m-d H:i:s'); // Store current timestamp
                    $_SESSION['status'] = 'active';   // Example custom session value

                    return json_encode([
                        "success" => true,
                        "message" => "Access Granted! Welcome, " . htmlspecialchars($admin['username']) . "."
                    ]);
                } else {
                    //  Code does not match or is not active

                    return json_encode([
                        "success" => false,
                        "error" => "Invalid username or password.<br>",
                        "message" => "Invalid credintials.<br>"
                    ]);
                }
            }
        } catch (Exception $e) {
            $message = "An unexpected error occurred. Please try again later.";
            $messageType = 'error';
            return json_encode([
                "success" => false,
                "error" => error_log("Login error: " . $e->getMessage()),
                "message" => "Invalid credintials.<br>"
            ]);

            
        }
    }

// 2. admin logout
     public function admin_logout(){
        // session_start();

        if (session_status() === PHP_SESSION_ACTIVE) {
            // Clear session variables
            $_SESSION = [];
            session_unset();
            // Destroy the session
            session_destroy();
            $_SESSION['message'] ='logout successfully';
            // echo "<script>alert('Logout successfully');</script>";
            // location.reload();
            // header("Location: ../admin_login.php");
            // exit;
             return json_encode([
                "success" => true,
                "message" => "logout successfully"
            ]);
        } 
        return json_encode([
                "success" => false,
                "message" => "Logout failed"
            ]);
        
        echo "You have been logged out successfully.";
    
    }

// 3.user passcode check
    public function handlePasscode($submittedCode)
    {

        $this->submittedCode = $submittedCode;

        try {
            // Validate access code
            $sql = "SELECT * FROM user_data WHERE code = ? AND status = 'active'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$this->submittedCode]);
            $result = $stmt->fetch();

            if ($result) {
                 // Log success
                $sql = "INSERT INTO access_logs (code_used, status, timestamp) VALUES (:code_used, :status, NOW())";
                $stmt = $this->conn->prepare($sql);
                $success = $stmt->execute([
                    ':code_used' => $this->submittedCode,
                    ':status' => 'Success'
                ]);


                if ($success) {
                    // echo "Log entry created successfully.";
                    return json_encode([
                        "success" => true,
                        "message" => "Access Granted! Welcome, " . htmlspecialchars($result['username']) . "."
                    ]);
                }
            } else {
                //  Code does not match or is not active
                // Log failure
                $sql = "INSERT INTO access_logs (code_used, status, timestamp) VALUES (:code_used, :status, NOW())";
                $stmt = $this->conn->prepare($sql);
                $success = $stmt->execute([
                    ':code_used' => $this->submittedCode,
                    ':status' => 'Fail'
                ]);


                if ($success) {
                    // Insert was successful
                    return json_encode([
                        "success" => false,
                        "error" => "User not found.<br>",
                        "message" => "Access Denied: Invalid or inactive access code.<br>"
                    ]);
                } else {
                    // Insert failed (unlikely but possible)
                    return json_encode([
                        "success" => false,
                        "error" => "Failed to log attempt.",
                        "message" => "An unexpected error occurred while logging the failed attempt."
                    ]);
                }
            }
        } catch (Exception $e) {
            return json_encode([
                "success" => false,
                "error" => error_log("Login error: " . $e->getMessage()) . ".<br>",
                "message" => "An unexpected error occurred. Please try again later.<br>"
            ]);
        }
    }
   
}

