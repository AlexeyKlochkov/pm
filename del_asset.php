<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_id = $_POST["asset_id"];
$project_id = $_POST["project_id"];
$del_success = delete_asset($asset_id);

if ($del_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: manage_project.php?p=" . $project_id . "&showassets=1#assets";


header($location) ;

?>