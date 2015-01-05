<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$role_id = $_GET["r"];
$active = $_GET["a"];

if($active == 2){
	$active = 0;
}

$active_success = activate_role($role_id, $active);

if ($active_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: new_role.php?e=" . $error;
header($location) ;
