<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$project_id = $_GET["p"];
$phase_id = $_GET["ph"];

$swap1 = $_GET["s1"];
$swap2 = $_GET["s2"];

//first set the first one to zero to avoid constraing
$update1 = update_project_schedule_order($project_id, $phase_id, $swap1, 0);

//then set the second one to the first one
$update2 = update_project_schedule_order($project_id, $phase_id, $swap2, $swap1);
//then set the first one (which is now zero) to the second one
$update3 = update_project_schedule_order($project_id, $phase_id, 0, $swap2);

$location = "Location: manage_schedules.php?p=" . $project_id;

header($location) ;

?>