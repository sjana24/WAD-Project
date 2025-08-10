<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $id = trim($_POST['id']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to DB (PDO)
        $pdo = new PDO("mysql:host=localhost;dbname=security_system", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert into DB
        $stmt = $pdo->prepare("INSERT INTO users (id, username, email, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $username, $email, $hashedPassword]);

        echo "Registration successful!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration form</title>
    <link rel="stylesheet" href="assests/css/registration_form.css">
</head>
<body>

<form method="POST" action="index.php">
        <h2>Register Here!</h2>
        
        <label>Username:</label><br>
        <input type="text" name="username" required autofocus><br><br>

        <label>Id:</label><br>
        <input type="text" name="id" required autofocus><br><br>

        <label>Email:</label><br>
        <input type="text" name="email" required placeholder="abc@email.com" autofocus><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" placeholder="" required><br><br>

        <label>Re-Enter the Password:</label><br>
        <input type="password" name="password" placeholder="" required><br><br>

        <button type="submit">Submit</button>
</form>
    


</body>
</html>