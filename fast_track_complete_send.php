<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
$error = 0;

$project_id = $_GET["p"];
$schedule_id = $_GET["s"];
$schedule_task_id = $_GET["stid"];

//clear current task if any
$success = clear_and_complete_current_task($schedule_task_id);

$min_incomplete_schedule_task_id = get_min_incomplete_task($schedule_id);
	
//update the min task unless it equals zero
if ($min_incomplete_schedule_task_id == 0){
	//no need to set the next task, there isn't one. 
	//Send the project manager an email saying the project is complete
	$send_success = send_pm_schedule_complete_email($schedule_id);
	$update_success = toggle_fasttrack($schedule_id, 2);
}else{
	$update_schedule_task_success = set_current_task($min_incomplete_schedule_task_id);
	//send emails - just pass schedule_task_id to the function
	$send_success = send_next_task_email($min_incomplete_schedule_task_id);
}

$location = "Location: manage_project.php?p=" . $project_id . "&show_schedules=1#schedules";

header($location) ;

?>