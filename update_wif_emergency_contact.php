<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
error_reporting(E_ALL);

$user_id = $_POST["user_id"];

$update_success = update_admin_value('user_id_for_wif_emergency_emails', $user_id);

if ($update_success == 0){
	
	$location = "Location: wif_list.php?e=1";
}else{
	$location = "Location: wif_list.php?e=2";
	if ($new_project_id <> 0){
		$location = "Location: wif_list.php?e=3&pc=" . $project_code;
	}
}

header($location) ;


?>