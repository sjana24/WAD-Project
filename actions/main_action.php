<?php
require_once './login_action.php';
require_once './add_user.php';
require_once './status_mange.php';
require_once './delete_user.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedCode = $_POST['access_code'] ?? '';
    $submittedUsername = $_POST['username'] ?? '';
    $submittedPassword = $_POST['password'] ?? '';

    $submittedName = $_POST['name'] ?? '';
    $submittedMobileNumber = $_POST['mobile_number'] ?? '';
    $submittedNIC = $_POST['nic'] ?? '';

    $delete_id = $_POST['delete'] ?? '';
    
    $edit_id = $_POST['edit'] ?? '';
    $status=$_POST['status'] ?? '';
    if (!empty($delete_id) || !empty($edit_id))
    if(!empty($delete_id)){
        // echo "delete id is-".$delete_id;
        $myObj=new Delete_User();
    // $updatedStatus=$status=== "active" ? "inactive" :"active";
    echo "<br>delete id is".$delete_id."-";
    $response=$myObj->deleteUser($delete_id);
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
    else{
    // echo "<br>edit id is-".$edit_id;
    $myObj=new Edit_User();
    $updatedStatus=$status=== "active" ? "inactive" :"active";
    echo "<br>edit id is".$edit_id."-".$updatedStatus;
    $response=$myObj->user_status_manage($edit_id,$updatedStatus);
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
    // $response=$myObj->user_status_manage($abc,$updatedStatus)
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
