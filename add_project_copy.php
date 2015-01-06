<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
set_time_limit(300);
$project_to_copy_id = $_POST["project_id"];
$project_name = $_POST["project_name"];
$arr_project_info = get_project_info($project_to_copy_id);
$campaign_code = $arr_project_info[0]["campaign_code"];
$campaign_id = $arr_project_info[0]["campaign_id"];
$campaign_description = $arr_project_info[0]["campaign_description"];
$project_code = $arr_project_info[0]["project_code"];
$arr_business_code = explode("-",$project_code);
$business_code = $arr_business_code[0];
//$project_name = $arr_project_info[0]["project_name"];
$product_id = $arr_project_info[0]["product_id"];
$audience_id = $arr_project_info[0]["audience_id"];
$project_manager_id = $arr_project_info[0]["project_manager_id"];
$acd_id = $arr_project_info[0]["acd_id"];
$project_status_id = $arr_project_info[0]["project_status_id"];
$project_summary = $arr_project_info[0]["project_summary"];
$cost_center = $arr_project_info[0]["cost_center"];
$project_budget_media = $arr_project_info[0]["media_budget"];
$project_budget_production = $arr_project_info[0]["production_budget"];
$start_date = $arr_project_info[0]["start_date"];
$start_date = translate_mysql_todatepicker($start_date);
$end_date = $arr_project_info[0]["end_date"];
$end_date = translate_mysql_todatepicker($end_date);
$approved_aop_activity = $arr_project_info[0]["approved_aop_activity"];
$compliance_project = $arr_project_info[0]["compliance_project"];
$upload_to_aps = $arr_project_info[0]["upload_to_aps"];
$project_active = $arr_project_info[0]["active"];
$business_unit_owner_id = $arr_project_info[0]["business_unit_owner_id"];
$project_requester = $arr_project_info[0]["project_requester"];
$aop_activity_type_id = $arr_project_info[0]["aop_activity_type_id"];

//create the new project, get the project_id
$new_project_id  = insert_project($campaign_id, $project_name, $product_id, $audience_id, $project_manager_id, $project_summary, $project_status_id, $start_date, $end_date, $cost_center, $project_budget_media, $project_budget_production, $approved_aop_activity, $upload_to_aps, $user_id, $business_unit_owner_id, $project_requester, $compliance_project, $aop_activity_type_id, $acd_id );

$project_code = $business_code . "-" . $new_project_id;
$insert_project_success = insert_project_code($new_project_id, $project_code);

////add people
$arr_project_people = get_project_people($project_to_copy_id);
if (!empty($arr_project_people)){
	foreach ($arr_project_people as $people_row){
		$project_user_id = $people_row["user_id"];
		$add_person_success = add_project_person($new_project_id, $project_user_id);
	}
}	

//add assets
$arr_assets = get_asset_info($project_to_copy_id);
$arr_new_and_old_assets = array();
$asset_count = 0;
if (!empty($arr_assets)){
	foreach ($arr_assets as $asset_row){
	$old_asset_id = $asset_row["asset_id"];
	$asset_name = $asset_row["asset_name"];
	$asset_type_id = $asset_row["asset_type_id"];
	$asset_type_name = $asset_row["asset_type_name"];
	$asset_budget_media = $asset_row["asset_budget_media"];
	$asset_budget_production = $asset_row["asset_budget_production"];
	$asset_quantity = $asset_row["asset_quantity"];
	$asset_notes = $asset_row["asset_notes"];
	$asset_start_date = $asset_row["asset_start_date"];
	$asset_end_date = $asset_row["asset_end_date"];
	
	$new_asset_id = add_asset($new_project_id, $asset_type_id, $asset_name, $asset_budget_media, $asset_budget_production, $asset_quantity, $asset_notes, $asset_start_date, $asset_end_date, $user_id);
	$arr_current_asset_ids = array($new_asset_id, $old_asset_id);
	array_push($arr_new_and_old_assets, $arr_current_asset_ids);
	}
}

//add phases
$arr_phases = get_project_phases($project_to_copy_id);
if (!empty($arr_phases)){
	foreach ($arr_phases as $phase_row){
		$phase_id = $phase_row["phase_id"];
		$display_order = $phase_row["display_order"];
		$insert_phase_success = insert_project_phase($new_project_id, $phase_id, $display_order );
	}
}
//add schedules
//1. grab each schedule, put it in a multi dimensional array
$arr_new_and_old_schedules = array();
$arr_project_schedules = get_schedules_for_project($project_to_copy_id);
if (!empty($arr_project_schedules)){
	foreach ($arr_project_schedules as $schedule_row){
		$old_schedule_id = $schedule_row["schedule_id"];
		$schedule_asset_id = $schedule_row["asset_id"];
		$schedule_name = $schedule_row["schedule_name"];
		$schedule_description = $schedule_row["schedule_description"];
		$phase_id = $schedule_row["phase_id"];
		$schedule_phase_order = $schedule_row["schedule_phase_order"];
		//find old asset_id and use the new asset_id
		$new_schedule_asset_id = "";
		if(!empty($schedule_asset_id)){
			foreach ($arr_new_and_old_assets as $asset_row){
				if($asset_row[1] == $schedule_asset_id){
					$new_schedule_asset_id = $asset_row[0];
				}
			}
		}
		
		$new_schedule_id =  insert_schedule($new_project_id, $schedule_name, $phase_id, $new_schedule_asset_id, $schedule_description, $schedule_phase_order, $user_id);
		$arr_current_schedule_ids = array($new_schedule_id, $old_schedule_id);
		array_push($arr_new_and_old_schedules, $arr_current_schedule_ids);
	}
}

//2 copy tasks and assignees
//go through schedule array, query the old schedules and add the new ones
if (!empty($arr_new_and_old_schedules)){
	foreach ($arr_new_and_old_schedules as $new_and_old_schedule_row){
		$new_schedule_id = $new_and_old_schedule_row[0];
		$old_schedule_id = $new_and_old_schedule_row[1];
		//print $new_schedule_id . "--" . $old_schedule_id;
		//grab the tasks for this old schedule
		$arr_schedule_tasks = get_schedule_tasks_only($old_schedule_id);
		if (!empty($arr_schedule_tasks)){
			foreach ($arr_schedule_tasks as $schedule_task_row){
				$old_schedule_task_id = $schedule_task_row["schedule_task_id"];
				$task_id = $schedule_task_row["task_id"];
				$progress = 0;
				$task_id = $schedule_task_row["task_id"];
				$start_date = $schedule_task_row["start_date"];
				$end_date = $schedule_task_row["end_date"];
				$estimated_hours = $schedule_task_row["estimated_hours"];
				$task_manager_id = $schedule_task_row["task_manager_id"];
				$progress = $schedule_task_row["progress"];
				$display_order = $schedule_task_row["display_order"];
				$daily_hours = $schedule_task_row["daily_hours"];
				$predecessor = $schedule_task_row["predecessor"];
				$new_schedule_task_id =  insert_schedule_task($new_schedule_id, $task_id, $start_date, $end_date, $estimated_hours, $task_manager_id, $progress, $display_order, $daily_hours, $user_id, $predecessor);
				
				//add schedule_task_assgnees now that you have new and old schedule_task_ids
				$arr_schedule_task_assignees = get_all_assignees_by_stid($old_schedule_task_id);
				if (!empty($arr_schedule_task_assignees)){
					foreach ($arr_schedule_task_assignees as $schedule_task_assignee_row){
						$assignee_user_id = $schedule_task_assignee_row["user_id"];
						$insert_success = insert_schedule_task_assignee($new_schedule_task_id, $assignee_user_id);
						//print $assignee_user_id . "--" . $insert_success . "<br>";
					}
				}
			}
		}
	}
}

$error = 0;
if ($error == 0){
	$location = "Location: manage_project.php?p=" . $new_project_id ;
}else{
	$location = "Location: copy_project.php?p=" . $project_to_copy_id . "&e=" . $error;
}

header($location) ;
