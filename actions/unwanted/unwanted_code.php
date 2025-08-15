<!-- // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
// } -->



<!-- 
 // else {
    //     $message = "Please enter an access code.";
    //     $messageType = 'error';
    // }

    // Store message in session and redirect back to index.php
    //     $_SESSION['login_message'] = $message;
    //     $_SESSION['login_message_type'] = $messageType;
    //     header("Location: ../index.php");
    //     exit;
    // } 
    // else {
    //     // If accessed directly without POST, redirect to index.php
    //     header("Location: ../index.php");
    //     exit;
    // } -->

