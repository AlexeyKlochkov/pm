<?php
include "functions/dbconn.php";
include "functions/queries.php";
//ini_set('display_errors','On');
$username = strtoupper($_POST["username"]); //remove case sensitivity on the username
session_start();
$_SESSION["system_username"] = $username;
$arr_user_info = get_user_info_by_system_user_name($username);
if(empty($arr_user_info)){
    $location = "loggedout.php?e=3";
}else{

    $session_user_id = $arr_user_info[0]["user_id"];
    $session_user_full_name = $arr_user_info[0]["first_name"] . " " . $arr_user_info[0]["last_name"];
    $session_company_id =$arr_user_info[0]["company_id"];
    $_SESSION["user_id"] = $session_user_id;
    $_SESSION["company_id"] = $session_company_id;
    $_SESSION["user_full_name"] = $session_user_full_name;
    $_SESSION["user_level"] = $arr_user_info[0]["user_level"];
    $_SESSION["is_project_manager"] = $arr_user_info[0]["is_project_manager"];
    $login_update_success = update_last_login($session_user_id);
    if(!empty($_SESSION["redirect_url"])){
        $location = $_SESSION["redirect_url"];
        print 'update success';
    }else{
        $location = "index.php";
    }
}

print "<br>" . $location;
header('Location: ' . $location);
