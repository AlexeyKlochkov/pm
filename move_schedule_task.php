<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$schedule_id = $_GET["s"];
$swap1 = $_GET["s1"];
$swap2 = $_GET["s2"];

//first set the first one to zero to avoid constraing
$update1 = update_schedule_task_order($schedule_id, $swap1, 0);

//then set the second one to the first one
$update2 = update_schedule_task_order($schedule_id, $swap2, $swap1);
//then set the first one (which is now zero) to the second one
$update3 = update_schedule_task_order($schedule_id, 0, $swap2);

$location = "Location: manage_tasks.php?s=" . $schedule_id;

header($location) ;
