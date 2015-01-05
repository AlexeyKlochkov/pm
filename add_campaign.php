<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$business_unit_id = $_POST["business_unit_id"];
$campaign_description = $_POST["campaign_description"];
$quarter = $_POST["campaign_quarter"];
$year = $_POST["campaign_year"];
$budget = $_POST["campaign_budget"];

$business_unit_abbrev = get_business_code($business_unit_id);


$error = 0;
if ($business_unit_id == 0){
	//submitted without a business unit
	$error = 1;
}


$new_campaign_id = insert_campaign($company_id, $business_unit_id, $campaign_description, $quarter, $year, $budget, $user_id );

if ($new_campaign_id == 0){
	//dupe error
	$error = 2;
}else{
	$campaign_code = $business_unit_abbrev . "-" . $quarter . $year;
	$campaign_code_success = insert_campaign_code($new_campaign_id, $campaign_code);
	
}

if ($error == 0){
	$location = "Location: campaigns.php";
}else{
	$location = "Location: new_campaign.php?e=" . $error;
}

header($location) ;
