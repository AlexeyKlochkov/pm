<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$project_id = $_POST["project_id"];
$spend_id = $_POST["spend_id"];
$percent_complete = $_POST["percent_complete"];
//$spend_date = $_POST["spend_date"];
$spend_month = $_POST["month"];
$spend_year = $_POST["year"];
$spend_date = $spend_year . "-" . $spend_month . "-1";

//$spend_date = convert_datepicker_date($spend_date);

$insert_spend_success = insert_spend_month_percentage($spend_id, $spend_date, $percent_complete);

if ($insert_spend_success <> 0){
	$location = "Location: edit_spend.php?e=2&p=" . $project_id . "&s=" . $spend_id;
}else{
	$location = "Location: edit_spend.php?e=3&p=" . $project_id . "&s=" . $spend_id;
}


header($location) ;
