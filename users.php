<?php
include "includes/auth.php";
include "db/config.php";

// Class to manage user codes
class UserCodeManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllCodes() {
        $sql = "SELECT * FROM user_data";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCode($username,$code) {
        $sql = "INSERT INTO user_data (username,code, created_at, updated_at) VALUES (:username,:code, NOW(), NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username'=>$username,':code' => $code]);
    }

    public function deleteCode($id) {
        $sql = "DELETE FROM user_data WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    function generateAccessCode($mobile, $nationalId) {
    $input = $mobile . $nationalId;

    // Step 1: Create hash (secure and irreversible)
    $hash = hash('sha256', $input);

    // Step 2: Convert part of hash to number
    $decimal = crc32($hash); // or use hexdec(substr($hash, 0, 8));

    // Step 3: Get 4-digit code from it
    $code = $decimal % 10000;

    // Step 4: Pad with zeros (e.g., 0034)
    return str_pad($code, 4, '0', STR_PAD_LEFT);
}
}

// Create object and database connection
$myObject = new dbConnect();
$conn = $myObject->connect();
$codeManager = new UserCodeManager($conn);

// Handle Add Code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nic'])) {
    $name=$_POST['name'];
    $number=$_POST['mobile_number'];
    $nic=$_POST['nic'];
    $code = $codeManager->generateAccessCode($number, $nic);
    // echo "Generated Code: $code"; // Debugging line
    // echo $name,$number,$nic,$code;

    $codeManager->addCode($name,$code);
    header("Location: users.php");
    exit;
}

// Handle Delete Code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $codeManager->deleteCode($_POST['id']);
    header("Location: users.php");
    exit;
}

// Fetch all codes
$codes = $codeManager->getAllCodes();
?>

<!-- HTML Output -->
<h2>User Codes</h2>
<a href="actions/admin_logout.php">🔑 Log out</a>
<form method="POST">
    <label id="name" >Name:</label>
    <input type="text" name="name" placeholder="Enter new name" required /><br>
    <label id="mobile_number" >Mobile Number:</label>
    <input type="text" name="mobile_number" placeholder="Enter mobile number" required /><br>
    <label id="nic" >NIC:</label>
    <input type="text" name="nic" placeholder="Enter NIC" required /></br>
    <button type="submit">Add Code</button>
</form>

<table border="1">
    <tr><th>ID</th><th>Code</th><th>Username</th><th>Action</th></tr>
    <?php foreach ($codes as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['code']) ?></td>
            <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
            <!-- <td><?= htmlspecialchars($row['status'] ?? '-') ?></td> -->
            <td>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>" />
                    <button type="submit"><?= htmlspecialchars($row['status'] ?? '-') ?></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
