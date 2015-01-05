<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$phase_id = $_POST["phase_id"];
$project_id = $_POST["project_id"];
$schedule_id = $_POST["schedule_id"];
$schedule_name = $_POST["schedule_name"];

$asset_id = $_POST["asset_id"];
$schedule_description = $_POST["schedule_description"];
$schedule_phase_order = $_POST["schedule_phase_order"];
$schedule_phase_order_orig = $_POST["schedule_phase_order"];
$current_phase_id = $_POST["current_phase_id"];

//if you are switching phases, find the max for the current project phase and increment it for this one.
if ($phase_id <> $current_phase_id){

	$max_phase_order = get_max_phase_order($project_id, $phase_id);
	//print $max_phase_order;
	$schedule_phase_order = $max_phase_order + 1;
}

if (!empty($_POST["phase_id"])){
	$phase_id = $_POST["phase_id"];
}

//if phase_id is zero, there is no assigned phase, but it still has an incrementing phase_order.

$error = 0;

//print $new_phase_order;

$update_success  = update_schedule($schedule_id, $schedule_name, $phase_id, $asset_id, $schedule_description, $schedule_phase_order );
//print $new_schedule_id;
//also need to update the schedules in the previous phase that are higher than this schedule_phase_order.

if ($phase_id <> $current_phase_id){
	$update_schedule_orders = update_schedule_orders($project_id, $current_phase_id, $schedule_phase_order_orig);
	//print $project_id . "--" . $current_phase_id . "--" . $schedule_phase_order_orig;
}

if ($update_success <> 0){
	$location = "Location: manage_schedules.php?e=4&p=" . $project_id ;
}else{
	$location = "Location: manage_schedules.php?e=3&p=" . $project_id;
}

header($location) ;


?>