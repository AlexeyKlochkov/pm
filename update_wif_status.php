<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
error_reporting(E_ALL);

$wif_id = $_POST["wif_id"];
$wif_code = $_POST["wif_code"]; // from hidden field
$wif_status_id = $_POST["wif_status_id"];
$product_id = $_POST["product_id"];
$campaign_id = $_POST["campaign_id"];
$project_manager_id = $_POST["project_manager_id"];
$requester_email = $_POST["requester_email"]; // from hidden field
$requester_name = $_POST["requester_name"]; // from hidden field
$wif_name = $_POST["wif_name"]; // from hidden field
$update_success = update_wif_status($wif_id, $wif_status_id);

$new_project_id = 0;
if ($wif_status_id == 2){
	//status of 2 means it's approved to become a project.
	$arr_wif_info = get_wif_info($wif_id);
	$wif_name = $arr_wif_info[0]["wif_name"];
	$requester_name = $arr_wif_info[0]["requester_name"];
	$requester_email = $arr_wif_info[0]["requester_email"];
	$desired_delivery_date = $arr_wif_info[0]["desired_delivery_date"];
	$request_date = $arr_wif_info[0]["request_date"];
	$description = $arr_wif_info[0]["description"];
	$project_summary = $description;
	$wif_code = $arr_wif_info[0]["wif_code"];
	$asset_type_id = $arr_wif_info[0]["asset_type_id"];
	//print_r($arr_wif_info);
	
	//add the project, set the status to 4, deploy
	$new_project_id = insert_project($campaign_id, $wif_name, $product_id, 0, $project_manager_id, $project_summary, 4, $request_date, $desired_delivery_date, 0, 0, 0, 0, 0, $user_id, 0, $requester_name, 0, 2, 0 );
	$business_unit_abbrev = get_business_unit_abbrev_from_campaign_id($campaign_id);
	$project_code = $business_unit_abbrev . "-" . $new_project_id;
	$insert_code_success = insert_project_code($new_project_id, $project_code);
	$insert_project_id_success = update_wif_project_id($wif_id, $new_project_id);
	//// send an email to the requester showing the WIF info with no modify link
	
	
	
	//add PM to project
	$add_user_success = add_project_person($new_project_id, $project_manager_id);

	//// get and add schedule template 27 with the current PM as the Manager for the tasks
	$schedule_template_id = 27;

	$start_date = $today = date("m/d/Y"); //set the start date for the schedule in the standard jquery datetime format
	////create a new schedule
	//$user_id = 85; //Since nobody is logged in, setting the user_id to Jen Rose. Why not.
	$schedule_id = insert_schedule($new_project_id, "Web: Basic", 0, 0, "Web site update", 0, $user_id );
	
	////add WIF files to the new project
	$arr_wif_files = get_wif_files($wif_id);
	if (!empty($arr_wif_files)){
		mkdir("project_files/" . $project_code, 0777);
		foreach ($arr_wif_files as $wif_file_row){
			$wif_file_id = $wif_file_row["wif_file_id"];
			$wif_file_name = $wif_file_row["wif_file_name"];
			$orig_file = "wif_files/" . $wif_code . "/" . $wif_file_name;
			$new_file = "project_files/" . $project_code . "/" .  $wif_file_name;
			//Copy WIF files to CB area of project files
			$insert_file_success = insert_project_file($new_project_id, $wif_file_name, "file from " . $wif_code,"CB", 0, "");
			$copy_success = copy($orig_file, $new_file);
		}
	}

	$arr_schedule_template_tasks = get_schedule_template_tasks($schedule_template_id);
	//add the tasks for this schedule to the newly created schedule
	$display_order_num = 1;
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
			$task_manager_id = get_user_by_project_and_role($new_project_id, $manager_role_id);
			$assignee_id = get_user_by_project_and_role($new_project_id, $assignee_role_id);
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
	
	//send approved email to requester:
	$arr_pm =  get_user_info($project_manager_id);
	$pm_name = $arr_pm[0]["first_name"] . " " . $arr_pm[0]["last_name"];
	$pm_email = $arr_pm[0]["email"] ;
	$send_email_success  = send_approved_wif_email_to_requester($wif_id, $wif_code, $requester_email, $pm_name, $wif_name);
	$send_email_success2 = send_approved_wif_email_to_pm($wif_id, $wif_code, $pm_email, $requester_name, $pm_name, $project_code, $new_project_id, $project_summary);
	
	//add an asset based on the wif_type_id
	$insert_asset_success = insert_asset($new_project_id, $asset_type_id, $user_id);
	
}else{
	//Send email to requester if not approved:
	$wif_status_name = get_wif_status_name($wif_status_id);
	$send_email_success = send_wif_status_change_email($wif_id, $wif_code, $requester_email, $wif_name, $wif_status_name);
}

$update_campaign_id_success = update_wif_campaign_id($wif_id, $campaign_id);

if ($update_success == 0){
	
	$location = "Location: wif_list.php?e=1";
}else{
	$location = "Location: wif_list.php?e=2";
	if ($new_project_id <> 0){
		$location = "Location: wif_list.php?e=3&pc=" . $project_code;
	}
}

header($location) ;


?>