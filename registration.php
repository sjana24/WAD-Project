<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Registration form</title>
    <link rel="stylesheet" href="assests/css/registration_form.css" />
     <link rel="icon" type="image/png" href="./assests/images/logo.svg" />
</head>
<body>

<form method="POST" action="actions/registrationAction.php">
    <h2>Register Here!</h2>

    <label>Username:</label><br />
    <input type="text" name="username" required autofocus /><br /><br />

    <label>Password:</label><br />
    <input type="password" name="password" required /><br /><br />

    <label>Re-Enter the Password:</label><br />
    <input type="password" name="password_confirm" required /><br /><br />

    <label>Security Question:</label><br />
    <select name="question" required>
        <option value="" disabled selected>Select your security question</option>
        <option value="q1">What is your mother's maiden name?</option>
        <option value="q2">What was your first pet's name?</option>
        <option value="q3">What city were you born in?</option>
        <option value="q4">What is your favorite color?</option>
        <option value="q5">What was the make of your first car?</option>
    </select><br /><br />

    <label>Answer:</label><br />
    <input type="text" name="answer" required /><br /><br />

    <button type="submit">Submit</button>
</form>

</body>
</html>
