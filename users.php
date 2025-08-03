<?php
// include "includes/auth.php";
require_once "actions/getter_main.php";
session_start();


// Class to manage user codes
class UserCodeManager
{
    // private $conn;
    public $codes;

    public function __construct()
    {
        $myObj=new getter_main();
        
        if (isset($_SESSION['user_id'])) {
            echo "Logged in as: " . htmlspecialchars($_SESSION['user_id']);
        }

        if (isset($_SESSION['auth_times'])) {
            echo "<br>Login Time: " .$_SESSION['auth_times'];
        }

        if (isset($_SESSION['status'])) {
            echo "<br>Status: " . $_SESSION['status'];
        }
         $this->codes = $myObj->get_all_userDatas();
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
    <!-- HTML Output -->
    <h2>User Datas</h2>
    <a href="logs.php">View Access Logs</a>
    <a href="./actions/admin_logout.php">🔑 Log out</a>
    <?php 
    if(1){
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
    }?>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Username</th>
            <th colspan="2">Action</th>
        </tr>
        <?php foreach ($code as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['code']) ?></td>
                <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
                <!-- <td><?= htmlspecialchars($row['status'] ?? '-') ?></td> -->
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

</html>