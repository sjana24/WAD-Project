<?php

require_once './auth_action.php';
// require_once './add_user.php';
// require_once './status_mange.php';
// require_once './delete_user.php';
require_once './admin_actions.php';

$myObj = new Admin_Actions();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedCode = $_POST['access_code'] ?? '';

    $submittedUsername = $_POST['username'] ?? '';
    $submittedPassword = $_POST['password'] ?? '';

    $submittedName = $_POST['name'] ?? '';
    $submittedMobileNumber = $_POST['mobile_number'] ?? '';
    $submittedNIC = $_POST['nic'] ?? '';

    $delete_id = $_POST['delete'] ?? '';

    $edit_id = $_POST['edit'] ?? '';
    $status = $_POST['status'] ?? '';
    $logout=$_POST['logout'] ?? '';
    echo ";ogout vansd".$logout;
    // Sanitize and Validate Inputs

    // $submittedCode = isset($_POST['access_code']) ? filter_var(trim($_POST['access_code']), FILTER_SANITIZE_STRING) : '';
    // if (!preg_match('/^\d{4}$/', $submittedCode)) {
    //     $submittedCode = ''; // Invalid code format (expecting 4-digit code)
    // }

    // $submittedUsername = isset($_POST['username']) ? filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING) : '';
    // if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $submittedUsername)) {
    //     $submittedUsername = ''; // Invalid username
    // }

    // $submittedPassword = isset($_POST['password']) ? filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING) : '';
    // if (strlen($submittedPassword) < 6) {
    //     $submittedPassword = ''; // Weak password
    // }

    // $submittedName = isset($_POST['name']) ? filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING) : '';
    // if (!preg_match('/^[a-zA-Z ]{2,100}$/', $submittedName)) {
    //     $submittedName = ''; // Invalid name
    // }

    // $submittedMobileNumber = isset($_POST['mobile_number']) ? filter_var(trim($_POST['mobile_number']), FILTER_SANITIZE_STRING) : '';
    // if (!preg_match('/^\d{10,15}$/', $submittedMobileNumber)) {
    //     $submittedMobileNumber = ''; // Invalid mobile number
    // }

    // $submittedNIC = isset($_POST['nic']) ? filter_var(trim($_POST['nic']), FILTER_SANITIZE_STRING) : '';
    // if (!preg_match('/^[0-9]{9}[vVxX]?$|^[0-9]{12}$/', $submittedNIC)) {
    //     $submittedNIC = ''; // Invalid NIC (Old or New format)
    // }

    // $delete_id = isset($_POST['delete']) ? filter_var(trim($_POST['delete']), FILTER_SANITIZE_NUMBER_INT) : '';
    // if (!is_numeric($delete_id)) {
    //     $delete_id = ''; // Invalid delete id
    // }

    // $edit_id = isset($_POST['edit']) ? filter_var(trim($_POST['edit']), FILTER_SANITIZE_NUMBER_INT) : '';
    // if (!is_numeric($edit_id)) {
    //     $edit_id = ''; // Invalid edit id
    // }

    // $status = isset($_POST['status']) ? filter_var(trim($_POST['status']), FILTER_SANITIZE_STRING) : '';
    // if (!in_array($status, ['active', 'inactive', 'deleted'])) {
    //     $status = ''; // Invalid status value
    // }


    //  1.admin login handle
    if (!empty($submittedUsername) && trim($submittedUsername) !== '' && !empty($submittedPassword) && trim($submittedPassword) !== '') {
        $myObj = new Auth_Action();
        $response = $myObj->admin_login($submittedUsername, $submittedPassword);
        $data = json_decode($response, true);

        if ($data['success']) {
            // echo $data['success'];
            // echo $data['message'];
            // echo "<script>alert({$_data['message']});</script>";
            $_SESSION['message'] = $data['message'];
            header("Location: ../users.php");
            exit;
        } else {
            // echo $data['success'];
            // echo  $data['message'];
            $_SESSION['message'] = $data['message'];
            // echo  $data['error'];
            header("Location: ../admin_login.php");
            exit;
        }
    }

    //  2.admin logout handle
    if (!empty($logout) && $logout== '1') {
        echo "dfknofdnofdngod";
        $myObj = new Auth_Action();
        $response = $myObj->admin_logout();
        $data = json_decode($response, true);

        if ($data['success']) {
            // echo $data['success'];
            // echo $data['message'];
            // echo "<script>alert({$_data['message']});</script>";
            $_SESSION['message'] = $data['message'];
            header("Location: ../admin_login.php");
            exit;
        } else {
            // echo $data['success'];
            // echo  $data['message'];
            $_SESSION['message'] = $data['message'];
            // echo  $data['error'];
            header("Location: ../admin_login.php");
            exit;
        }
    }

    // 2. Handle Add new Code
    if (!empty($submittedName) && trim($submittedName) !== '' && !empty($submittedNIC) && trim($submittedNIC) !== '' && !empty($submittedMobileNumber) && trim($submittedMobileNumber) !== '') {

        $response = $myObj->add_user($submittedName, $submittedMobileNumber, $submittedNIC);

        $data = json_decode($response, true);

        if ($data['success']) {
            echo $data['success'];
            echo $data['message'];
            $_SESSION['message'] = $data['message'];
            echo $data['code'];
            $_SESSION['code'] = $data['code'];
            header("Location: ../users.php");
            exit;
        } else {
            echo $data['success'];
            echo  $data['message'];
            $_SESSION['message'] = $data['message'];
            header("Location: ../users.php");
            exit;
        }
    }


    // 3. manage user
    if (!empty($delete_id) || !empty($edit_id))
        if (!empty($delete_id)) {
            $response = $myObj->deleteUser($delete_id);
            $data = json_decode($response, true);

            if ($data['success']) {
                // echo $data['success'];
                $_SESSION['message'] = $data['message'];
                // echo $data['message'];
                // echo $data['code'];
                header("Location: ../users.php");
                exit;
            } else {
                echo $data['success'];
                echo  $data['message'];
                $_SESSION['message'] = $data['message'];
                echo  $data['error'];
                header("Location: ../users.php");
                exit;
            }
        } else {
            $updatedStatus = $status === "active" ? "inactive" : "active";
            echo "<br>edit id is" . $edit_id . "-" . $updatedStatus."dfsdfd".$status;
            $response = $myObj->user_status_manage($edit_id, $updatedStatus);
            $data = json_decode($response, true);

            if ($data['success']) {
                // echo $data['success'];
                echo $data['message'];
                $_SESSION['message'] = $data['message'];
                // echo $data['code'];
                header("Location: ../users.php");
                exit;
            } else {
                echo $data['success'];
                echo  $data['message'];
                $_SESSION['message'] = $data['message'];
                echo  $data['error'];
                header("Location: ../users.php");
                exit;
            }
        }



    //  4.user passcode handle
    if (!empty($submittedCode) && trim($submittedCode) !== '') {
        $myObj = new Auth_Action();
        $response = $myObj->handlePasscode($submittedCode);
        $data = json_decode($response, true);

        if ($data['success']) {
            // echo $data['success'];
            echo $data['message'];
            $_SESSION['message'] = $data['message'];
            header("Location: ../index.php");
                exit;
            // echo $data['code'];
        } else {
            echo $data['success'];
            echo  $data['message'];
            $_SESSION['message'] = $data['message'];
            // $_SESSION['message'] = "knsdvjdsnjn";
            echo  $data['error'];
            header("Location: ../index.php");
                exit;
        }
    }
}
