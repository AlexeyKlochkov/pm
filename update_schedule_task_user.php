<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$schedule_id = $_POST["schedule_id"];
$schedule_task_id = $_POST["schedule_task_id"];
$dont_update = array("schedule_id", "schedule_task_id");
$delete_success = delete_schedule_task_assignees($schedule_task_id);
if(empty($_POST["approval"])){
	foreach($_POST as $key => $value) {
		if (!in_array($key, $dont_update)){
			$update_success = insert_schedule_task_assignee($schedule_task_id, $value);
		}
	}
}else{
	//this is an approval task, and only gets one user.
	$user_id = $_POST["user_id"];
	$update_success = insert_schedule_task_assignee($schedule_task_id, $user_id);
}
if ($update_success <> 0){
	$location = "Location: manage_tasks.php?e=3&s=" . $schedule_id ;
}else{
	$location = "Location: manage_tasks.php?e=1&s=" . $schedule_id;
}

header($location) ;
