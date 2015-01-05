<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$task_id = $_GET["t"];
$active = $_GET["a"];

if($active == 2){
	$active = 0;
}

$active_success = activate_task($task_id, $active);

if ($active_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: new_task.php?e=" . $error;
header($location) ;
