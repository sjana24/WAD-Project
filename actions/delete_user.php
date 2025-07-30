<?php
require_once "../db/config.php";
$id = $_POST['id'];
$conn->query("DELETE FROM user_codes WHERE id=$id");
header("Location: ../users.php");
?>
