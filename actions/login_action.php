<?php
session_start();
require_once "../db/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $auth = new Auth();
    $auth->adminLogin($username, $password);
}

class Auth
{
    public $conn;

    public function __construct()
    {
        $db = new dbConnect();
        $this->conn = $db->connect();
    }

    public function adminLogin($username, $password)
    {
        if (empty($username) || empty($password)) {
            echo "Username and password are required.";
            return;
        }

        try {
            $sql = "SELECT * FROM admins WHERE username = :username";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                header("Location: ../logs.php");
                exit();
            } else {
                
                echo "Invalid username or password.";
                header("Location: ../admin_login.php");
                exit();
            }
        } catch (PDOException $e) {
            echo "Login failed: " . $e->getMessage();
        }
    }

    // Middleware-style check for login
    public static function check()
    {
        session_start();
        if (!isset($_SESSION['admin_logged_in'])) {
            header("Location: ../admin_login.php");
            exit();
        }
    }
}
