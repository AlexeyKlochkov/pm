<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";


$schedule_id = $_GET["s"];
$projcet_id = $_GET["p"];
$del_success1 = delete_schedule_tasks($schedule_id);
$del_success2 = delete_schedule($schedule_id);
$error = 0;
if ($del_success1 <> 1){
	$error = 1;
}
if ($del_success2 <> 1){
	$error = 1;
}
$location = "Location: manage_schedules.php?p=" . $projcet_id . " &e=" . $error;

header($location) ;
