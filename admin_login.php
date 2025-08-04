 <!-- <?php
    if (isset($_SESSION['message'])) {

        $msg = htmlspecialchars($_SESSION['message'], ENT_QUOTES);
        echo "<script>alert('{$msg}');</script>";
        unset($_SESSION['message']);  // clear the message after showing

    }
    ?>
 <!DOCTYPE html>

 <head>
     <title>Admin Login</title>
     <link rel="stylesheet" href="assests/css/admin_login.css">
 </head>

 <body>
     <h1>Welcome to the Admin Panel</h1>
     <p>Please log in to access the admin features.</p>
     <a href="./index.php">home</a>
     <form method="POST" action="actions/main_action.php">
         <h2>Admin Login</h2>
         <label for="username">Username:</label>
         <input type="text" name="username" placeholder="Username" required />
         <br><br>
         <label for="password">Password:</label>
         <input type="password" name="password" placeholder="Password" required maxlength="8" />
         <br><br>
         <button type="submit">Login</button>
     </form>

 </body>

 </html> -->

 <?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assests/css/admin_login.css">
</head>
<body>

<div class="login-container">
    <h1>🔑 Admin Panel</h1>
    <p>Please log in to access admin features.</p>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="session-message">
            <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES); ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <form method="POST" action="actions/main_action.php">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="Username" required />
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Password" required maxlength="8" />
        </div>

        <button type="submit" class="login-btn">Login</button>
    </form>

    <div class="links">
        <a href="./index.php">🏠 Home</a>
    </div>
</div>

</body>
</html>
