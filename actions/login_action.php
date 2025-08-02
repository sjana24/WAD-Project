<?php
// login_action.php
session_start();
require_once '../db/config.php';

class LoginAction
{

    private $message = '';
    private $messageType = '';
    private $conn;
    private $submittedCode = '';
    private $submittedUsername = '';
    private $submittedPassword = '';

    public function __construct()
    {
        // Initialize message and type
        $this->conn = (new dbConnect())->connect();
        if (!$this->conn) {
            $message = "Database connection failed. Please try again later.";
            $messageType = 'error';
        }
    }

    // Check if form was submitted
    public function handlePasscode($submittedCode)
    {

        $this->submittedCode = $submittedCode;

        try {
            // Validate access code
            $stmt = $this->conn->prepare("SELECT * FROM user_data 
                                       WHERE code = ? AND status = 'active'");
            $stmt->execute([$this->submittedCode]);
            $result = $stmt->fetch();

            if ($result) {
                // ✅ Code matches and is active
                $_SESSION['access_granted'] = true;
                $_SESSION['authenticated_user'] = $result['username'];
                $_SESSION['auth_time'] = time();

                $message = "Access Granted! Welcome, " . htmlspecialchars($result['username']) . ".";
                $messageType = 'success';

                // Log success
                $logStmt = $this->conn->prepare("INSERT INTO access_logs (code_used, status, timestamp) VALUES (?, 'Success', NOW())");
                $logStmt->execute([$this->submittedCode]);

                // Redirect to dashboard after successful access
                $_SESSION['login_message'] = $message;
                $_SESSION['login_message_type'] = $messageType;
                header("Location: ../index.php");
                exit;
            } else {
                // ❌ Code does not match or is not active
                $message = "Access Denied: Invalid or inactive access code.";
                $messageType = 'error';

                // Log failure
                $logStmt = $this->conn->prepare("INSERT INTO access_logs (code_used, status, timestamp) VALUES (?, 'Fail', NOW())");
                $logStmt->execute([$submittedCode]);
            }
        } catch (Exception $e) {
            $message = "An unexpected error occurred. Please try again later.";
            $messageType = 'error';

            // Optional: Log the actual error for debugging
            error_log("Login error: " . $e->getMessage());
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
                // ❌ Username not found
                $message = "Access Denied: Invalid username.";
                $messageType = 'error';
                echo "Invalid username.";
                return;
            } else {
                if ($admin && password_verify($this->submittedPassword, $admin['password_hash'])) {
                    // ✅ Code matches and is active
                    $_SESSION['access_granted'] = true;
                    $_SESSION['authenticated_user'] = $admin['username'];
                    $_SESSION['auth_time'] = time();

                    $message = "Access Granted! Welcome, " . htmlspecialchars($admin['username']) . ".";
                    $messageType = 'success';


                    // Redirect to dashboard after successful access
                    $_SESSION['login_message'] = $message;
                    $_SESSION['login_message_type'] = $messageType;
                    header("Location: ../logs.php");
                    exit;
                } else {
                    // ❌ Code does not match or is not active
                    $message = "Access Denied: Invalid or inactive access code.";
                    $messageType = 'error';
                    echo "Invalid username or password.";
                    echo "$admin[username]";
                    echo "$admin[password_hash]";
                    echo " $this->submittedPassword";

                    // Log failure
                    // $logStmt = $this->conn->prepare("INSERT INTO access_logs (code_used, status, timestamp) VALUES (?, 'Fail', NOW())");
                    // $logStmt->execute([$submittedCode]);
                }
            }
        } catch (Exception $e) {
            $message = "An unexpected error occurred. Please try again later.";
            $messageType = 'error';

            // Optional: Log the actual error for debugging
            error_log("Login error: " . $e->getMessage());
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedCode = $_POST['access_code'] ?? '';
    $submittedUsername = $_POST['username'] ?? '';
    $submittedPassword = $_POST['password'] ?? '';


    if (!empty($submittedCode) && trim($submittedCode) !== '') {
        // $myObj = new LoginAction();
        // $myObj->handlePasscode($submittedCode);
    }

    if (!empty($submittedUsername) && trim($submittedUsername) !== '' && !empty($submittedPassword) && trim($submittedPassword) !== '') {
        // echo "$submittedUsername, $submittedPassword";
        // $myObj = new LoginAction();
        // $myObj->admin_login($submittedUsername, $submittedPassword);
        // $myObj->handlePasscode($submittedCode);
    }
}


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
