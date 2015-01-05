<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$project_id = $_POST["project_id"];
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
$active = $_POST["active"];
$business_unit_owner_id = $_POST["business_unit_owner_id"];
$project_requester = $_POST["project_requester"];
$campaign_id = $_POST["campaign_id"];
$aop_activity_type_id = $_POST["aop_activity_type_id"];
//print $business_code;
$error = 0;

//print $start_date . "--";
if(!empty($start_date)){
	$start_date = convert_datepicker_date($start_date);
}
//print $start_date;
if(!empty($end_date)){
	$end_date = convert_datepicker_date($end_date);
}
//print $cost_center;

$update_project_success  = update_project($project_id, $project_name, $product_id, $audience_id, $project_manager_id, $project_summary, $project_status_id,$start_date, $end_date, $cost_center, $media_budget, $production_budget, $approved_aop_activity, $upload_to_aps, $user_id, $active, $business_unit_owner_id, $project_requester, $compliance_project, $campaign_id, $aop_activity_type_id, $acd_id );

//print $insert_project_success;
//print $audit_id;

if ($update_project_success == 1){
	$location = "Location: edit_project.php?e=1&p=" . $project_id ;
}else{
	$location = "Location: edit_project.php?e=2&p=" . $project_id;
}

header($location) ;


?>