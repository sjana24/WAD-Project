<?php
require_once "includes/auth.php";
// require_once "actions/get_all_logs.php";
require_once "actions/getter_main.php";

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

$log = new getter_main();
$logs = $log->get_all_logs();

// Enforce session authentication
if (!isset($_SESSION['user_id'])) {
    // User is not logged in → Redirect to login
    header("Location: admin_login.php");
    exit;
}

?>

<h2>Access Logs</h2>

    
    <!-- <a href="admin_login.php">🔑 Admin Login</a> -->
    <a href="./actions/admin_logout.php">🔑 Log out</a>
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
<script>
// Detect if page is loaded from cache (Back Button)
window.addEventListener( "pageshow", function ( event ) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        // Reload to fetch fresh session
        window.location.reload();
    }
});
</script>