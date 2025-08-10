<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: users.php");
    exit;
}

 if (isset($_SESSION['message'])) {
            $msg = htmlspecialchars($_SESSION['message'], ENT_QUOTES);
            echo "<script>alert('{$msg}');</script>";
            unset($_SESSION['message']);
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

    <title>Home Security System</title>
    <link rel="icon" type="image/png" href="./assests/images/logo.svg" />
    <link rel="stylesheet" href="assests/css/adminlog.css">
</head>

<body>
    

    <?php if ($message): ?>
        <p style="color: <?= $messageType === 'error' ? 'red' : 'green'; ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>


    <form method="POST" action="actions/main_action.php">

        <label>Username:</label><br>
        <input type="text" name="username" required autofocus><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button></br></br>

        <a href="index.php" class="register-btn">Back</a>
        <a  onclick="askPassword()" class="register-btn">Reset</a>

    </form>

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


