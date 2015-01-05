<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_type_id = $_POST["asset_type_id"];
$project_id = $_POST["project_id"];
$asset_item_id = $_POST["asset_item_id"];

//print_r($_POST);
//$update_success = update_asset_type($asset_type_id, $asset_type_name, $asset_type_category_id, $asset_type_template_id);
foreach($_POST as $key=>$value)
{
	$variable_name = $key;
	$variable_value = $value;
	$first_three_characters = substr($variable_name, 0, 3);
	//print $first_three_characters . "<br>";
	if ($first_three_characters == "att"){
		$arr_attribute = explode("-", $variable_name);
		$asset_attribute_id = $arr_attribute[1];
		//print $variable_value . "<br>";
		$update_success = replace_into_asset_item_attribute($asset_item_id, $asset_attribute_id, $variable_value);
	}
}

//handle empty checkboxes
if(!empty($_POST["checkbox_list"])){
	$arr_checkboxes = explode(",", $_POST["checkbox_list"]);
	foreach ($arr_checkboxes as $checkbox_row){
			$current_checkbox = $checkbox_row;
			if(empty($_POST["att-" . $current_checkbox])){
				//print $current_checkbox . "-- empty";
				//empty checkbox detected. Set it to zero.
				$update_check_success = update_empty_item_attribute_checkbox($asset_item_id, $current_checkbox);
			}
	}
}

//print $audit_id;
$update_success = 1;
if ($update_success <> 0){
	$location = "Location: asset_item_specsheet.php?u=1&p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id;
}else{
	$location = "Location: asset_item_specsheet.php?e=1&p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id;
}
header($location) ;


?>