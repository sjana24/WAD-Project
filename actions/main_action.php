<?php
// require_once '../db/config.php';
require_once './login_action.php';


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $submittedCode = $_POST['access_code'] ?? '';
            $submittedUsername = $_POST['username'] ?? '';
            $submittedPassword = $_POST['password'] ?? '';


            if (!empty($submittedCode) && trim($submittedCode) !== '') {
                $myObj = new LoginAction();
                $myObj->handlePasscode($submittedCode);
            }

            if (!empty($submittedUsername) && trim($submittedUsername) !== '' && !empty($submittedPassword) && trim($submittedPassword) !== '') {
                echo "$submittedUsername, $submittedPassword";
                $myObj = new LoginAction();
                $myObj->admin_login($submittedUsername, $submittedPassword);
                
            }
        }
    // }

// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "dofndofj";
// $my=new main();
}
else{
    echo "kjdngjdnsg";
}