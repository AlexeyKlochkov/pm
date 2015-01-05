<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$schedule_id = $_POST["schedule_id"];
$task_id = $_POST["task_id"];
$start_date = $_POST["start_date"];
$end_date = $_POST["end_date"];
$hours = $_POST["hours"];
$minutes = $_POST["minutes"];
$task_manager = $_POST["task_manager_id"];
$progress = $_POST["progress"];
$predecessor = $_POST["predecessor"];
$assignee1 = $_POST["assignee1"];
$assignee2 = $_POST["assignee2"];

$max_display_num = get_max_display_order_schedule_task($schedule_id);

//$display_order = $max_display_order + 1;

$total_days = get_total_days_minus_weekends($start_date, $end_date);

$daily_percentage = get_daily_percentage($total_days, $hours, $minutes);

//done with calculations, convert these for MySQL
$start_date = convert_datepicker_date($start_date);

$end_date = convert_datepicker_date($end_date);

if(empty($hours)){$hours = "0";}
if(empty($minutes)){$minutes = "0";}

$estimated_hours = $hours . ":" . $minutes . ":00";

//figure out where the task is going.
$num_tasks = $max_display_num;
$task_location = $_POST["task_location"];
$after_task = "";
if (!empty($_POST["after_task"])){
	$after_task = $_POST["after_task"];
}

if ($task_location == "end"){
	$display_num = $max_display_num + 1;
	//no need to move anything.
}
if ($task_location == "beginning"){
	$display_num = 1;
	$move_success = move_schedule_tasks($schedule_id, 1, 1);
	//start with #2
}

if ($task_location == "after_task"){
	$move_start = $after_task + 1;
	$display_num = $after_task + 1;
	$move_success = move_schedule_tasks($schedule_id, $move_start, 1);
}

$new_schedule_task_id = insert_schedule_task($schedule_id, $task_id, $start_date, $end_date, $estimated_hours, $task_manager, $progress, $display_num, $daily_percentage, $user_id, $predecessor);
$insert_success1 = insert_schedule_task_assignee($new_schedule_task_id, $assignee1);

if(!empty($assignee2)){
	$insert_success2 = insert_schedule_task_assignee($new_schedule_task_id, $assignee2);
}


if ($new_schedule_task_id <> 0){
	$location = "Location: manage_tasks.php?s=" . $schedule_id;
}else{
	$location = "Location: manage_tasks.php?e=1&s=" . $schedule_id;
}

header($location) ;
