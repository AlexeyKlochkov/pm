<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
set_time_limit ( 300 );
$schedule_id = $_POST["schedule_id"];
$project_id = $_POST["project_id"];
$schedule_template_id = $_POST["schedule_template_id"];
$task_location = $_POST["task_location"];
$after_task = "";
if (!empty($_POST["after_task"])){
	$after_task = $_POST["after_task"];
}
$start_date = $_POST["start_date"];
$user_id = $_POST["user_id"];
//print $schedule_id . "-" . $schedule_template_id . "-" . $task_location;

$max_display_num = get_max_display_order_schedule_task($schedule_id);

$arr_schedule_template_tasks = get_schedule_template_tasks($schedule_template_id);
$count_schedule_template_tasks = 0;
if (!empty($arr_schedule_template_tasks)){
	$count_schedule_template_tasks = count($arr_schedule_template_tasks);
}


if ($task_location == "end"){
	$begin_display_num = $max_display_num + 1;
}

if ($task_location == "beginning"){
	$begin_display_num = 1;
	$move_success = move_schedule_tasks($schedule_id, $begin_display_num, $count_schedule_template_tasks);
}

if ($task_location == "after_task"){
	$begin_display_num = $after_task + 1;
	$move_success = move_schedule_tasks($schedule_id, $begin_display_num, $count_schedule_template_tasks);
}

$display_order_num = $begin_display_num;

//print $count_schedule_template_tasks;

if (!empty($arr_schedule_template_tasks)){
	foreach ($arr_schedule_template_tasks as $task_row){
		//$schedule_template_task_id = $task_row["schedule_template_task_id"];
		$task_id = $task_row["task_id"];
		$manager_role_id = $task_row["manager_role_id"];
		$assignee_role_id = $task_row["assignee_role_id"];
		$start_day = $task_row["start_day"];
		$end_day = $task_row["end_day"];
		$total_time = $task_row["total_time"];
		$predecessor = $task_row["predecessor"];
		$schedule_template_task_display_order = $task_row["display_order"];
		//attempt to find managers and assignees based on role type.
		$task_manager_id = get_user_by_project_and_role($project_id, $manager_role_id);
		$assignee_id = get_user_by_project_and_role($project_id, $assignee_role_id);
		//get the start and end dates based on the number of days and the start date
		$task_start_date = get_date_no_weekends($start_date, $start_day);
		$task_end_date = get_date_no_weekends($start_date, $end_day);
		//calculate the daily hours
		$total_days = get_total_days_minus_weekends($task_start_date, $task_end_date);
		//this should equal start_day minus end_day
		list($hours, $minutes, $seconds) = explode(":", $total_time);
		$daily_percentage = get_daily_percentage($total_days, $hours, $minutes);
		$new_schedule_task_id = insert_schedule_task($schedule_id, $task_id, $task_start_date, $task_end_date, $total_time, $task_manager_id, 0, $display_order_num, $daily_percentage, $user_id, $predecessor);
		if (!empty($assignee_id )){
			$insert_success1 = insert_schedule_task_assignee($new_schedule_task_id, $assignee_id);
		}
		$display_order_num ++;
		
	}
}
if ($new_schedule_task_id <> 0){
	$location = "Location: manage_tasks.php?e=2&s=" . $schedule_id;
}else{
	$location = "Location: manage_tasks.php?e=1&s=" . $schedule_id;
}

header($location) ;


?>