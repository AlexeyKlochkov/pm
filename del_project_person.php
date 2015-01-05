<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$project_user_id = $_GET["puid"];


if (!empty($_GET["page"])){
	$page = $_GET["page"];
	$schedule_id = $_GET["s"];
	$location = "Location: " . $page . ".php?s=" . $schedule_id;
}else{
	$page = "manage_project";
	$project_id = $_GET["p"];
	$location = "Location: manage_project.php?p=" . $project_id;
}

$del_success = delete_project_person($project_user_id);

if ($del_success == 1){
	$error = 0;
}else{
	$error = 1;
}




header($location) ;

?>