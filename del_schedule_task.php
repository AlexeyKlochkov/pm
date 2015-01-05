<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$error = 0;
$schedule_task_id = $_GET["stid"];
$schedule_id = $_GET["s"];
$display_order = $_GET["d"];

//delete task
$delete_success = delete_schedule_task($schedule_task_id);

//shift all higher tasks back one
$update_success = move_schedule_tasks($schedule_id, $display_order, -1);


$location = "Location: manage_tasks.php?e=5&s=" . $schedule_id;


header($location) ;

?>