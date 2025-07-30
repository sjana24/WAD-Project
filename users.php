<?php include "includes/auth.php"; include "db/config.php"; ?>

<h2>User Codes</h2>

<form method="POST" action="actions/add_user.php">
    <input type="text" name="code" placeholder="Enter new code" required />
    <button type="submit">Add Code</button>
</form>

<table border="1">
    <tr><th>ID</th><th>Code</th><th>Action</th></tr>
    <?php
    $result = $conn->query("SELECT * FROM user_codes");
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['code']}</td>
                <td>
                    <form method='POST' action='actions/delete_user.php' style='display:inline'>
                        <input type='hidden' name='id' value='{$row['id']}' />
                        <button type='submit'>Delete</button>
                    </form>
                </td>
              </tr>";
    }
    ?>
</table>
