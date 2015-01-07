<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_type_id = $_POST["atid"];
$asset_type_name = $_POST["asset_type_name"];
$asset_type_category_id = $_POST["asset_type_category_id"];
$asset_type_template_id = $_POST["asset_type_template_id"];
$update_success = update_asset_type($asset_type_id, $asset_type_name, $asset_type_category_id, $asset_type_template_id);
if ($update_success <> 0){
	$location = "Location: new_asset_type.php";
}else{
	$location = "Location: new_asset_type.php?e=1";
}

header($location) ;
