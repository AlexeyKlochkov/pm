<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_item_id = $_GET["aiid"];
$project_id = $_GET["p"];
$asset_type_id = $_GET["atid"];
$asset_item_image_id = $_GET["aiiid"];
$del_success = delete_asset_item_image($asset_item_image_id);

if ($del_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: asset_item_specsheet.php?p=". $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id;

header($location) ;

?>