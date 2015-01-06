<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$schedule_template_name = $_POST["schedule_template_name"];
$error = 0;
//figure out the phase order.
$new_schedule_template_id  = insert_schedule_template($company_id, $schedule_template_name);
if ($new_schedule_template_id <> 0){
	$location = "Location: new_schedule_template.php?e=2";
}else{
	$location = "Location: new_schedule_template.php?e=1";
}

header($location) ;
