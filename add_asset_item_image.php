<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
$asset_item_id = $_POST["asset_item_id"];
$project_id = $_POST["project_id"];
$asset_type_id = $_POST["asset_type_id"];
$image_id = $_POST["image_id"];

$new_asset_item_image_id = insert_asset_item_image($asset_item_id, $image_id);

if ($new_asset_item_image_id <> 0){
	$location = "Location: asset_item_specsheet.php?p=". $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id;
}else{
	$location = "Location: asset_item_specsheet.php?e=4&p=". $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id;
}

header($location) ;
