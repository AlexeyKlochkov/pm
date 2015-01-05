<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

///create hidden fields with current values
//check if the incoming checkboxes have the same value, whether 1 or 0
//if not, do the update.

foreach($_POST as $key=>$value)
{
	$variable_name = $key;
	$variable_value = $value;
	//print $variable_name . "--" . $variable_value . "<br>";
	
	$first_five_characters = substr($variable_name, 0, 5);

	if ($first_five_characters == "spend"){
		$arr_spend_id = explode("_", $variable_name);
		$current_spend_id = $arr_spend_id[1];
		//if the checkbox is there, it's currently on.
		if(!empty($_POST["posted_" . $current_spend_id])){
			//these should be on. If they aren't, turn them on.
			//print $current_spend_id . " should be on. Currently: " . $variable_value . "<br>";
			//print $variable_value;
			if(empty($variable_value)){
				$update_success = update_spend_posted($current_spend_id, 1);
				//print "turned on " . $current_spend_id . "<br>";
			}
		}else{
			//These are checkboxes that are currently turned off.
			//print $current_spend_id . "<br>";
			if($variable_value == "1"){
				//turn these off.
				$update_success = update_spend_posted($current_spend_id, 0);
				//print "turned off " . $current_spend_id . "<br>";
			}
		
		}
	}
}

$start_month = $_POST["start_month"];
$end_month = $_POST["end_month"];
$campaign_id = $_POST["campaign_id"];

$location = "Location: sow_report.php?campaign_id=" . $campaign_id . "&start_month=" . $start_month . "&end_month=" . $end_month;
header($location) ;

?>