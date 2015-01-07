<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$schedule_template_id = $_GET["stid"];
$swap1 = $_GET["s1"];
$swap2 = $_GET["s2"];

//first set the first one to zero to avoid constraing
$update1 = update_schedule_template_tasks_order($schedule_template_id, $swap1, 0);

//then set the second one to the first one
$update2 = update_schedule_template_tasks_order($schedule_template_id, $swap2, $swap1);
//then set the first one (which is now zero) to the second one
$update3 = update_schedule_template_tasks_order($schedule_template_id, 0, $swap2);

$location = "Location: edit_schedule_template.php?stid=" . $schedule_template_id;

header($location) ;
