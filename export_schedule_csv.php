<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/functions.php";
include "functions/queries.php";
$schedule_id = $_GET["s"];

$vendor_other_id = get_vendor_other_id($company_id);

$arr_schedule = array();
$arr_headers = array("Order", "Task", "Task Manager", "Start Date", "End Date", "Total Time", "Predecessor", "Progress", "Assignee(s)", "Default Role","Approval Task?", "Complete?");

array_push($arr_schedule, $arr_headers);
$arr_schedule_tasks = get_schedule_tasks($schedule_id);

if (!empty($arr_schedule_tasks)){
	foreach ($arr_schedule_tasks as $schedule_row){
			$schedule_task_id = $schedule_row["schedule_task_id"];
			$display_order = $schedule_row["display_order"];
			$task_id = $schedule_row["task_id"];
			$task_name = $schedule_row["task_name"];
			$manager_name = $schedule_row["initials"];
			$manager_id = $schedule_row["user_id"];
			$start_date = translate_mysql_todatepicker($schedule_row["start_date"]);
			$end_date = translate_mysql_todatepicker($schedule_row["end_date"]);
			$estimated_hours = $schedule_row["estimated_hours"];
			$is_approval = $schedule_row["is_approval"];
			if($is_approval == 1){
				$is_approval = "yes";
			}else{
				$is_approval = "no";
			}
			
			$complete= $schedule_row["complete"];
			if($complete == 1){
				$complete = "yes";
			}else{
				$complete = "no";
			}
			$predecessor = $schedule_row["predecessor"];
			$default_role_id = $schedule_row["role_id"];
			$default_role_name = $schedule_row["role_name"];
			$assignee_list = get_assignee_initials($schedule_task_id);
			$progress = $schedule_row["progress"] . "%";
			
		$arr_current_variables = array($display_order , $task_name, $manager_name, $start_date, $end_date, $estimated_hours, $predecessor, $progress, $assignee_list, $default_role_name, $is_approval, $complete );
		array_push($arr_schedule, $arr_current_variables);
	}
}
download_send_headers("Schedule_Report_S" . $schedule_id . "_" . date("Y-m-d") . ".csv");
echo array2csv2($arr_schedule);
die();
