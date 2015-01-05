<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$schedule_task_id = $_POST["stid"];
$progress = $_POST["progress"];
$day_count_tasks = $_POST["dct"];
$day_count_approvals = $_POST["dca"];

$update_success =update_progress($schedule_task_id, $progress);

if ($update_success <> 0){
	$location = "Location: index.php?e=4&dct=" . $day_count_tasks . "&dca=" . $day_count_approvals;
}else{
	$location = "Location: index.php?e=5&dct=" . $day_count_tasks . "&dca=" . $day_count_approvals;
}


header($location) ;

?>