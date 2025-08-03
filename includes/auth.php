<?php
// session_start();
// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: ../admin_login.php");
//     exit();
// }

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Not logged in, redirect to login page
    header("Location: ./admin_login.php");
    exit();
}

// Optionally, you can check for user role or status here
if ($_SESSION['status'] !== 'active') {
    echo "Access Denied. Your account is not active.";
    exit();
}

// You can also refresh session expiry timeout here (optional)


?>
