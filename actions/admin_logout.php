<?php
session_start();

if (session_status() === PHP_SESSION_ACTIVE) {
    // Clear session variables
    $_SESSION = [];
session_unset();
    // Destroy the session
    session_destroy();
    
    // location.reload();
    // header("Location: ../admin_login.php");
    // exit;
}

echo "You have been logged out successfully.";
?>
<script>
    window.location.href = "../admin_login.php";
    sessionStorage.clear;
</script>
