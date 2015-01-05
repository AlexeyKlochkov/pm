<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$schedule_template_id = $_POST["schedule_template_id"];
$schedule_template_tasks_id = $_POST["schedule_template_tasks_id"];
$task_id = $_POST["task_id"];
$manager_role_id = $_POST["manager_role_id"];
$assignee_role_id = $_POST["assignee_role_id"];
$start_day = $_POST["start_day"];
$end_day = $_POST["end_day"];
$total_hours = $_POST["hours"];
$total_minutes = $_POST["minutes"];
$predecessor = $_POST["predecessor"];
$total_time = $total_hours . ":" . $total_minutes . ":00";

$update_success = update_schedule_template_task($schedule_template_tasks_id, $task_id, $manager_role_id, $assignee_role_id, $start_day, $end_day, $total_time, $predecessor);

//print $audit_id;

if ($update_success <> 0){
	$location = "Location: edit_schedule_template_task.php?e=2&stid=" . $schedule_template_id . "&sttid=" . $schedule_template_tasks_id;
}else{
	$location = "Location: edit_schedule_template_task.php?e=1&stid=" . $schedule_template_id . "&sttid=" . $schedule_template_tasks_id;
}

header($location) ;


?>