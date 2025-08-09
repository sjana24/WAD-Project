<?php
// lock.php
session_start();

// Clear the session messages after displaying
unset($_SESSION['login_message']);
unset($_SESSION['login_message_type']);
if (isset($_SESSION['message'])) {

            $msg = htmlspecialchars($_SESSION['message'], ENT_QUOTES);
            echo "<script>alert('{$msg}');</script>";
            unset($_SESSION['message']);  // clear the message after showing

        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Security System - Lock Screen</title>
  <link rel="stylesheet" href="assests/css/lock.css">
</head>
<body>

  <div class="lock-container">
    <h1>🔒 Enter Access Code</h1>
    <form method="POST" action="actions/main_action.php" class="keypad-mode">
      <div class="form-group">
        <input type="password" name="access_code" id="accessCode" placeholder="Enter your code" maxlength="40" required readonly />
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

  <script src="assests/js/lock.js"></script>
</body>
</html>
