<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$task_name = $_POST["task_name"];
$task_rate = $_POST["task_rate"];
$role_id = $_POST["role_id"];
$is_approval = $_POST["is_approval"];

$new_task_id = insert_task($company_id, $task_name, $role_id, $task_rate, $is_approval);

//print $audit_id;

if ($new_task_id <> 0){
	$location = "Location: new_task.php?e=2";
}else{
	$location = "Location: new_task.php?e=1";
}

header($location) ;


?>