<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$system_user_name = $_POST["system_user_name"];
$email = $_POST["email"];
$initials = $_POST["initials"];
$role_id = $_POST["role_id"];
$is_project_manager = 0;
$is_aps_admin = 0;

if(!empty($_POST["is_project_manager"])){
	$is_project_manager = 1;
}

$is_aps_admin = 0;
if(!empty($_POST["is_aps_admin"])){
	$is_aps_admin = 1;
}

$user_level = $_POST["user_level"];

$add_success = add_user($company_id, $first_name, $last_name, $system_user_name, $email, $initials, $role_id, $is_project_manager, $is_aps_admin, $user_level);

if($add_success == 1){
	$location = "Location: manage_users.php?e=1";
}else{
	$location = "Location: manage_users.php?e=2";
}

header($location) ;
