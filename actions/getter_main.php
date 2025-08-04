<?php
require_once __DIR__ . '/../db/config.php';
// require_once "get_all_logs.php";
// require_once "get_all_userDatas.php";

class getter_main
{




    private $conn;

    public function __construct()
    {
        $db = new dbConnect();
        $this->conn = $db->getConnect();
    }
    // public function get_all_logs(){
    //     $myObj=new AccessLog();
    //     return $myObj->getAllLogs();
    //     exit;

    // }
    // public function get_all_userDatas(){
    //     $myObj=new UserData();
    //     return $myObj->getAllUserDatas();
    //     exit;

    // }



    public function getAllUserDatas()
    {
        // $sql="SELECT * FROM access_logs ORDER BY timestamp DESC";
        $sql = "SELECT id,username,status FROM user_data WHERE delete_status=0";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

        // $stmt = $this->conn->query($sql);
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllLogs()
    {
        // $sql="SELECT * FROM access_logs ORDER BY timestamp DESC";
        $sql = "
SELECT 
    access_logs.id,
    CASE 
        WHEN user_data.username IS NOT NULL THEN user_data.username 
        ELSE 'Unknown' 
    END AS username,
    CASE 
        WHEN user_data.username IS NULL THEN access_logs.code_used 
        ELSE NULL 
    END AS used_code,
    access_logs.status,
    access_logs.timestamp
FROM access_logs
LEFT JOIN user_data ON access_logs.code_used = user_data.code
ORDER BY access_logs.timestamp DESC;

";


        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function logAttempt($code, $status)
    {
        $sql = "INSERT INTO access_logs (code_used, status) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$code, $status]);
    }
}
