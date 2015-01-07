<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$user_group_member_id = $_GET["ugmid"];
$user_group_id = $_GET["ug"];

$del_success = delete_user_group_member($user_group_member_id);

if ($del_success == 1){
	$error = 3;
}else{
	$error = 4;
}

$location = "Location: edit_user_group.php?e=" . $error . "&ug=" . $user_group_id;

header($location) ;
