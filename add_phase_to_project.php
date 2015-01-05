<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$project_id = $_POST["project_id"];
$phase_id = $_POST["phase_id"];
$display_order = $_POST["display_order"];

$new_project_phase_id = insert_project_phase($project_id, $phase_id, $display_order);
if ($new_project_phase_id <> 0){
	$location = "Location: manage_schedules.php?e=2&p=" . $project_id;
}else{
	$location = "Location: manage_schedules.php?e=3&p=" . $project_id;
}

header($location) ;
