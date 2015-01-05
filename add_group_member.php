<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

//$asset_id = $_POST["asset_id"];
$user_group_id = $_POST["user_group_id"];

//handle deletes
foreach($_POST as $key=>$value) {
	$variable_name = $key;
	$variable_value = $value;
	$first_three_characters = substr($variable_name, 0, 3);
	if ($first_three_characters == "uid"){
		$arr_add = explode("-", $variable_name);
		$user_id_to_add = $arr_add[1];
		$update_success = insert_user_group_member($user_group_id, $user_id_to_add);
	}
}

if ($update_success <> 0){
	$location = "Location: edit_user_group.php?e=2&ug=" . $user_group_id;
}else{
	$location = "Location: edit_user_group.php?e=1&ug=" . $user_group_id;
}

header($location) ;
