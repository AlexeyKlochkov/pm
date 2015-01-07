<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";


$project_id = $_POST["project_id"];
$schedule_id = $_POST["schedule_id"];

//get all tasks for this schedule
//as you go through the tasks, get the top assignee from that project
//see if they are already assigned to the task. If not, assign them.

$arr_schedule_tasks = get_schedule_tasks($schedule_id);
//print_r($arr_schedule_tasks);
if (!empty($arr_schedule_tasks)){
	foreach ($arr_schedule_tasks as $schedule_task_row){
		$add_user = 0;
		$schedule_task_id  = $schedule_task_row["schedule_task_id"];
		//print "STID: " . $schedule_task_id . "<br>";
		$default_role_id = $schedule_task_row["role_id"];
		$user_for_current_role = get_user_by_project_and_role($project_id, $default_role_id);
		$is_approval_task = $schedule_task_row["is_approval"];
		//print "User for role: "  . $user_for_current_role . "<br>";
		//check if that user is already assigned to that task
		if(!empty($user_for_current_role)){
			$check_if_assigned = check_schedule_task_assignee($schedule_task_id, $user_for_current_role);
			//print $check_if_assigned;
			if ($check_if_assigned == 0){
				//user was not found, insert this user.
				//print $schedule_task_id;
				if($is_approval_task ==1){
					//get rid of everyone
					delete_schedule_task_assignees($schedule_task_id);
				}
				
				insert_schedule_task_assignee($schedule_task_id, $user_for_current_role);
			}
		}
	}
}

//$active_success = activate_asset_type($asset_type_id, $active);
$active_success = 1;
if ($active_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: manage_tasks.php?s=" . $schedule_id . "&p=" . $project_id;


header($location) ;
