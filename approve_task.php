<?php
include "functions/dbconn.php";
include "functions/queries.php";
include "loggedin.php";
$company_id = $_POST["company_id"];
$schedule_task_id = $_POST["schedule_task_id"];
$user_id = $_POST["user_id"];
$is_approved = $_POST["is_approved"];
$approval_notes = $_POST["approval_notes"];
$approval_file_id = $_POST["approval_file_id"];
$page =  $_POST["page"];
$update_success = update_approval($schedule_task_id, $user_id, $is_approved, $approval_notes);

$arr_user_info = get_user_info($user_id);
$user_initials = $arr_user_info[0]["initials"];
$user_first_name = $arr_user_info[0]["first_name"];
$user_last_name = $arr_user_info[0]["last_name"];


if($is_approved == 1){
	$approval_string = "was approved";
}else{
	$approval_string = "was NOT approved";
}

$event_string = "Task " . $approval_string  . " by " . $user_first_name . " " . $user_last_name;
if(!empty($approval_file_id)){
	$event_string = "File " . $approval_string . " by " . $user_first_name . " " . $user_last_name;
}

$insert_success = insert_approval_log($schedule_task_id, $user_id, $event_string, $approval_notes, $approval_file_id);

if ($update_success <> 1){
	$location = "Location: " . $page . ".php?e=1&stid=" . $schedule_task_id;
}else{
	$location = "Location: " . $page . ".php?e=2&stid=" . $schedule_task_id;
}


header($location) ;
