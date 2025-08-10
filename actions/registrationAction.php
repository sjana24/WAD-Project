<?php
session_start();
require_once "../db/config.php";
class Registration
{
    private $conn;

    public function __construct()
    {
        $myObj = new dbConnect();
        $this->conn = $myObj->getConnect();
    }

    public function insertAdmin($username, $password)
    {
        try {
            // Hash the password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL insert statement
            $sql = "INSERT INTO admins (username, password_hash, created_at, updated_at)
                VALUES (:username, :password_hash, NOW(), NOW())";

            $stmt = $this->conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password_hash', $hashedPassword);

            // Execute the query
            $stmt->execute();

            // Check if insert was successful
            if ($stmt->rowCount() > 0) {
                return json_encode([
                    "success" => true,
                    "message" => "Admin inserted successfully.",
                    "admin_id" => $this->conn->lastInsertId()
                ]);
            } else {
                return json_encode([
                    "success" => false,
                    "message" => "Failed to insert admin."
                ]);
            }
        } catch (PDOException $e) {
            return json_encode([
                "success" => false,
                "message" => "Database error: " . $e->getMessage()
            ]);
        }
    }



    public function insertQuestion($question, $answer)
    {
        try {
            // Prepare SQL insert statement
            $sql = "INSERT INTO question (question, answer) VALUES (:question, :answer)";

            $stmt = $this->conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer', $answer);

            // Execute the query
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return json_encode([
                    "success" => true,
                    "message" => "Question inserted successfully.",
                    "question_id" => $this->conn->lastInsertId()
                ]);
            } else {
                return json_encode([
                    "success" => false,
                    "message" => "Failed to insert question."
                ]);
            }
        } catch (PDOException $e) {
            return json_encode([
                "success" => false,
                "message" => "Database error: " . $e->getMessage()
            ]);
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize helper function
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Validation helper functions
    function validateUsername($username) {
        // Username: 3-30 characters, alphanumeric and underscore only
        if (strlen($username) < 3 || strlen($username) > 30) {
            return "Username must be between 3 and 30 characters.";
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return "Username can only contain letters, numbers, and underscores.";
        }
        return true;
    }

    function validatePassword($password) {
        // Password: minimum 8 characters, at least one letter and one number
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)/', $password)) {
            return "Password must contain at least one letter and one number.";
        }
        return true;
    }

    function validateSecurityQuestion($question) {
        // Validate against allowed security questions
        $allowedQuestions = ['q1', 'q2', 'q3', 'q4', 'q5'];
        if (!in_array($question, $allowedQuestions)) {
            return "Invalid security question selected.";
        }
        return true;
    }

    function validateAnswer($answer) {
        // Answer: 1-100 characters, basic validation
        if (strlen($answer) < 1 || strlen($answer) > 100) {
            return "Answer must be between 1 and 100 characters.";
        }
        // Remove potentially dangerous characters
        if (preg_match('/[<>"\']/', $answer)) {
            return "Answer contains invalid characters.";
        }
        return true;
    }

    // Collect and sanitize inputs
    $username = isset($_POST['username']) ? filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING) : '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $question = isset($_POST['question']) ? filter_var(trim($_POST['question']), FILTER_SANITIZE_STRING) : '';
    $answer = isset($_POST['answer']) ? filter_var(trim($_POST['answer']), FILTER_SANITIZE_STRING) : '';

    // Validate required fields
    if (!$username || !$password || !$password_confirm || !$question || !$answer) {
        $_SESSION['error_message'] = "All fields are required.";
        header("Location: ../registration.php");
        exit;
    }

    // Validate username format
    $usernameValidation = validateUsername($username);
    if ($usernameValidation !== true) {
        $_SESSION['error_message'] = $usernameValidation;
        header("Location: ../registration.php");
        exit;
    }

    // Validate password strength
    $passwordValidation = validatePassword($password);
    if ($passwordValidation !== true) {
        $_SESSION['error_message'] = $passwordValidation;
        header("Location: ../registration.php");
        exit;
    }

    // Validate password match
    if ($password !== $password_confirm) {
        $_SESSION['error_message'] = "Passwords do not match.";
        header("Location: ../registration.php");
        exit;
    }

    // Validate security question
    $questionValidation = validateSecurityQuestion($question);
    if ($questionValidation !== true) {
        $_SESSION['error_message'] = $questionValidation;
        header("Location: ../registration.php");
        exit;
    }

    // Validate answer
    $answerValidation = validateAnswer($answer);
    if ($answerValidation !== true) {
        $_SESSION['error_message'] = $answerValidation;
        header("Location: ../registration.php");
        exit;
    }
    $myObj = new Registration();
    $response = $myObj->insertAdmin($username, $password);
    $data = json_decode($response, true);

    if ($data['success']) {
        $response = $myObj->insertQuestion($question, $answer);
        $questionData = json_decode($response, true);
        
        if ($questionData['success']) {
            $_SESSION['success_message'] = "Registration completed successfully. Please login with your credentials.";
            header("Location: ../admin_login.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Registration failed: " . $questionData['message'];
            header("Location: ../registration.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Registration failed: " . $data['message'];
        header("Location: ../registration.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Invalid request method.";
    header("Location: ../registration.php");
    exit;
}
