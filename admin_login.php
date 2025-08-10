<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: users.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Handle messages from main_action.php
$message = '';
$messageType = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageType = $_SESSION['message_type'] ?? 'error'; // Default to error for login failures
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Also check for login_message (backup)
if (empty($message) && isset($_SESSION['login_message'])) {
    $message = $_SESSION['login_message'];
    $messageType = $_SESSION['login_message_type'] ?? 'error';
    unset($_SESSION['login_message'], $_SESSION['login_message_type']);
}
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Security System - Admin Login</title>
    <link rel="stylesheet" href="assests/css/admin_login.css">
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if ($message): ?>
            <div class="message <?= $messageType === 'error' ? 'error' : 'success'; ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="actions/main_action.php">
            <div class="form-row">
                <label>Username:</label>
                <input type="text" name="username" required autofocus>
            </div>

            <div class="form-row">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <div class="button-row">
                <button type="submit">Login</button>
            </div>
        </form>

        <div class="button-row" style="margin-top: 20px; gap: 15px;">
            <a href="index.php" class="register-btn" style="text-decoration: none; color: #667eea;">Back</a>
            <a onclick="askPassword()" class="register-btn" style="text-decoration: none; color: #667eea; cursor: pointer;">Reset</a>
        </div>
    </div>

    <!-- Hidden form to send answer -->
<form id="resetForm" action="actions/reset.php" method="post" style="display:none;">
    <input type="hidden" name="logout" value="1" />
    <input type="hidden" name="answer" id="answer" />
</form>

    <script>
function askPassword() {
    let answer = prompt("Please answer your security question:");

    if (!answer) {
        alert("Cancelled or empty input.");
        return;
    }

    // Fill hidden form input
    document.getElementById("answer").value = answer;

    // Submit form to reset.php
    document.getElementById("resetForm").submit();
}
</script>

    </body>
    </html> 


