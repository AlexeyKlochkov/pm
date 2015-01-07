<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$company_id = $_POST["company_id"];
$schedule_task_id = $_POST["schedule_task_id"];
$schedule_id = $_POST["schedule_id"];
$user_id = $_POST["user_id"];
$complete = $_POST["complete"];
$notes = $_POST["notes"];
$hours = $_POST["hours"];
$minutes = $_POST["minutes"];
$day_worked =  convert_datepicker_date($_POST["day"]);
$time_worked = $hours . ":" . $minutes . ":00";

$update_success = insert_schedule_task_time($schedule_task_id, $user_id, $time_worked, $day_worked, $notes, $user_id);

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

if ($update_success == 0){
	$location = "Location: fast_track_complete.php?e=1&stid=" . $schedule_task_id;
}else{
	$location = "Location: fast_track_complete.php?e=2&stid=" . $schedule_task_id;
}

header($location) ;
