<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_item_id = $_POST["aaid"];
$project_id = $_POST["project_id"];
$asset_id = $_POST["asset_id"];
$state_list = "";
foreach($_POST as $key=>$value)
{
	$variable_name = $key;
	$variable_value = $value;
	$first_three_characters = substr($variable_name, 0, 3);
	if ($first_three_characters == "chk"){
		$arr_state = explode("_", $variable_name);
		$state_to_add = $arr_state[1];
		$state_list .= $state_to_add . ", ";
	}
}
$state_list = substr($state_list, 0, -2);
$update_success = update_asset_item_states($asset_item_id, $state_list);
if ($update_success <> 1){
	$location = "Location: manage_project.php?p=" . $project_id . "&showassets=1#asset_" . $asset_id;
}else{
	$location = "Location: manage_project.php?p=" . $project_id . "&showassets=1#asset_" . $asset_id;
}

header($location) ;
