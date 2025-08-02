<?php
// require_once "db/config.php";
class AccessHandler {
    private $conn;

    public function __construct($db) {
        // $myObject = new dbConnect();
        $this->conn = $db;
    }

    public function checkCode($code) {
        $stmt = $this->conn->prepare("SELECT * FROM user_data WHERE code = :code");
        $stmt->bindParam(":code", $code);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? 'Success' : 'Fail';
    }

    public function logAttempt($code, $status) {
        $stmt = $this->conn->prepare("INSERT INTO access_logs (code_used, status) VALUES (:code, :status)");
        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
    }
}
