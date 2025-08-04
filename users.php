<?php
include "includes/auth.php";
require_once "actions/getter_main.php";
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies


// Class to manage user codes
class UserCodeManager
{
    // private $conn;
    public $codes;

    public function __construct()
    {
        $myObj = new getter_main();

        if (isset($_SESSION['message'])) {

            $msg = htmlspecialchars($_SESSION['message'], ENT_QUOTES);
            echo "<script>alert('{$msg}');</script>";
            unset($_SESSION['message']);  // clear the message after showing

        }
        if (isset($_SESSION['code'])) {

            $msg = htmlspecialchars($_SESSION['code'], ENT_QUOTES);
            echo "<script>alert('Your code is-{$msg}');</script>";
            unset($_SESSION['code']);  // clear the message after showing

        }

        // if (isset($_SESSION['auth_times'])) {
        //     echo "<br>Login Time: " . $_SESSION['auth_times'];
        // }

        // if (isset($_SESSION['status'])) {
        //     echo "<br>Status: " . $_SESSION['status'];
        // }
        $this->codes = $myObj->getAllUserDatas();
        // print_r($this->codes); // Debugging line
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
    <link rel="stylesheet" href="assests/css/admin_panel.css">
</head>
<body>
    <!-- Existing PHP code for alerts will stay here -->

    <div class="admin-container">
        <h1>👤 User Management</h1>

        <div class="nav-links">
            <a href="logs.php">📋 View Access Logs</a>
            <form action="./actions/main_action.php" method="post" class="logout-form">
                <input type="hidden" name="logout" value="1" />
                <button type="submit" name="status">🔓 Logout</button>
            </form>
        </div>

        <form method="POST" action="./actions/main_action.php" class="add-user-form">
            <h2>Add New User</h2>
            <label>Name:</label>
            <input type="text" name="name" placeholder="Enter name" required />
            <label>Mobile Number:</label>
            <input type="text" name="mobile_number" placeholder="Enter mobile number" required />
            <label>NIC:</label>
            <input type="text" name="nic" placeholder="Enter NIC" required />
            <button type="submit">➕ Add Code</button>
        </form>

        <h2>User List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($code as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
                        <td>
                            <form action="./actions/main_action.php" method="post">
                                <input type="hidden" name="edit" value="<?= htmlspecialchars($row['id']) ?>" />
                                <button type="submit" name="status">
                                    <?= htmlspecialchars($row['status']) ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="./actions/main_action.php" method="post">
                                <input type="hidden" name="delete" value="<?= htmlspecialchars($row['id']) ?>" />
                                <button type="submit" name="status" class="delete-btn">Delete</button>
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
</body>
</html>
