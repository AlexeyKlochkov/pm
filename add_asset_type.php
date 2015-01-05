<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_type_name = $_POST["asset_type_name"];
$asset_type_category_id = $_POST["asset_type_category_id"];
$asset_type_template_id = $_POST["asset_type_template_id"];

$new_asset_type_id = insert_asset_type($company_id, $asset_type_name, $asset_type_category_id, $asset_type_template_id);

if ($new_asset_type_id <> 0){
	$location = "Location: new_asset_type.php?e=2";
}else{
	$location = "Location: new_asset_type.php?e=1";
}

header($location) ;