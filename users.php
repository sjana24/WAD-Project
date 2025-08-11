<?php
include "includes/auth.php";
require_once "actions/getter_main.php";
require_once "db/config.php"; // <-- make sure this has $host, $user, $pass, $dbname

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// -------------------- Handle Reset Request --------------------
if (isset($_POST['reset_actions'])) {
    $enteredPassword = $_POST['admin_password'];

    $correctHash = '$2y$10$RqqCNLNAd6bW4S/pmCZPSeOp2U4jqfEpAHAc2HWKhk4SyBe5VrMrO';

    if (password_verify($enteredPassword, $correctHash)) {
        $db = new dbConnect();
        $conn = $db->getConnect();

        if ($conn === null) {
            die("Database connection failed.");
        }

        // PDO truncate query
        $stmt = $conn->prepare("TRUNCATE TABLE user_data");
        $stmt->execute();

        $stmt = $conn->prepare("TRUNCATE TABLE admins");
        $stmt->execute();

        $stmt = $conn->prepare("TRUNCATE TABLE access_logs");
        $stmt->execute();

        $stmt = $conn->prepare("TRUNCATE TABLE question");
        $stmt->execute();

        $_SESSION['message'] = "✅ User action list cleared.";
    } else {
        $_SESSION['message'] = "❌ Incorrect password. Reset aborted.";
    }

    header("Location: registration.php");
    exit;
}

// -------------------- Class to Manage Codes --------------------
class UserCodeManager
{
    public $codes;

    public function __construct()
    {
        $myObj = new getter_main();

        if (isset($_SESSION['message'])) {
            $msg = htmlspecialchars($_SESSION['message'], ENT_QUOTES);
            echo "<script>alert('{$msg}');</script>";
            unset($_SESSION['message']);
        }

        if (isset($_SESSION['code'])) {
            $msg = htmlspecialchars($_SESSION['code'], ENT_QUOTES);
            echo "<script>alert('Your code is-{$msg}');</script>";
            unset($_SESSION['code']);
        }

        $this->codes = $myObj->getAllUserDatas();
    }
}

$codeManager = new UserCodeManager();
$code = $codeManager->codes;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Codes - Admin Panel</title>
    <link rel="stylesheet" href="assests/css/users.css">
    <link rel="icon" type="image/png" href="./assests/images/logo.svg" />
</head>

<body>
    <div class="admin-container">
        <h1>👤 User Management</h1>

        <div class="nav-links">
            <a href="logs.php">📋 View Access Logs</a>

            <!-- Logout -->
            <form action="./actions/main_action.php" method="post" class="logout-form" style="display:inline;">
                <input type="hidden" name="logout" value="1" />
                <button type="submit" name="status">🔓 Logout</button>
            </form>

            <!-- Reset User Action List -->
            <form action="" method="post" style="display:inline; margin-left:10px;">
                <input type="password" name="admin_password" placeholder="Enter password" required />
                <button type="submit" name="reset_actions" style="background-color:orange;">🔄 Reset</button>
            </form>
        </div>

        <form method="POST" action="./actions/main_action.php" class="add-user-form"  onsubmit="return validateUserForm();">
            <h2>Add New User</h2>
            <label>Name:</label>
            <input type="text" name="name" id="name" placeholder="Enter name" required />
            <label>Mobile Number:</label>
            <input type="text" name="mobile_number" id="mobile_number" placeholder="Enter mobile number" required />
            <label>NIC:</label>
            <input type="text" name="nic"id="nic" placeholder="Enter NIC" required />
            <button type="submit">➕ Add Code</button>
        </form>

        <h2>User List</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($code as $index => $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($index + 1) ?></td>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
                        <td>
                            <?php
                            $status = $row['status'];
                            $class = ($status === 'active') ? 'background-color:green;' : 'background-color:red;';
                            $value = ($status === 'active') ? 'Active' : 'Inactive';
                            ?>
                            <form action="./actions/main_action.php" method="post">
                                <input type="hidden" name="edit" value="<?= htmlspecialchars($row['id']) ?>" />
                                <input type="hidden" name="status" value="<?= htmlspecialchars($row['status']) ?>" />
                                <button type="submit" name="submit-edit" style="<?= $class ?>">
                                    <?= htmlspecialchars($value) ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="./actions/main_action.php" method="post">
                                <input type="hidden" name="delete" value="<?= htmlspecialchars($row['id']) ?>" />
                                <button type="submit" name="submit-del" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
    <script src="assests/js/user.js"></script>
</body>
</html>