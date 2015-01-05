<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$spend_id = $_GET["s"];
$project_id = $_GET["p"];

$del_success = delete_spend($spend_id);

if ($del_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: manage_project.php?p=" . $project_id . "#budget";


header($location) ;

?>