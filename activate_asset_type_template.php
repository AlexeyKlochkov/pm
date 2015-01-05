<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_type_template_id = $_GET["attid"];
$active = $_GET["a"];

if($active == 2){
	$active = 0;
}

$active_success = activate_asset_type_template($asset_type_template_id, $active);

if ($active_success == 1){
	$error = 0;
}else{
	$error = 2;
}

$location = "Location: new_asset_type_template.php?e=" . $error;
header($location) ;
