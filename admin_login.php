<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$message = $_SESSION['login_message'] ?? '';
$messageType = $_SESSION['login_message_type'] ?? '';
unset($_SESSION['login_message'], $_SESSION['login_message_type']);
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>

<head>
<link rel="stylesheet" href="assests/css/adminlog.css">
</head>

<body>
    

    <?php if ($message): ?>
        <p style="color: <?= $messageType === 'error' ? 'red' : 'green'; ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="login_action.php">
        <h2>Admin Login</h2>
        <label>Username:</label><br>
        <input type="text" name="username" required autofocus><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button></br></br>

        <a href="registration.php" class="register-btn">Register</a>

    </form>
    </body>
    </html> 


