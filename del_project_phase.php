<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$error = 0;
$project_phase_id = $_GET["ppid"];
$project_id = $_GET["p"];
$phase_id = $_GET["ph"];

	//check to see if this phase is in use. If so, don't delete it, and send a warning.
$get_count_schedules_in_a_phase = get_count_schedules_in_a_phase($project_id, $phase_id);

if ($get_count_schedules_in_a_phase == 0){
	//if there are no schedules in a phase, delete the phase and shift stuff.
	$del_success = delete_project_phase($project_phase_id);
	$display_order = $_GET["d"];

	$location = "Location: manage_schedules.php?p=" . $project_id;
	if ($del_success == 1){
		update_project_phase_display_order($project_id, $display_order);
		$error = 0;
	}else{
		$location = "Location: manage_schedules.php?e=1&p=" . $project_id;
	}

}else{
	//if there are one or more scheduels in a phase, don't delete and send the warning.
	$location = "Location: manage_schedules.php?e=5&p=" . $project_id;

}







header($location) ;

?>