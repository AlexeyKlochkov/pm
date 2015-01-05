<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
$asset_id = $_GET["a"];
$project_id = $_GET["p"];
$asset_in_market_date = $_GET["aimd"];
$asset_expiration_date = $_GET["aed"];
$asset_has_ge = $_GET["hge"];
$project_code = get_project_code($project_id);
$aps_product_id = "";

if(!empty($asset_in_market_date)){
	$asset_in_market_date = convert_datepicker_date($asset_in_market_date);
}
if(!empty($asset_expiration_date)){
	$asset_expiration_date = convert_datepicker_date($asset_expiration_date);
}

$max_asset_item_num = get_max_asset_item_num($asset_id);
$new_asset_item_num = $max_asset_item_num + 1;
$asset_item_code = $project_code . "-" . $asset_id . "-" . $new_asset_item_num;
$new_asset_item_id = insert_asset_item($asset_id, $asset_item_code, $aps_product_id, $new_asset_item_num, $asset_in_market_date,  $asset_expiration_date, $asset_has_ge);
$num_asset_items = get_asset_item_count($asset_id);
$update_quantity_success = update_asset_quantity($asset_id, $num_asset_items);

if ($new_asset_item_id <> 0){
	$location = "Location: manage_project.php?p=". $project_id . "&showassets=1#asset_" . $asset_id;
}else{
	$location = "Location: manage_project.php?e=7&p=". $project_id . "&showassets=1#asset_" . $asset_id;
}

header($location) ;
