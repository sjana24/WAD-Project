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

<head>
    <title>User Codes</title>
</head>

<body>
    <?php
    if (!isset($_SESSION['user_id'])) {
        header("Location: admin_login.php");
        exit();
    }
    ?>

    <!-- HTML Output -->
    <h2>User Datas</h2>
    <a href="logs.php">View Access Logs</a>
    <!-- <a onclick="" valu href="./actions/admin_logout.php">🔑 Log out</a> -->
    <form action="./actions/main_action.php" method="post">
        <input type="hidden" name="logout" value="1" />
        <input type="submit" name="status" value="Logout" />
    </form>
    <?php
    if (1) {
    ?>
        <form method="POST" action="./actions/main_action.php">
            <label id="name">Name:</label>
            <input type="text" name="name" placeholder="Enter new name" required /><br>
            <label id="mobile_number">Mobile Number:</label>
            <input type="text" name="mobile_number" placeholder="Enter mobile number" required /><br>
            <label id="nic">NIC:</label>
            <input type="text" name="nic" placeholder="Enter NIC" required /></br>
            <button type="submit">Add Code</button>
        </form>
    <?php
    } ?>

    <table border="1">
        <tr>
            <th>ID</th>
            <!-- <th>Code</th> -->
            <th>Username</th>
            <th colspan="2">Action</th>
        </tr>
        <?php foreach ($code as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>

                <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>

                <td>
                    <form action="./actions/main_action.php" method="post">
                        <input type="hidden" name="edit" value="<?= htmlspecialchars($row['id']) ?>" />
                        <input type="submit" name="status" value="<?= htmlspecialchars($row['status']) ?>" />
                    </form>
                </td>
                <td>
                    <form action="./actions/main_action.php" method="post">
                        <input type="hidden" name="delete" value="<?= htmlspecialchars($row['id']) ?>" />
                        <input type="submit" name="status" value="Delete" />
                    </form>
                </td>

            </tr>
        <?php endforeach; ?>
    </table>

</body>
<!-- <script>
    sesssion_start();
    if (isset($_SESSION['user_id'])) {
            // echo "Logged in as: " . htmlspecialchars($_SESSION['user_id']);
            setTimeout(function() {
        location.reload();
    }, 3000); // Reload after 3 seconds
        }
    
</script> -->
<script>
    // Detect if page is loaded from cache (Back Button)
    window.addEventListener("pageshow", function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            // Reload to fetch fresh session
            window.location.reload();
        }
    });
</script>




</html>