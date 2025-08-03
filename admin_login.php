 <?php
    if (isset($_SESSION['message'])) {

        $msg = htmlspecialchars($_SESSION['message'], ENT_QUOTES);
        echo "<script>alert('{$msg}');</script>";
        unset($_SESSION['message']);  // clear the message after showing

    }
    ?>
 <!DOCTYPE html>

 <head>
     <title>Admin Login</title>
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

 </html>