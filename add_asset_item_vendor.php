<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$asset_item_id = $_POST["asset_item_id"];
$vendor_id = $_POST["vendor_id"];
$delivery_method = $_POST["delivery_method"];
$released_by = $_POST["released_by"];
$released_what = $_POST["released_what"];
$release_date = $_POST["release_date"];
$issue_date = $_POST["issue_date"];
$project_id = $_POST["project_id"];
$asset_type_id = $_POST["asset_type_id"];
$aps_product_id = "";

if(!empty($release_date)){
	$release_date = convert_datepicker_date($release_date);
}
if(!empty($issue_date)){
	$issue_date = convert_datepicker_date($issue_date);
}

$new_asset_item_vendor_id = insert_asset_item_vendor($asset_item_id, $vendor_id, $delivery_method, $released_by, $released_what, $release_date, $issue_date);

if ($new_asset_item_vendor_id <> 0){
	$location = "Location: asset_item_specsheet.php?u=1&p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id;
}else{
	$location = "Location: asset_item_specsheet.php?e=2&p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id;
}

header($location) ;
