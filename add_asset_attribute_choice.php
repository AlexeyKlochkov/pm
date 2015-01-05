<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_attribute_id = $_POST["aaid"];
$asset_attribute_choice_name = $_POST["asset_attribute_choice_name"];
$display_order = $_POST["display_order"];
$active = 1;

$new_asset_attribute_choice_id = insert_asset_attribute_choice($asset_attribute_id, $asset_attribute_choice_name, $display_order, $active);

if ($new_asset_attribute_choice_id <> 0){
	$location = "Location: new_asset_attribute.php?ed=1&aaid=". $asset_attribute_id;
}else{
	$location = "Location: new_asset_attribute.php?e=3&ed=1&aaid=". $asset_attribute_id;
}

header($location) ;
