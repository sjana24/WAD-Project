<?php
session_start();
require_once "../db/config.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admins WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: ../logs.php");
        exit();
    }
}

echo "Invalid login.";
?>
