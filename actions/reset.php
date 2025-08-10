<?php
require_once "./auth_action.php";
$auth = new Auth_Action();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1️⃣ Check if new password is being posted (second step)
    if (isset($_POST['new_password'])) {
        $newPassword = $_POST['new_password'];
        
        // Update password in DB
        $updateResponse = $auth->change_admin_password(2, $newPassword); // example with id=2
        $updateData = json_decode($updateResponse, true);

        if ($updateData['success']) {
            $_SESSION['message'] = $updateData['message'];
            echo "<script> window.location.href='../admin_login.php';</script>";
        } else {
            $_SESSION['message'] = $updateData['message'] ?? 'Password update failed.';
            echo "<script> window.location.href='../admin_login.php';</script>";
        }
        exit;
    }

    // 2️⃣ If this is the first step, process the security answer
    if (isset($_POST['answer'])) {
        $answer = $_POST['answer'];
        echo "You entered: " . htmlspecialchars($answer);

        $response = $auth->reset_password($answer);
        $data = json_decode($response, true);

        if ($data['success']) {
            echo "<script>
                function beforeSubmit(newPassword) {
                    console.log('Password before sending:', newPassword);
                }

                let newPassword = prompt('Enter your new password:');
                if (newPassword === null || newPassword.trim() === '') {
                    alert('Password entry cancelled or empty!');
                    window.location.href = '../admin_login.php';
                } else {
                    let confirmPassword = prompt('Re-enter your new password:');
                    if (confirmPassword === null || confirmPassword.trim() === '') {
                        alert('Password confirmation cancelled or empty!');
                        window.location.href = '../admin_login.php';
                    } else if (newPassword !== confirmPassword) {
                        alert('Passwords do not match!');
                        window.location.href = '../admin_login.php';
                    } else {
                        beforeSubmit(newPassword);

                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'reset.php';

                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'new_password';
                        input.value = newPassword;
                        form.appendChild(input);

                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            </script>";
            exit;
        } else {
            $_SESSION['message'] = $data['message'];
            // echo "<script>alert('{$data['message']}')</script>;
            echo "<script>
             window.location.href='../admin_login.php';</script>";
            exit;
        }
    }
} else {
    echo "No POST data received.";
}
?>
