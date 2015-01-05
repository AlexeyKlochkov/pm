<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$phase_id = 0;
$project_id = $_POST["project_id"];
$schedule_name = $_POST["schedule_name"];

$asset_id = $_POST["asset_id"];
$schedule_description = $_POST["schedule_description"];

if (!empty($_POST["phase_id"])){
	$phase_id = $_POST["phase_id"];
}

//if phase_id is zero, there is no assigned phase, but it still has an incrementing phase_order.

$error = 0;
//figure out the phase order.
$new_phase_order = 1;
$max_phase_order = get_max_phase_order($project_id, $phase_id);
if (empty($max_phase_order)){
	$new_phase_order = 1;
}else{
	$new_phase_order = $max_phase_order + 1;
}
$new_schedule_id  = insert_schedule($project_id, $schedule_name, $phase_id, $asset_id, $schedule_description, $new_phase_order, $user_id );
if ($new_schedule_id <> 0){
	$location = "Location: manage_schedules.php?e=6&p=" . $project_id ;
}else{
	$location = "Location: manage_schedules.php?e=1&p=" . $project_id;
}

header($location) ;
