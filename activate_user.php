<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$user_id = $_GET["u"];
$active = $_GET["a"];

if($active == 2){
	$active = 0;
}

$active_success = activate_user($user_id, $active);

if ($active_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: manage_users.php?e=" . $error;
header($location) ;
