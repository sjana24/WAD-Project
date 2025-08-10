<?php
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

    // Collect and sanitize inputs
    $username = isset($_POST['username']) ? sanitize($_POST['username']) : '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $question = isset($_POST['question']) ? sanitize($_POST['question']) : '';
    $answer = isset($_POST['answer']) ? sanitize($_POST['answer']) : '';

    // Validate required fields
    if (!$username || !$password || !$password_confirm || !$question || !$answer) {
        echo "Error: All fields are required.";
        exit;
    }

    // Validate password match
    if ($password !== $password_confirm) {
        echo "Error: Passwords do not match.";
        exit;
    }
    $myObj = new Registration();
    $response = $myObj->insertAdmin($username, $password);
    $data = json_decode($response, true);

    if ($data['success']) {
        echo $data['success'];
        echo $data['message'];

        $response = $myObj->insertQuestion($question, $answer);
        $data = json_decode($response, true);
        if ($data['success']) {
            echo $data['success'];
            echo $data['message'];
            echo "<script>alert({$_data['message']});</script>";
            // $_SESSION['message'] = $data['message'];
            header("Location: ../admin_login.php");
            exit;
        } else {
            echo $data['success'];
            echo  $data['message'];
            // $_SESSION['message'] = $data['message'];
            echo  $data['error'];
            header("Location: ../registration.php");
            exit;
        }
        // echo "<script>alert({$_data['message']});</script>";
        // $_SESSION['message'] = $data['message'];
        // header("Location: ../users.php");
        // exit;
    } else {
        echo $data['success'];
        echo  $data['message'];
        // $_SESSION['message'] = $data['message'];
        echo  $data['error'];
        header("Location: ../registration.php");
        exit;
    }



    // echo "<h2>Registration Data Received:</h2>";
    // echo "<p><strong>Username:</strong> $username</p>";
    // echo "<p><strong>Password:</strong> (hashed securely, not shown)</p>";
    // echo "<p><strong>Security Question:</strong> $question</p>";
    // echo "<p><strong>Answer:</strong> $answer</p>";
    // echo "<p><strong>Hashed Password:</strong> $hashedPassword</p>";
} else {
    echo "No data submitted.";
}
