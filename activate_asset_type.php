<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_type_id = $_GET["at"];
$active = $_GET["a"];

if($active == 2){
	$active = 0;
}

$active_success = activate_asset_type($asset_type_id, $active);

if ($active_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: new_asset_type.php?e=" . $error;
header($location) ;
