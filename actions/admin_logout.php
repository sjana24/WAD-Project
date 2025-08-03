<?php
session_start();

if (session_status() === PHP_SESSION_ACTIVE) {
    // Clear session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();
}

echo "You have been logged out successfully.";
?>
