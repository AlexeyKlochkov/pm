<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$business_unit_id = $_POST["business_unit_id"];
$business_unit_name = $_POST["business_unit_name"];
$business_unit_abbrev = $_POST["business_unit_abbrev"];
$default_cost_code = $_POST["default_cost_code"];
$business_unit_owner_id = $_POST["business_unit_owner_id"];
$active = $_POST["active"];

$update_success = update_business_unit($business_unit_id, $business_unit_name, $business_unit_abbrev, $default_cost_code, $active, $business_unit_owner_id);
if ($update_success <> 0){
	$location = "Location: edit_business_unit.php?e=2&b=" . $business_unit_id;
}else{
	$location = "Location: edit_business_unit.php?e=1&b=" . $business_unit_id;
}

header($location) ;
