<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_type_category_id = $_POST["atcid"];
$asset_type_category_name = $_POST["asset_type_category_name"];
$asset_type_category_abbrev = $_POST["asset_type_category_abbrev"];

$update_success = update_asset_type_category($asset_type_category_id, $asset_type_category_name, $asset_type_category_abbrev);

//print $audit_id;

if ($update_success <> 0){
	$location = "Location: new_asset_type_category.php";
}else{
	$location = "Location: new_asset_type_category.php?e=1";
}

header($location) ;


?>