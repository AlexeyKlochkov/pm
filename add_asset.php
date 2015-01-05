<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$project_id = $_POST["project_id"];
$asset_type_id = $_POST["asset_type_id"];

$new_asset_id = insert_asset($project_id, $asset_type_id, $user_id);
if ($new_asset_id == 0){
	
	$location = "Location: manage_project.php?e=2&p=" . $project_id;
}else{
	$location = "Location: edit_asset.php?a=" . $new_asset_id;
}

header($location) ;
