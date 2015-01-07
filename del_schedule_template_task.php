<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$schedule_template_tasks_id = $_GET["sttid"];
$schedule_template_id = $_GET["stid"];
$display_order = $_GET["d"];
$del_success = delete_schedule_template_task($schedule_template_tasks_id);

if ($del_success == 1){
	$error = 0;
	update_schedule_template_tasks_display_order($schedule_template_id, $display_order);
}else{
	$error = 1;
}

$location = "Location: edit_schedule_template.php?stid=" . $schedule_template_id;

header($location) ;
