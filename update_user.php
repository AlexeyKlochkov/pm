<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$user_id = $_POST["user_id"];
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$email = $_POST["email"];
$system_user_name = $_POST["system_user_name"];
$initials = $_POST["initials"];
$role_id = $_POST["role_id"];
$is_project_manager = 0;
if(!empty($_POST["is_project_manager"])){
	$is_project_manager = 1;
}

$is_aps_admin = 0;
if(!empty($_POST["is_aps_admin"])){
	$is_aps_admin = 1;
}

$user_level = $_POST["user_level"];
$active = 0;
if(!empty($_POST["active"])){
	$active = 1;
}

$update_success = update_user($user_id, $first_name, $last_name, $email, $initials, $role_id, $is_project_manager, $is_aps_admin, $user_level, $system_user_name, $active);



if ($update_success <> 0){
	$location = "Location: edit_user.php?e=1&u=" . $user_id;
}else{
	$location = "Location: edit_user.php?e=2&u=" . $user_id;
}


header($location) ;

?>