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

    $xyz = $_POST['xyz'] ?? '';
    echo $xyz;
    // $submittedNIC = $_POST['nic'] ?? '';


    //user passcode handle
    if (!empty($submittedCode) && trim($submittedCode) !== '') {
        $myObj = new LoginAction();
        $response = $myObj->handlePasscode($submittedCode);
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

    //admin login handle
    if (!empty($submittedUsername) && trim($submittedUsername) !== '' && !empty($submittedPassword) && trim($submittedPassword) !== '') {
        // echo "$submittedUsername, $submittedPassword";
        $myObj = new LoginAction();
        $response = $myObj->admin_login($submittedUsername, $submittedPassword);
        $data = json_decode($response, true);

        if ($data['success']) {
            // echo $data['success'];
            echo $data['message'];
            header("Location: ../users.php");
            exit;

            // echo $data['code'];
        } else {
            echo $data['success'];
            echo  $data['message'];
            echo  $data['error'];
            header("Location: ../admin_login.php");
            exit;

        }
    }

    // Handle Add new Code
    if (!empty($submittedName) && trim($submittedName) !== '' && !empty($submittedNIC) && trim($submittedNIC) !== '' && !empty($submittedMobileNumber) && trim($submittedMobileNumber) !== '') {

        $myObj = new Add_User();
        $response = $myObj->add_user($submittedName, $submittedMobileNumber, $submittedNIC);

        $data = json_decode($response, true);

        if ($data['success']) {
            echo $data['success'];
            echo $data['message'];
            echo $data['code'];
            header("Location: ../users.php");
            exit;
        } else {
            echo $data['success'];
            echo  $data['message'];
            header("Location: ../users.php");
            exit;
        }
    }
}
