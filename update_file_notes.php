<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$project_file_id = $_POST["project_file_id"];
$project_id = $_POST["project_id"];
$file_notes = $_POST["file_notes"];
$file_type = $_POST["file_type"];
$update_success = update_file_notes($project_file_id, $file_notes);

if ($file_type[0] == "R"){
	$file_type = "CR";
}

if ($update_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: manage_project.php?show_files=1&show" . $file_type. "=1&p=" . $project_id . "#files";


header($location) ;

?>