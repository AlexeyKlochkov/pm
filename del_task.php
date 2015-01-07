<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";


$task_id = $_GET["t"];
$del_success = delete_task($task_id);

if ($del_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: new_task.php?e=" . $error;

header($location) ;
