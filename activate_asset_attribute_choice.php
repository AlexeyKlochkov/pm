<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_attribute_id = $_GET["aaid"];
$asset_attribute_choice_id = $_GET["aacid"];
$display_order = $_GET["d"];
$activate_success = activate_asset_attribute_choice($asset_attribute_choice_id, 1, ($display_order + 1));

if ($activate_success == 1){
	$error = 0;
}else{
	$error = 1;
}

//$move_success = move_asset_attribute_choices($asset_attribute_id, $display_order);

$location = "Location: new_asset_attribute.php?aaid=" . $asset_attribute_id;
header($location) ;
