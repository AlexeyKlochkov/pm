<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_attribute_id = $_POST["asset_attribute_id"];
$asset_type_template_id = $_POST["asset_type_template_id"];
$include_attribute_name = $_POST["include_attribute_name"];

$new_asset_type_template_attribute_id = insert_asset_type_template_attribute($asset_type_template_id, $asset_attribute_id, $include_attribute_name, 0,0);

if ($new_asset_attribute_id <> 0){
	$location = "Location: edit_asset_type_template.php?asset_type_template_id=" . $asset_type_template_id ;
}else{
	$location = "Location: edit_asset_type_template.php?asset_type_template_id=" . $asset_type_template_id . "&e=1";
}

header($location) ;
