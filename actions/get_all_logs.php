<?php
require_once __DIR__ . '/../db/config.php';

class AccessLog {
    private $conn;

    public function __construct() {
        $db = new dbConnect();
        $this->conn = $db->connect();
    }

    public function getAllLogs() {
        // $sql="SELECT * FROM access_logs ORDER BY timestamp DESC";
        $sql = "
SELECT 
    access_logs.*, 
    user_data.username 
FROM access_logs
JOIN user_data ON access_logs.code_used = user_data.code
ORDER BY access_logs.timestamp DESC
";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function logAttempt($code, $status) {
        $sql = "INSERT INTO access_logs (code_used, status) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$code, $status]);
    }
}
