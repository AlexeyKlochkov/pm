<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_type_category_name = $_POST["asset_type_category_name"];
$asset_type_category_abbrev = $_POST["asset_type_category_abbrev"];

$new_asset_type_category_id = insert_asset_type_category($company_id, $asset_type_category_name, $asset_type_category_abbrev, 1);

if ($new_asset_type_category_id <> 0){
	$location = "Location: new_asset_type_category.php";
}else{
	$location = "Location: new_asset_type_category.php?e=1";
}

header($location) ;
