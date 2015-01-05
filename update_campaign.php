<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$campaign_id = $_POST["campaign_id"];
$business_unit_id = $_POST["business_unit_id"];
$campaign_description = $_POST["campaign_description"];
$campaign_quarter = $_POST["campaign_quarter"];
$campaign_year = $_POST["campaign_year"];
$campaign_budget = $_POST["campaign_budget"];
$campaign_active = $_POST["active"];

if (empty($campaign_active)){
	$active = 0;
}else{
	$active = 1;
}

$error = 0;

$update_campaign = update_campaign($campaign_id, $business_unit_id, $campaign_description, $campaign_quarter, $campaign_year, $campaign_budget, $user_id, $active );

if ($update_campaign == 0){
	//dupe error
	$error = 2;
}

//print $audit_id;

if ($error == 0){
	$location = "Location: campaigns.php";

}else{
	$location = "Location: edit_campaign.php?e=1&c=" . $campaign_id;
}

header($location) ;


?>