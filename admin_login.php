<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit;
}

$message = $_SESSION['login_message'] ?? '';
$messageType = $_SESSION['login_message_type'] ?? '';
unset($_SESSION['login_message'], $_SESSION['login_message_type']);
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>

<head>
    
</head>

<body>
    <h2>Admin Login</h2>

    <?php if ($message): ?>
        <p style="color: <?= $messageType === 'error' ? 'red' : 'green'; ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="actions/login_action.php">
        <label>Username:</label><br>
        <input type="text" name="username" required autofocus><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    </body>
    </html>

