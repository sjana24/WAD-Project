<?php include "includes/auth.php"; include "db/config.php"; ?>

<h2>Access Logs</h2>
<table border="1">
    <tr><th>ID</th><th>Code Used</th><th>Status</th><th>Time</th></tr>
    <?php
    $result = $conn->query("SELECT * FROM access_logs ORDER BY timestamp DESC");
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['code_used']}</td>
                <td>{$row['status']}</td>
                <td>{$row['timestamp']}</td>
              </tr>";
    }
    ?>
</table>
