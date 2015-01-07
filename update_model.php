<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$model_id = $_POST["model_id"];
$model_name = $_POST["model_name"];
$model_email = $_POST["model_email"];
$model_address = $_POST["model_address"];
$model_phone = $_POST["model_phone"];
$model_notes = $_POST["model_notes"];
$model_gender = $_POST["model_gender"];
$model_is_minor = $_POST["model_is_minor"];
$model_territory = $_POST["model_territory"];
$model_territory_other = $_POST["model_territory_other"];
$model_usage_category = "";
$model_usage_category_other = $_POST["model_usage_category_other"];
$model_start_date = $_POST["model_start_date"];
$model_start_date = convert_datepicker_date($model_start_date);
$model_end_date = $_POST["model_end_date"];
$model_end_date = convert_datepicker_date($model_end_date);
$representation_type = $_POST["representation_type"];
$agency_id = $_POST["agency_id"];
$model_released = $_POST["model_released"];
$duration_type = $_POST["duration_type"];
$media_rights = "";
$media_rights_other = $_POST["media_rights_other"];
$user_id = $_POST["user_id"];

foreach($_POST as $key=>$value)
{
	$variable_name = $key;
	$variable_value = $value;
	$first_two_characters = substr($variable_name, 0, 2);
	if ($first_two_characters == "MR"){
		$media_rights .= $value . ",";
	}
	if ($first_two_characters == "UC"){
		$model_usage_category .= $value . ",";
	}
}
$media_rights = substr($media_rights, 0, -1);
$model_usage_category = substr($model_usage_category, 0, -1);

if(empty($model_id)){
	$model_id = insert_model($model_name, $model_email, $model_address, $model_phone, $model_notes, $model_gender, $model_is_minor, $model_territory, $model_territory_other, $model_usage_category, $model_usage_category_other, $model_start_date, $model_end_date, $representation_type, $agency_id, $model_released, $duration_type, $media_rights, $media_rights_other, $user_id);
	if(!empty($model_id)){
		$update_success = 1;
	}else{
		$update_success = 0;
	}
}else{
	$update_success = update_model($model_id, $model_name, $model_email, $model_address, $model_phone, $model_notes, $model_gender, $model_is_minor, $model_territory, $model_territory_other, $model_usage_category, $model_usage_category_other, $model_start_date, $model_end_date, $representation_type, $agency_id, $model_released, $duration_type, $media_rights, $media_rights_other);
}
if ($update_success <> 0){
	$location = "Location: model.php?m=" . $model_id;
}else{
	$location = "Location: model.php?e=1&m=" . $model_id;
}

header($location) ;
