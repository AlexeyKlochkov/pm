<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";


$schedule_id = $_POST["schedule_id"];
$project_id = $_POST["project_id"];
$days_to_shift = $_POST["days"];

foreach ( $_POST as $key => $value )
{
    if ( preg_match('/shift/', $key) )
    {
        //print $key . "-" . $value;
		$arr_stid = explode("-", $key);
		$schedule_task_id = $arr_stid[1];
		$current_start_date = $_POST["sd-" . $schedule_task_id ];
		$current_end_date = $_POST["ed-" . $schedule_task_id ];
		$new_start_date = get_date_no_weekends($current_start_date, $days_to_shift);
		$new_end_date =  get_date_no_weekends($current_end_date, $days_to_shift);
		$update_success = update_start_and_end_dates($schedule_task_id, $new_start_date, $new_end_date);
		//$current_value = $value;
        
   }
}

if ($update_success <> 0){
	
	$location = "Location: shift_schedule_tasks.php?e=2&p=" . $project_id . "&s=" . $schedule_id;
}else{
	$location = "Location: shift_schedule_tasks.php?e=1&p=" . $project_id . "&s=" . $schedule_id;
}

header($location) ;


?>