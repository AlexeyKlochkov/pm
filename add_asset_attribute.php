<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_attribute_name = $_POST["asset_attribute_name"];
$display_type = $_POST["display_type"];
$aps_product_id = "";

$new_asset_attribute_id = insert_asset_attribute($company_id, $asset_attribute_name, $display_type, 1);
if ($new_asset_attribute_id <> 0){
	$location = "Location: new_asset_attribute.php";
}else{
	$location = "Location: new_asset_attribute.php?e=1";
}

header($location) ;
