<?php
require_once "../db/config.php";
$code = $_POST['code'];
$conn->query("INSERT INTO user_codes (code) VALUES ('$code')");
header("Location: ../users.php");
?>
