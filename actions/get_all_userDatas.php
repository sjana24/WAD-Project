<?php
require_once __DIR__ . '/../db/config.php';

class UserData {
    private $conn;

    public function __construct() {
        $db = new dbConnect();
        $this->conn = $db->connect();
    }

    public function getAllUserDatas() {
        // $sql="SELECT * FROM access_logs ORDER BY timestamp DESC";
$sql = "SELECT id,username,status FROM user_data WHERE delete_status=0";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

        // $stmt = $this->conn->query($sql);
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function logAttempt($code, $status) {
        $sql = "INSERT INTO access_logs (code_used, status) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$code, $status]);
    }
}
