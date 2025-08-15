<?php
//login_action.php;
session_start();
require_once 'db/config.php';

$message = '';
$messageType = '';

//Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedCode = $_POST['access_code'] ?? '';

    if (!empty($submittedCode) && trim($submittedCode) !== '') {
        try {
            // Database connection
            require_once 'db/config.php'; 

            if (!$pdo) {
                $message = "Database connection failed. Please try again later.";
                $messageType = 'error';
            } else {
                // Validate access code
                $stmt = $pdo->prepare("SELECT * FROM user_data 
                                       WHERE code = ? AND status = 'active'");
                $stmt->execute([$submittedCode]);
                $result = $stmt->fetch();

                if ($result) {
                    // ✅ Code matches and is active
                    $_SESSION['access_granted'] = true;
                    $_SESSION['authenticated_user'] = $result['username'];
                    $_SESSION['auth_time'] = time();
                    
                    $message = "Access Granted! Welcome, " . htmlspecialchars($result['username']) . ".";
                    $messageType = 'success';

                    // Log success
                    $logStmt = $pdo->prepare("INSERT INTO access_logs (code_used, status, timestamp) VALUES (?, 'Success', NOW())");
                    $logStmt->execute([$submittedCode]);

                    // Redirect to dashboard after successful access
                    $_SESSION['login_message'] = $message;
                    $_SESSION['login_message_type'] = $messageType;
                    header("Location: index.php");
                    exit;
                } else {
                    // ❌ Code does not match or is not active
                    $message = "Access Denied: Invalid or inactive access code.";
                    $messageType = 'error';

                    // Log failure
                    $logStmt = $pdo->prepare("INSERT INTO access_logs (code_used, status, timestamp) VALUES (?, 'Fail', NOW())");
                    $logStmt->execute([$submittedCode]);
                }
            }
        } catch (Exception $e) {
            $message = "An unexpected error occurred. Please try again later.";
            $messageType = 'error';
            
            // Optional: Log the actual error for debugging
            error_log("Login error: " . $e->getMessage());
        }
    } else {
        $message = "Please enter an access code.";
        $messageType = 'error';
    }

    // Store message in session and redirect back to index.php
    $_SESSION['login_message'] = $message;
    $_SESSION['login_message_type'] = $messageType;
    header("Location: index.php");
    exit;
} else {
    // If accessed directly without POST, redirect to index.php
    header("Location: index.php");
    exit;
}
?>
