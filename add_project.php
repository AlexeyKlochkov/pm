<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$campaign_id = $_POST["campaign_id"];
$project_name = $_POST["project_name"];
$product_id = $_POST["product_id"];
$audience_id = $_POST["audience_id"];
$project_manager_id = $_POST["project_manager_id"];
$acd_id = $_POST["acd_id"];
$project_summary = $_POST["project_summary"];
$project_status_id = $_POST["project_status_id"];
$start_date = $_POST["start_date"];
$end_date = $_POST["end_date"];
$cost_center = $_POST["cost_center"];
$media_budget = $_POST["media_budget"];
$production_budget = $_POST["production_budget"];
$approved_aop_activity = "";
$compliance_project = "";
$upload_to_aps = $_POST["upload_to_aps"];
$user_id = $_POST["user_id"];
$business_unit_owner_id = $_POST["business_unit_owner_id"];
$project_requester = $_POST["project_requester"];
$aop_activity_type_id = $_POST["aop_activity_type_id"];

$arr_business_code = get_business_code_by_campaign($campaign_id);
$business_code = $arr_business_code[0]["business_unit_abbrev"];
$error = 0;
$start_date = convert_datepicker_date($start_date);
$end_date = convert_datepicker_date($end_date);

$new_project_id  = insert_project($campaign_id, $project_name, $product_id, $audience_id, $project_manager_id, $project_summary, $project_status_id, $start_date, $end_date, $cost_center, $media_budget, $production_budget, $approved_aop_activity, $upload_to_aps, $user_id, $business_unit_owner_id, $project_requester, $compliance_project, $aop_activity_type_id, $acd_id );

$project_code = $business_code . "-" . $new_project_id;
$insert_project_success = insert_project_code($new_project_id, $project_code);

//insert three phases with each project
insert_project_phase($new_project_id, 10, 1 );
insert_project_phase($new_project_id, 11, 2 );
insert_project_phase($new_project_id, 7, 3 );

//add project manager to project
add_project_person($new_project_id, $project_manager_id);

//add business_unit_owner if there is one
if(!empty($business_unit_owner_id)){
	add_project_person($new_project_id, $business_unit_owner_id);
}

if(!empty($acd_id)){
	add_project_person($new_project_id, $acd_id);
}

if ($error == 0){
	$location = "Location: manage_project.php?p=" . $new_project_id ;
}else{
	$location = "Location: add_project.php?e=" . $error;
}

header($location) ;
