<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";


$business_unit_id = $_GET["b"];
$active = $_GET["a"];

if($active == 2){
	$active = 0;
}

$active_success = activate_business_unit($business_unit_id, $active);

if ($active_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: new_business_unit.php?e=" . $error;
header($location) ;
