<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$project_id = $_POST["project_id"];
$asset_id = $_POST["asset_id"];
$asset_name = $_POST["asset_name"];
$asset_type_id = $_POST["asset_type_id"];
$asset_budget_media = $_POST["asset_budget_media"];
$asset_budget_production = $_POST["asset_budget_production"];
$asset_quantity = $_POST["asset_quantity"];
$asset_notes = $_POST["asset_notes"];
if(!empty($_POST["asset_has_ge"])){
	$asset_has_ge = $_POST["asset_has_ge"];
}else{
	$asset_has_ge = 0;
}
if(!empty($_POST["asset_for_aps"])){
	$asset_for_aps = $_POST["asset_for_aps"];
}else{
	$asset_for_aps = 0;
}
$asset_start_date = convert_datepicker_date($_POST["asset_start_date"]);
$asset_end_date = convert_datepicker_date($_POST["asset_end_date"]);
$update_success = update_asset($asset_id, $asset_name, $asset_type_id, $asset_budget_media, $asset_budget_production, $asset_quantity, $asset_notes, $user_id, $asset_start_date, $asset_end_date, $asset_has_ge, $asset_for_aps);
if ($update_success == 0){
	
	$location = "Location: manage_project.php?e=7&p=" . $project_id . "&showassets=1#asset_" . $asset_id;
}else{
	$location = "Location: manage_project.php?e=6&p=" . $project_id . "&showassets=1#asset_" . $asset_id;
}

header($location) ;
