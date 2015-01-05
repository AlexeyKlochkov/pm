<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$project_file_id = $_GET["pfid"];
$project_id = $_GET["p"];
$active = $_GET["a"];
$file_type = $_GET["f"];
$del_success = delete_project_file($project_file_id, $active);

if ($file_type[0] == "R"){
	$file_type = "CR";
}

if ($del_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: manage_project.php?show_files=1&show" . $file_type. "=1&p=" . $project_id . "#files";


header($location) ;

?>