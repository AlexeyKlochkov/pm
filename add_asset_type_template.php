<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_type_template_name = $_POST["asset_type_template_name"];

$new_asset_type_template_id = insert_asset_type_template($company_id, $asset_type_template_name);

if ($new_asset_type_template_id <> 0){
	$location = "Location: new_asset_type_template.php" ;
}else{
	$location = "Location: new_asset_type_template.php?e=1" ;
}

header($location) ;
