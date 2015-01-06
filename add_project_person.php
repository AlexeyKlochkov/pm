<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$project_id = $_POST["project_id"];
$user_id = $_POST["user_id"];

if (!empty($_POST["page"])){
	$page = $_POST["page"];
	$schedule_id = $_POST["schedule_id"];
	$location = "Location: " . $page . ".php?s=" . $schedule_id . "&showusers=1";
}else{
	$page = "manage_project";
	$location = "Location: manage_project.php?p=" . $project_id . "&showusers=1";
}

$add_success = add_project_person($project_id, $user_id);
header($location) ;
