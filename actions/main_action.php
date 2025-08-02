<?php
require_once './login_action.php';
require_once './add_user.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedCode = $_POST['access_code'] ?? '';
    $submittedUsername = $_POST['username'] ?? '';
    $submittedPassword = $_POST['password'] ?? '';

    $submittedName = $_POST['name'] ?? '';
    $submittedMobileNumber = $_POST['mobile_number'] ?? '';
    $submittedNIC = $_POST['nic'] ?? '';


    //user passcode handle
    if (!empty($submittedCode) && trim($submittedCode) !== '') {
        $myObj = new LoginAction();
        $myObj->handlePasscode($submittedCode);
    }

    //admin login handle
    if (!empty($submittedUsername) && trim($submittedUsername) !== '' && !empty($submittedPassword) && trim($submittedPassword) !== '') {
        // echo "$submittedUsername, $submittedPassword";
        $myObj = new LoginAction();
        $response=$myObj->admin_login($submittedUsername, $submittedPassword);
         $data = json_decode($response, true);

        if ($data['success']) {
            // echo $data['success'];
            echo $data['message'];
            // echo $data['code'];
        } else {
            echo $data['success'];
            echo  $data['message'];
            echo  $data['error'];
        }
    }

    // Handle Add new Code
    if (!empty($submittedName) && trim($submittedName) !== '' && !empty($submittedNIC) && trim($submittedNIC) !== '' && !empty($submittedMobileNumber) && trim($submittedMobileNumber) !== '') {
        // echo "$submittedName, $submittedMobileNumber,$submittedNIC";
        $myObj = new Add_User();
        $response = $myObj->add_user($submittedName, $submittedMobileNumber, $submittedNIC);

        $data = json_decode($response, true);

        if ($data['success']) {
            echo $data['success'];
            echo $data['message'];
            echo $data['code'];
        } else {
            echo $data['success'];
            echo  $data['message'];
        }
    }
}
