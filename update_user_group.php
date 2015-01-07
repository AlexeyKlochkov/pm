<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$user_group_id = $_POST["user_group_id"];
$user_group_name = $_POST["user_group_name"];
$update_success = update_user_group($user_group_id, $user_group_name);
if ($update_success <> 0){
	$location = "Location: edit_user_group.php?e=2&ug=" . $user_group_id;
}else{
	$location = "Location: edit_user_group.php?e=1&ug=" . $user_group_id;
}

header($location) ;
