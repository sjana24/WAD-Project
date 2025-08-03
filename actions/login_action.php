<?php

session_start();
require_once '../db/config.php';

class LoginAction
{


    private $conn;
    private $submittedCode = '';
    private $submittedUsername = '';
    private $submittedPassword = '';

    public function __construct()
    {
        // Initialize message and type
        $this->conn = (new dbConnect())->connect();
        if (!$this->conn) {

            return json_encode([
                "success" => false,
                "error" => "db not connect<br>.",
                "message" => "Database connection failed. Please try again later.<br>"
            ]);
        }
    }

    // Check if form was submitted
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
                // Code matches and is active
                // $_SESSION['access_granted'] = true;
                // $_SESSION['authenticated_user'] = $result['username'];
                // $_SESSION['auth_time'] = time();



                // Log success
                $sql = "INSERT INTO access_logs (code_used, status, timestamp) VALUES (:code_used, :status, NOW())";
                $stmt = $this->conn->prepare($sql);
                $success = $stmt->execute([
                    ':code_used' => $this->submittedCode,
                    ':status' => 'Success'
                ]);

                // $affectedRows = $logStmt->rowCount();


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
    // else {
    //     $message = "Please enter an access code.";
    //     $messageType = 'error';
    // }

    // Store message in session and redirect back to lock.php
    //     $_SESSION['login_message'] = $message;
    //     $_SESSION['login_message_type'] = $messageType;
    //     header("Location: ../lock.php");
    //     exit;
    // } 
    // else {
    //     // If accessed directly without POST, redirect to lock.php
    //     header("Location: ../lock.php");
    //     exit;
    // }


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
                    //  Code matches and is active
                    // $_SESSION['access_granted'] = true;
                    // $_SESSION['authenticated_user'] = $admin['username'];
                    // $_SESSION['auth_time'] = time();
                    // $_SESSION['username'] = $submittedUsername;
                    date_default_timezone_set('Asia/Colombo');
                    $_SESSION['user_id'] = $admin['id'];  // Example user ID
                    $_SESSION['auth_times'] = date('Y-m-d H:i:s'); // Store current timestamp
                    $_SESSION['status'] = 'active';   // Example custom session value


                    // $message = "Access Granted! Welcome, " . htmlspecialchars($admin['username']) . ".";
                    // $messageType = 'success';

                    return json_encode([
                        "success" => true,
                        "message" => "Access Granted! Welcome, " . htmlspecialchars($admin['username']) . "."
                    ]);


                    // Redirect to dashboard after successful access
                    // $_SESSION['login_message'] = $message;
                    // $_SESSION['login_message_type'] = $messageType;
                    // header("Location: ../logs.php");
                    // exit;
                } else {
                    //  Code does not match or is not active
                    // $message = "Access Denied: Invalid or inactive access code.";
                    // $messageType = 'error';
                    // echo "Invalid username or password.";
                    // echo "$admin[username]";
                    // echo "$admin[password_hash]";
                    // echo " $this->submittedPassword";

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

            // Optional: Log the actual error for debugging
            // error_log("Login error: " . $e->getMessage());
        }
    }
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $submittedCode = $_POST['access_code'] ?? '';
//     $submittedUsername = $_POST['username'] ?? '';
//     $submittedPassword = $_POST['password'] ?? '';


//     if (!empty($submittedCode) && trim($submittedCode) !== '') {
//         // $myObj = new LoginAction();
//         // $myObj->handlePasscode($submittedCode);
//     }

//     if (!empty($submittedUsername) && trim($submittedUsername) !== '' && !empty($submittedPassword) && trim($submittedPassword) !== '') {
//         // echo "$submittedUsername, $submittedPassword";
//         // $myObj = new LoginAction();
//         // $myObj->admin_login($submittedUsername, $submittedPassword);
//         // $myObj->handlePasscode($submittedCode);
//     }
// }


//             $sql = "SELECT * FROM admins WHERE username = :username";

//             $stmt = $this->conn->prepare($sql);
//             $stmt->bindParam(':username', $username, PDO::PARAM_STR);
//             $stmt->execute();
//             $admin = $stmt->fetch(PDO::FETCH_ASSOC);

//             if ($admin && password_verify($password, $admin['password_hash'])) {
//                 $_SESSION['admin_logged_in'] = true;
//                 $_SESSION['admin_username'] = $username;
//                 header("Location: ../logs.php");
//                 exit();
//             } else {
//                 $x = $admin['username'];
//                 $y = $admin['password_hash'];
//                 $z = $password;
//                 echo "$x,$y,$z";
//                 // echo `$admin['password_hash'] `;
//                 echo "Invalid username or password.";
//             }
//         } catch (PDOException $e) {
//             echo "Login failed: " . $e->getMessage();
//         }
//     }

//     // Middleware-style check for login
//     public static function check()
//     {
//         session_start();
//         if (!isset($_SESSION['admin_logged_in'])) {
//             header("Location: ../admin_login.php");
//             exit();
//         }
//     }
// }
