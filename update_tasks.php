<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
set_time_limit ( 300 );

$schedule_id = $_POST["schedule_id"];
$incoming_array = $_POST["schedule_task_id_list"];

$arr_schedule_task_id = explode("-", $incoming_array);


foreach ( $arr_schedule_task_id as $current_schedule_task_id )
{
	$task_id = $_POST[$current_schedule_task_id . "-task_id"];
	$task_manager_id = $_POST[$current_schedule_task_id . "-task_manager_id"];
	$start_date = $_POST[$current_schedule_task_id . "-start_date"];
	$end_date = $_POST[$current_schedule_task_id . "-end_date"];
	$hours = $_POST[$current_schedule_task_id . "-hours"];
	$minutes = $_POST[$current_schedule_task_id . "-minutes"];
	$progress = $_POST[$current_schedule_task_id . "-progress"];
	$predecessor = $_POST[$current_schedule_task_id . "-predecessor"];
	
	if (!empty( $_POST[$current_schedule_task_id . "-complete"])){
		$complete = $_POST[$current_schedule_task_id . "-complete"];
		$progress = 100;
	}else{
		$complete = 0;
	}

	if(empty($hours)){$hours = "0";}
	if(empty($minutes)){$minutes = "0";}
	$estimated_hours = $hours . ":" . $minutes . ":00";
	$total_days = get_total_days_minus_weekends($start_date, $end_date);
	$daily_hours = get_daily_percentage($total_days, $hours, $minutes);
	$start_date = convert_datepicker_date($start_date);
	$end_date = convert_datepicker_date($end_date);
	$update_success = update_schedule_task($current_schedule_task_id, $task_id, $task_manager_id, $start_date, $end_date, $progress, $estimated_hours, $daily_hours, $complete, $predecessor);
}

//handle deletes
foreach($_POST as $key=>$value)
{
	$variable_name = $key;
	$variable_value = $value;
	$first_three_characters = substr($variable_name, 0, 3);
	if ($first_three_characters == "del"){
		
		$arr_delete = explode("-", $variable_name);
		$schedule_task_id_to_delete = $arr_delete[1];
		//display order changes on multiple deletes so it's safest to grab it this way.
		$schedule_task_display_order = get_schedule_task_display_order($schedule_task_id_to_delete);
		//delete schedule task
		$delete_success = delete_schedule_task($schedule_task_id_to_delete);
		//shift order
		$update_success = move_schedule_tasks($schedule_id, $schedule_task_display_order, -1);
	}
}

if ($update_success <> 0){
	$location = "Location: manage_tasks.php?e=2&s=" . $schedule_id;
}else{
	$location = "Location: manage_tasks.php?e=1&s=" . $schedule_id;
}
header($location) ;
