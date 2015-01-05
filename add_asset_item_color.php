<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$asset_item_id = $_POST["asset_item_id"];
$print_color_id = $_POST["print_color_id"];
$coated = $_POST["coated"];
$process_or_spot = $_POST["process_or_spot"];
$ink_used_in = $_POST["ink_used_in"];
$tint = $_POST["tint"];
$notes = $_POST["notes"];
$project_id = $_POST["project_id"];
$asset_type_id = $_POST["asset_type_id"];

$new_asset_item_color_id = insert_asset_item_color($asset_item_id, $print_color_id, $coated, $process_or_spot, $ink_used_in, $tint, $notes);

if ($new_asset_item_color_id <> 0){
	$location = "Location: asset_item_specsheet.php?u=1&p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id;
}else{
	$location = "Location: asset_item_specsheet.php?e=2&p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id;
}

header($location) ;
