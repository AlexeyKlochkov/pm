<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$schedule_task_id = $_POST["schedule_task_id"];
$day = $_POST["day"];
$hours = $_POST["hours"];
$minutes = $_POST["minutes"];
$user_id = $_POST["user_id"];
$worker_user_id = $_POST["worker_user_id"];
$notes = $_POST["notes"];
$campaign_id = $_POST["campaign_id"];
$project_id = $_POST["project_id"];
$task_id = $_POST["task_id"];
$phase_id = $_POST["phase_id"];
$project_manager_id = $_POST["project_manager_id"];
$archived = $_POST["archived"];
$redirect_page = $_POST["page"] . ".php";
$day_worked = convert_datepicker_date($day);
if (empty($hours)){
	$hours = 0;
}
if (empty($minutes)){
	$minutes = 0;
}
$time_worked = $hours . ":" . $minutes . ":00";
$new_schedule_task_time_id = insert_schedule_task_time($schedule_task_id, $worker_user_id, $time_worked, $day_worked, $notes, $user_id);
if ($new_schedule_task_time_id <> 0){
	$location = "Location: " . $redirect_page . "?e=1&campaign_id=" . $campaign_id . "&project_id=" . $project_id . "&task_id=" . $task_id . "&phase_id=" . $phase_id . "&project_manager_id=" . $project_manager_id . "&archived=" . $archived;
}else{
	$location = "Location: " . $redirect_page . "?e=2&campaign_id=" . $campaign_id . "&project_id=" . $project_id . "&task_id=" . $task_id . "&phase_id=" . $phase_id . "&project_manager_id=" . $project_manager_id . "&archived=" . $archived;
}
print $location;
header($location) ;
