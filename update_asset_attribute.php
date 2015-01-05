<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_attribute_id = $_POST["aaid"];
$asset_attribute_name = $_POST["asset_attribute_name"];
$display_type = $_POST["display_type"];

$update_success = update_asset_attribute($asset_attribute_id, $asset_attribute_name, $display_type);

//print $audit_id;

if ($update_success <> 0){
	$location = "Location: new_asset_attribute.php";
}else{
	$location = "Location: new_asset_attribute.php?e=2";
}

header($location) ;


?>