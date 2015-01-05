<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

//$asset_id = $_POST["asset_id"];
$asset_item_id = $_POST["aiid"];
$aps_product_id = $_POST["aps_product_id"];
$asset_item_name = $_POST["name"];
$asset_item_has_ge = $_POST["has_ge"];
$asset_item_in_market_date = $_POST["asset_item_in_market_date"];
$asset_item_expiration_date = $_POST["asset_item_expiration_date"];
if(!empty($asset_item_in_market_date)){
	$asset_item_in_market_date = convert_datepicker_date($asset_item_in_market_date);
}
if(!empty($asset_item_expiration_date)){
	$asset_item_expiration_date = convert_datepicker_date($asset_item_expiration_date);
}
$update_success = update_aps_product_id($asset_item_id, $aps_product_id, $asset_item_name, $asset_item_has_ge, $asset_item_in_market_date, $asset_item_expiration_date);
		
	


//print $new_asset_id;

//if ($update_success <> 0){
	
//	$location = "Location: manage_tasks.php?e=2&s=" . $schedule_id;
//}else{
//	$location = "Location: manage_tasks.php?e=1&s=" . $schedule_id;
//}

//header($location) ;


?>