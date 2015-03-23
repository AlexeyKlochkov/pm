<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "pif_email_inc.php";

$project_name = $_POST["project_name"];
$marketing_owner_id = $_POST["marketing_owner_id"];
$exec_sponsor_id = $_POST["exec_sponsor_id"];
$business_unit_id = $_POST["business_unit_id"];
$product_id = $_POST["product_id"];
$request_date = $_POST["request_date"];
$desired_delivery_date = $_POST["desired_delivery_date"];
$target_in_market_date = $_POST["target_in_market_date"];
$expiration_date = $_POST["expiration_date"];
$budget = $_POST["budget"];
$cost_code = $_POST["cost_code"];
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
$estimated_total_reach = 0;
$segment_reach_potential_students = 0;
$segment_reach_current_students = 0;
$segment_reach_employee = 0;
$segment_reach_faculty = 0;
$segment_reach_alumni = 0;
$segment_reach_wfs = 0;
$segment_reach_other = 0;
$segment_quantity_potential_students = $_POST["segment_quantity_potential_students"];
$segment_quantity_potential_students = str_replace(",", "", $segment_quantity_potential_students);
$segment_quantity_current_students = $_POST["segment_quantity_current_students"];
$segment_quantity_current_students = str_replace(",", "", $segment_quantity_current_students);
$segment_quantity_employee = $_POST["segment_quantity_employee"];
$segment_quantity_employee = str_replace(",", "", $segment_quantity_employee);
$segment_quantity_faculty = $_POST["segment_quantity_faculty"];
$segment_quantity_faculty = str_replace(",", "", $segment_quantity_faculty);
$segment_quantity_alumni = $_POST["segment_quantity_alumni"];
$segment_quantity_alumni = str_replace(",", "", $segment_quantity_alumni);
$segment_quantity_other = $_POST["segment_quantity_other"];
$segment_quantity_other = str_replace(",", "", $segment_quantity_other);
$segment_quantity_wfs = 0;


if(!empty($_POST["segment_reach_potential_students"])){
	$segment_reach_potential_students = 1;
}
if(!empty($_POST["segment_reach_current_students"])){
	$segment_reach_current_students = 1;
}
if(!empty($_POST["segment_reach_employee"])){
	$segment_reach_employee = 1;
}
if(!empty($_POST["segment_reach_faculty"])){
	$segment_reach_faculty = 1;
}
if(!empty($_POST["segment_reach_alumni"])){
	$segment_reach_alumni = 1;
}
if(!empty($_POST["segment_reach_other"])){
    $segment_reach_other = 1;
}
$version = 1;
$orig_business_unit_id = "";
if(!empty($_POST["version"])){
	$version = $_POST["version"] + 1;
	$old_pif_id  = $_POST["pif_id"];
	$orig_pif_id  = $_POST["orig_pif_id"];
	$old_pif_code = $_POST["old_pif_code"];
	$orig_business_unit_id =  $_POST["orig_business_unit_id"];
}else{
	$orig_pif_id = 0;
}

if($orig_business_unit_id <> $business_unit_id){
	$version = 1;
}


$request_date = convert_datepicker_date($request_date);
$desired_delivery_date = convert_datepicker_date($desired_delivery_date);
$target_in_market_date = convert_datepicker_date($target_in_market_date);
$expiration_date = convert_datepicker_date($expiration_date);

$new_pif_id = insert_pif($project_name, $company_id, $version, $marketing_owner_id, $exec_sponsor_id, $business_unit_id, $product_id, $request_date, $desired_delivery_date,
                        $target_in_market_date, $expiration_date, $budget, $cost_code, $project_description, $uopx_benefit,
                        $uopx_risk, $project_objective, $estimated_total_reach, $segment_reach_potential_students, $segment_reach_current_students, $segment_reach_employee,
                        $segment_reach_faculty, $segment_reach_alumni, $segment_reach_wfs, $segment_reach_other, $segment_quantity_potential_students,
                        $segment_quantity_current_students, $segment_quantity_employee, $segment_quantity_faculty, $segment_quantity_alumni, $segment_quantity_wfs,
                        $segment_quantity_other,$user_id, $orig_pif_id,$background,$audience,$objectives,$core_message,$support_points,$aop_activity_type_id,$required_elem);

//insert PIF code
$business_unit_abbrev = get_business_unit_abbrev($business_unit_id);

$pif_code = "PB_" . $business_unit_abbrev . "-" . $new_pif_id . "_V" . $version;

//if the business unit has not changed, and if we are updating an existing PIF, set old PIF to old and version the new one.
if($orig_business_unit_id == $business_unit_id){
	if(!empty($_POST["version"])){
		//PIF code reflects the original PIF ID
		$pif_code = "PIF_" . $business_unit_abbrev . "-" . $orig_pif_id . "_V" . $version;
		
		//set the old PIF to old version
		$old_pif_update_success = update_pif_status($old_pif_id, 2);
		$log_update_success_old_pif = insert_pif_log($old_pif_id, $old_pif_code . " set to OLD VERSION.", "", $user_id);
	}
}else{
	if(!empty($_POST["version"])){
		//if it's a new business unit, set the old one to old
		$old_pif_update_success = update_pif_status($old_pif_id, 2);
		$log_update_success_old_pif = insert_pif_log($old_pif_id, $old_pif_code . " set to OLD VERSION.", "", $user_id);
		
	}
}
// if this is a new pif, throw the pif_id in to the orig_pif field.
if(empty($orig_pif_id)){
	$update_success = update_orig_pif($new_pif_id);
}
$update_success = update_pif_code($new_pif_id, $pif_code);


//insert PIF assets
foreach($_POST as $key=>$value)
{
	$variable_name = $key;
	$variable_value = $value;
	$first_four_characters = substr($variable_name, 0, 4);
	if ($first_four_characters == "pat-"){
		
		$arr_pat = explode("-", $variable_name);
		$pif_asset_type_id = $arr_pat[1];
		$pat_volume = $_POST["patvol-" . $pif_asset_type_id];
		$pat_volume = str_replace(",", "", $pat_volume);
		$pif_asset_id = insert_pif_asset($new_pif_id, $pif_asset_type_id, $pat_volume, "");
	}
	
}

//insert the PIF log
$log_update_success = insert_pif_log($new_pif_id, $pif_code . " created.", "", $user_id);

//send pif email
$pif_html = get_pif_email($new_pif_id);
$recipient_email = get_user_email($user_id);

$send_success = smtpmailer($recipient_email, 'Thank you for your PIF submission', $pif_html ,'');
if ($send_success == 1){
	$send_success = "sent successfully.";
}else{
	$send_success = "send failed.";
}
$enter_log_success = insert_pif_log($new_pif_id, "Email Sent to " . $recipient_email . " upon initial PIF submission. Email " . $send_success, "", NULL);

if ($new_pif_id == 0){
	
	$location = "Location: pif_ty.php?e=1";
}else{
	$location = "Location: pif_ty.php?e=2&pc=" . $pif_code;
}

header($location) ;