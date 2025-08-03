<?php
include "includes/auth.php";
require_once "get_all_logs.php";
require_once "get_all_userDatas.php";

class getter_main{
    public function get_all_logs(){
        $myObj=new AccessLog();
        return $myObj->getAllLogs();
        exit;

    }
    public function get_all_userDatas(){
        $myObj=new UserData();
        return $myObj->getAllUserDatas();
        exit;

    }
}