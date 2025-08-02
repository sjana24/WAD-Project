<?php
// Start session and include database config
session_start();

include 'config.php';

// Add new user
if (isset($_POST['add_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $code = mysqli_real_escape_string($conn, $_POST['code']);

    if (!empty($username) && !empty($code)) {
        $sql = "INSERT INTO user_data (username, code, status) VALUES ('$username', '$code', 'active')";
        if ($conn->query($sql)) {
            $message = "✅ User added successfully.";
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    }
}

// Delete user
if (isset($_POST['delete_user'])) {
    $delete_id = intval($_POST['delete_id']);
    $sql = "DELETE FROM user_data WHERE id = $delete_id";
    if ($conn->query($sql)) {
        $message = "✅ User deleted successfully.";
    } else {
        $message = "❌ Error deleting user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f4f4f4;
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            margin-right: 10px;
        }
        button {
            padding: 8px 12px;
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .message {
            font-weight: bold;
            margin: 10px 0;
            color: green;
        }
    </style>
</head>
<body>
    
    <h2>User Management</h2>

    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Enter username" required>
        <input type="text" name="code" placeholder="Enter access code" required pattern="\d{4,10}">
        <button type="submit" name="add_user">Add User</button>
    </form>

    <h3>Existing Users</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Code</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM user_data ORDER BY id DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['code'] ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td><?= $row['updated_at'] ?></td>
            <td>
                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this user?')">
                    <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_user">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
