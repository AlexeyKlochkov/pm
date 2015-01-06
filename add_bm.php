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
$background = "";
$audience = "";
$required_elem = "";
$objectives = "";
$core_message = "";
$support_points = "";
$estimated_total_reach = 0;
$segment_reach_potential_students = 0;
$segment_reach_current_students = 0;
$segment_reach_employee = 0;
$segment_reach_faculty = 0;
$segment_reach_alumni = 0;
$segment_reach_wfs = 0;
$project_description=$_POST["wif_description"];
$uopx_benefit ="";
$uopx_risk="";
$project_objective="";
$segment_quantity_potential_students = 0;
$segment_quantity_current_students = 0;
$segment_quantity_employee = 0;
$segment_quantity_faculty = 0;
$segment_quantity_alumni = 0;
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

$new_pif_id = insert_pif($project_name, $company_id, $version, $marketing_owner_id, $exec_sponsor_id, $business_unit_id, $product_id, $request_date, $desired_delivery_date, $target_in_market_date, $expiration_date, $budget, $cost_code, $project_description, $uopx_benefit, $uopx_risk,
    $project_objective, $estimated_total_reach, $segment_reach_potential_students, $segment_reach_current_students, $segment_reach_employee, $segment_reach_faculty, $segment_reach_alumni, $segment_reach_wfs, $segment_quantity_potential_students, $segment_quantity_current_students,
    $segment_quantity_employee, $segment_quantity_faculty, $segment_quantity_alumni, $segment_quantity_wfs, $user_id, $orig_pif_id,$background,$audience,$objectives,$core_message,$support_points,1,$required_elem,1);

//insert PIF code
$business_unit_abbrev = get_business_unit_abbrev($business_unit_id);
$pif_code = "WIF_BM-" . $new_pif_id;

//if the business unit has not changed, and if we are updating an existing PIF, set old PIF to old and version the new one.
if($orig_business_unit_id == $business_unit_id){
    if(!empty($_POST["version"])){
        //PIF code reflects the original PIF ID
        $pif_code = "WIF_" . $business_unit_abbrev . "-" . $orig_pif_id . "_V" . $version;

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




//insert the PIF log
$log_update_success = insert_pif_log($new_pif_id, $pif_code . " created.", "", $user_id);
$i=0;
if(count($_FILES['filesToUpload']['name'])) {
    mkdir("pif_files/" . $pif_code, 0777);
    foreach ($_FILES['filesToUpload']['name'] as $file) {
        $img = "pif_files/" . $pif_code . "/" .$file;
        move_uploaded_file($_FILES["filesToUpload"]["tmp_name"][$i], $img);
        $pif_file_id = insert_pif_file($new_pif_id, $file);
        $i++;
    }
}

if ($new_pif_id == 0){

    $location = "Location: aop_ty.php?e=1";
}else{
    $location = "Location: aop_ty.php?e=2&pc=" . $pif_code;
}

header($location) ;