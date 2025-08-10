<?php
require_once "includes/auth.php";
require_once "actions/getter_main.php";

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

$log = new getter_main();
$logs = $log->getAllLogs();

// Enforce session authentication
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Access Logs - Admin Panel</title>
    <link rel="icon" type="image/png" href="./assests/images/logo.svg" />
    <link rel="stylesheet" href="assests/css/admin_panel.css">
</head>

<body>
    <div class="admin-container">
        <h1>📋 Access Logs</h1>

        <div class="nav-links">
            <a href="users.php">👥 Manage Users</a>
            <!-- <form action="./actions/auth_action.php" method="post" class="logout-form">
                <input type="hidden" name="logout" value="1" />
                <button type="submit" name="status">🔓 Logout</button>
            </form> -->
             <form action="./actions/main_action.php" method="post" class="logout-form">
                <input type="hidden" name="logout" value="1" />
                <button type="submit" name="status">🔓 Logout</button>
            </form>
        </div>

        <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Code Used</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $index=> $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($index+1) ?></td>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['used_code'] ?? '****') ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['timestamp']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

    <script>
        // Detect if page is loaded from cache (Back Button)
        window.addEventListener("pageshow", function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</body>

</html>
