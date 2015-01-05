<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_type_category_id = $_GET["atcid"];
$active = $_GET["a"];

if($active == 2){
	$active = 0;
}

$active_success = activate_asset_type_category($asset_type_category_id, $active);

if ($active_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: new_asset_type_category.php?e=" . $error;
header($location) ;
