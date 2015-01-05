<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";


$schedule_template_id = $_POST["schedule_template_id"];
$task_id = $_POST["task_id"];
$manager_role_id = $_POST["manager_role_id"];
$assignee_role_id = $_POST["assignee_role_id"];
$start_day = $_POST["start_day"];
$end_day = $_POST["end_day"];
$total_hours = $_POST["hours"];
$total_minutes = $_POST["minutes"];
$predecessor = $_POST["predecessor"];
$total_time = $total_hours . ":" . $total_minutes . ":00";

$error = 0;
//figure out the phase order.
$max_display_order = get_max_schedule_template_task_display_order($schedule_template_id);
$display_order = $max_display_order + 1;
$new_schedule_template_tasks_id  = insert_schedule_template_task($schedule_template_id, $task_id, $manager_role_id, $assignee_role_id, $start_day, $end_day, $total_time, $display_order, $predecessor);
if ($new_schedule_template_tasks_id <> 0){
	$location = "Location: edit_schedule_template.php?stid=" . $schedule_template_id;
}else{
	$location = "Location: edit_schedule_template.php?e=1&stid=" . $schedule_template_id;
}

header($location) ;
