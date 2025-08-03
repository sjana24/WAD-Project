<?php
// require_once "includes/auth.php";
// require_once "actions/get_all_logs.php";
require_once "actions/getter_main.php";

$log = new getter_main();
$logs = $log->get_all_logs();



?>

<h2>Access Logs</h2>

    
    <!-- <a href="admin_login.php">🔑 Admin Login</a> -->
    <a href="./lock.php">🔑 Log out</a>
    <a href="users.php">🔑 Manage user</a>


<table border="1">
    <tr><th>ID</th><th>username</th><th>Code Used</th><th>Status</th><th>Time</th></tr>
    <?php foreach ($logs as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['code_used']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <!-- <td><?= htmlspecialchars($row['status']) ?></td> -->
            <td><?= htmlspecialchars($row['timestamp']) ?></td>
            
        </tr>
    <?php endforeach; ?>
</table>
