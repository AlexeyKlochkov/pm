<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
error_reporting(E_ALL);

$pif_id = $_POST["pif_id"];
$pif_status = $_POST["s"];
$approver_notes = $_POST["approver_notes"];
$approver_id = $_POST["approved_by"];
$update_success = update_pif_status($pif_id, $pif_status);
$status_name = get_status_name($pif_status);
$update_log_success = insert_pif_log($pif_id, "Status changed to " . $status_name, $approver_notes, $approver_id);
$requester_id = $_POST["requester_id"];
$arr_requester =  get_user_info($requester_id);
$requester_name = $arr_requester[0]["first_name"] . " " . $arr_requester[0]["last_name"];
$requester_email = $arr_requester[0]["email"];
$pif_code = $_POST["pif_code"];
$marketing_owner_id = $_POST["marketing_owner_id"];
$new_project_id = 0;
$project_name = $_POST["project_name"];
if (isset($_POST["project_description"])) {
	$project_description = $_POST["project_description"];
}else $project_description="";
if (isset($_POST["uopx_benefit"])) {
	$uopx_benefit = $_POST["uopx_benefit"];
}else $uopx_benefit="";
if (isset($_POST["uopx_risk"])) {
	$uopx_risk = $_POST["uopx_risk"];
}else $uopx_risk="";
if (isset($_POST["project_objective"])) {
	$project_objective = $_POST["project_objective"];
}else $project_objective="";
if (isset($_POST["background"])) {
	$background = $_POST["background"];
}else $background="";
if (isset($_POST["audience"])) {
	$audience = $_POST["audience"];
}else $audience="";
if (isset($_POST["objectives"])) {
	$objectives = $_POST["objectives"];
}else $objectives="";
if (isset($_POST["core_message"])) {
	$core_message = $_POST["core_message"];
}else $core_message="";
if (isset($_POST["support_points"])) {
	$support_points = $_POST["support_points"];
}else $support_points="";
if (isset($_POST["required_elem"])) {
	$required_elem = $_POST["required_elem"];
}else $required_elem="";
if (isset($_POST["aop_activity_type_id"])) {
	$aop_activity_type_id = $_POST["aop_activity_type_id"];
}else $aop_activity_type_id="";

if ($pif_status == 3){
	//if the project is approved, create a project
	$project_manager_id = $_POST["project_manager_id"];
	$arr_pm =  get_user_info($project_manager_id);
	$pm_name = $arr_pm[0]["first_name"] . " " . $arr_pm[0]["last_name"];
	$pm_email = $arr_pm[0]["email"] ;
	$project_name = $_POST["project_name"];
	$line_of_business_id = $_POST["line_of_business_id"];
	$product_id = $_POST["product_id"];
	$project_description = $_POST["project_description"];
	$cost_code = $_POST["cost_code"];
	$request_date = $_POST["request_date"];
	$desired_delivery_date = $_POST["desired_delivery_date"];
	$aop_activity_type_id = $_POST["aop_activity_type_id"];
	$campaign_id = $_POST["campaign_id"];
	$production_budget = $_POST["production_budget"];
	$business_unit_owner_id = $_POST["business_unit_owner_id"];
	$acd_id = $_POST["acd_id"];
	$project_status_id = 1;
	$approved_aop_activity = 0;
	if ($aop_activity_type_id == 1){
		$approved_aop_activity = 1;
	}

	$new_project_id = insert_project($campaign_id, $project_name, $product_id, 0, $project_manager_id, $project_description, $project_status_id, $request_date, $desired_delivery_date, $cost_code, 0, $production_budget, $approved_aop_activity, 0, $user_id, $business_unit_owner_id, $requester_name, 0, $aop_activity_type_id, $acd_id);
	$pif_files=get_pif_file_by_id($pif_id);
	$business_code = get_business_unit_abbrev($line_of_business_id);
	//insert the project code
	$project_code = $business_code . "-" . $new_project_id;
	$insert_project_code_success = insert_project_code($new_project_id, $project_code);
	if (!empty($pif_files)){
		foreach($pif_files as $file){
			$pf=insert_project_file($new_project_id,$file["pif_file_name"],"","PIF","","");
			$dir = "project_files/" . $project_code . "/";
			if (!file_exists($dir)) {
				mkdir($dir);
			}
			copy("pif_files/".$pif_code."/".$file["pif_file_name"],$dir.$file["pif_file_name"]);

		}
	}
	$update_pif_approval_date_success = update_pif_approval_date($pif_id);
	////add the project assets
	$arr_pif_assets = get_pif_assets($pif_id);
	
	if (!empty($arr_pif_assets)){
		foreach ($arr_pif_assets as $pif_asset_row){
			$pif_asset_id = $pif_asset_row["pif_asset_id"];
			$asset_quantity = $pif_asset_row["asset_quantity"];
			$asset_type_id = $pif_asset_row["asset_type_id"];
			$insert_asset_success = add_asset($new_project_id, $asset_type_id, "", 0, 0, $asset_quantity, "", convert_datepicker_date($request_date), convert_datepicker_date($desired_delivery_date), $user_id);
		}
	}
	
	$arr_users_to_add = array($project_manager_id);
	if($project_manager_id <> $business_unit_owner_id){
		array_push($arr_users_to_add, $business_unit_owner_id);
	}
	
	if(!empty($acd_id)){
		if(!in_array($acd_id, $arr_users_to_add)){
			array_push($arr_users_to_add, $acd_id);
		}
	}
	////add the project people. these are coming in as checkboxes starting with "u-"
	foreach($_POST as $key=>$value){
		$variable_name = $key;
		$variable_value = $value;
		$first_two_characters = substr($variable_name, 0, 2);
		if ($first_two_characters == "u-"){
			$arr_user = explode("-", $variable_name);
			$project_person_id = $arr_user[1];
			if(!in_array($project_person_id, $arr_users_to_add)){
				$project_person_id = intval($project_person_id);
				array_push($arr_users_to_add, $project_person_id);
			}
		}
	}
	foreach ($arr_users_to_add as $user_to_add_id){
		$add_person_success = add_project_person($new_project_id, $user_to_add_id);
	}
	//update the log
	$update_log_success2 = insert_pif_log($pif_id, "Project " . $project_code . " created for " . $pif_code, "", $approver_id);
	//set the project_id in the PIF table to link these --> may be better to store the PIF ID in the project table...
	$update_success3 = update_pif_project_id($pif_id, $new_project_id);
}
////send emails if appropriate
if (!empty($_POST["send_email"])){
	//send different emails to different people depending on the status.
	
	//On hold:
	if ($pif_status == 6){
		//status has been changed to on-hold. Send on-hold email to requester and/or brand manager
		if (!empty($_POST["send_requester"])){
			$send_success = send_on_hold_email_with_link($pif_id, $pif_code, $approver_notes, $requester_email, $requester_name, $approver_id, $project_name, $requester_email);
		}
		if (!empty($_POST["send_bm"])){
			$arr_bm_user =  get_user_info($marketing_owner_id);
			$bm_full_name = $arr_bm_user[0]["first_name"] . " " .  $arr_bm_user[0]["last_name"];
			$bm_email = $arr_bm_user[0]["email"];
			$send_success = send_on_hold_email_no_link($pif_id, $pif_code, $approver_notes, $bm_email, $bm_full_name, $approver_id, $project_name, $requester_email);
		}
	}elseif ($pif_status == 3){
		//status has been changed to on-hold. Send on-hold email
		if (!empty($_POST["send_requester"])){
			$bm_email = "";
			//CC the brand manager if there is one
			if (!empty($_POST["send_bm"])){
				$bm_email =  get_user_email($marketing_owner_id);
			}
			$send_success = send_approved_email_to_requester($pif_id, $pif_code, $approver_notes, $requester_email, $requester_name, $approver_id, $bm_email, $pm_name, $project_name, $requester_email);
		}
		if (!empty($_POST["send_pm"])){
			$send_success2 = send_approved_email_to_pm($pif_id, $pif_code, $approver_notes, $pm_email, $requester_name, $approver_id, $pm_name, $project_code, $new_project_id, $project_name);
		}
	}else{
		if (!empty($_POST["send_requester"])){
			$bm_email = "";
			//CC the brand manager if there is one
			if (!empty($_POST["send_bm"])){
				$bm_email =  get_user_email($marketing_owner_id);
			}
			$send_success = send_other_pif_status_email($pif_id, $pif_code, $approver_notes, $requester_email, $requester_name, $approver_id, $bm_email, $status_name, $project_name, $requester_email);
		}
	}
}
$pif_files=get_pif_file_by_id($pif_id);
if ($update_success == 0){
	$location = "Location: pif_list.php?e=1";
}else{
	$location = "Location: pif_list.php?e=2";
	if ($new_project_id <> 0){
		$location = "Location: pif_list.php?e=3";
	}
}

header($location) ;
