<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$project_id = $_POST["project_id"];
$spend_id = $_POST["spend_id"];
$spend_percent_id = $_POST["spend_percent_id"];
$percent_complete = $_POST["percent_complete"];
$update_success = update_spend_month($spend_percent_id, $percent_complete);

if ($update_success <> 0){
	$location = "Location: edit_spend.php?e=2&p=" . $project_id . "&s=" . $spend_id;
}else{
	$location = "Location: edit_spend.php?e=1&p=" . $project_id . "&s=" . $spend_id;
}


header($location) ;

?>