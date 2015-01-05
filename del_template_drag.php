<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

//$asset_id = $_POST["asset_id"];
$current_id = $_POST["id"];
$object_type = $_POST["object_type"];

if($object_type == "attaid"){
	$update_success = delete_asset_type_template_attribute($current_id);
}
if($object_type == "attgid"){
	$update_success = delete_asset_type_template_garnish($current_id);
}


//print $new_asset_id;

//if ($update_success <> 0){
	
//	$location = "Location: manage_tasks.php?e=2&s=" . $schedule_id;
//}else{
//	$location = "Location: manage_tasks.php?e=1&s=" . $schedule_id;
//}

//header($location) ;


?>