<?php
require_once "../db/config.php";

$code = $_POST['code'];

// Check code
$sql = "SELECT * FROM user_codes WHERE code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();

$status = $result->num_rows > 0 ? 'Success' : 'Fail';

// Log the attempt
$log = $conn->prepare("INSERT INTO access_logs (code_used, status) VALUES (?, ?)");
$log->bind_param("ss", $code, $status);
$log->execute();

echo ($status === 'Success') ? "Access Granted ✅" : "Access Denied ❌";
?>
