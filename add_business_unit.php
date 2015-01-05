<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$business_unit_name = $_POST["business_unit_name"];
$business_unit_abbrev = $_POST["business_unit_abbrev"];
$default_cost_code = $_POST["default_cost_code"];
$business_unit_owner_id = $_POST["business_unit_owner_id"];
$new_business_unit_id = insert_business_unit($company_id, $business_unit_name, $business_unit_abbrev, $default_cost_code, $business_unit_owner_id);

if ($new_business_unit_id <> 0){
	$location = "Location: new_business_unit.php?e=2";
}else{
	$location = "Location: new_business_unit.php?e=1";
}

header($location) ;
