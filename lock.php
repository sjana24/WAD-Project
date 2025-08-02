<?php
// lock.php
session_start();
require_once 'database.php';

$message = '';
$messageType = '';

$db = new dbConnect();
$pdo = $db->connect();

if ($pdo) {
    // Optional: only show this if you're debugging
    // echo "✅ Connection successful!";
} else {
    echo "❌ Connection failed!";
    exit;
}

$submittedCode = $_POST['access_code'] ?? '';

if (!empty($submittedCode) && trim($submittedCode) !== '') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM user_data 
                               WHERE code = ? AND status = 'active'");
        $stmt->execute([$submittedCode]);
        $result = $stmt->fetch();

        if ($result) {
            // ✅ Code matches and is active
            $_SESSION['access_granted'] = true;
            $_SESSION['authenticated_user'] = $result['username'];
            $_SESSION['auth_time'] = time();
            
            $message = "Access Granted! Welcome, " . htmlspecialchars($result['username']) . ".";
            $messageType = 'success';

            // Log success
            $logStmt = $pdo->prepare("INSERT INTO access_logs (code_used, status, timestamp) VALUES (?, 'Success', NOW())");
            $logStmt->execute([$submittedCode]);

            // Redirect to dashboard after successful access
            header("refresh:2;url=index.php");
        } else {
            // ❌ Code does not match or is not active
            $message = "Access Denied: Invalid or inactive access code.";
            $messageType = 'error';

            // Log failure
            $logStmt = $pdo->prepare("INSERT INTO access_logs (code_used, status, timestamp) VALUES (?, 'Fail', NOW())");
            $logStmt->execute([$submittedCode]);
        }
    } catch (Exception $e) {
        $message = "An unexpected error occurred. Please try again later.";
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Security System - Lock Screen</title>
  <link rel="stylesheet" href="assets/css/lock.css">
</head>
<body>

  <div class="lock-container">
    <h1>🔒 Enter Access Code</h1>
    <form method="POST" action="lock.php" class="keypad-mode">
      <div class="form-group">
        <input type="password" name="access_code" id="accessCode" placeholder="••••" maxlength="4" required readonly />
      </div>
      
      <!-- Keypad -->
      <div class="keypad-container">
        <div class="keypad">
          <button type="button" class="keypad-btn" onclick="addDigit('1')">1</button>
          <button type="button" class="keypad-btn" onclick="addDigit('2')">2</button>
          <button type="button" class="keypad-btn" onclick="addDigit('3')">3</button>
          <button type="button" class="keypad-btn" onclick="addDigit('4')">4</button>
          <button type="button" class="keypad-btn" onclick="addDigit('5')">5</button>
          <button type="button" class="keypad-btn" onclick="addDigit('6')">6</button>
          <button type="button" class="keypad-btn" onclick="addDigit('7')">7</button>
          <button type="button" class="keypad-btn" onclick="addDigit('8')">8</button>
          <button type="button" class="keypad-btn" onclick="addDigit('9')">9</button>
          <button type="button" class="keypad-btn clear" onclick="clearCode()">C</button>
          <button type="button" class="keypad-btn" onclick="addDigit('0')">0</button>
          <button type="button" class="keypad-btn backspace" onclick="backspace()">⌫</button>
        </div>
        
        <!-- Submit Button -->
        <button type="button" class="submit-btn" onclick="submitCode()">Submit</button>
      </div>
    </form>

    <?php if (!empty($message)): ?>
      <div class="message <?php echo $messageType; ?>">
        <?php echo $message; ?>
        <?php if ($messageType === 'success'): ?>
          <br><small>Redirecting to home...</small>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="links">
      <a href="index.php">🏠 Home</a>
    </div>
  </div>

  <script src="assets/js/lock.js"></script>
</body>
</html>
