<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$task_id = $_POST["task_id"];
$task_name = $_POST["task_name"];

if(!empty($_POST["task_rate"])){
	$task_rate = $_POST["task_rate"];
}else{
	$task_rate = "ignore";
}
$role_id = $_POST["role_id"];
$active = $_POST["active"];
$is_approval = $_POST["is_approval"];
$update_success = update_task($task_id, $task_name, $role_id, $task_rate, $is_approval, $active);

//print $audit_id;

if ($update_success <> 0){
	$location = "Location: edit_task.php?e=2&t=" . $task_id;
}else{
	$location = "Location: edit_task.php?e=1&t=" . $task_id;
}

header($location) ;


?>