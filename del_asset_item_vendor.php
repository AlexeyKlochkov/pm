<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_item_id = $_GET["aiid"];
$project_id = $_GET["p"];
$asset_type_id = $_GET["atid"];
$asset_item_vendor_id = $_GET["aivid"];

$del_success = delete_asset_item_vendor($asset_item_vendor_id);

if ($del_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: asset_item_specsheet.php?u=3&p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id;


header($location) ;
