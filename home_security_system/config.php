<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // (default is blank in XAMPP)
$db   = 'security_system';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
