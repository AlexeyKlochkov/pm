<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$schedule_template_id = $_GET["stid"];
$active = $_GET["a"];

if($active == 2){
	$active = 0;
}

$active_success = activate_schedule_template($schedule_template_id, $active);

if ($active_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: new_schedule_template.php?e=" . $error;
header($location) ;
