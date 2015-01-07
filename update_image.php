<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$image_id = $_POST["image_id"];
$width = $_POST["width"];
$height = $_POST["height"];
$file_size = $_POST["file_size"];
$resolution = $_POST["resolution"];
$stock_ref_code = $_POST["stock_ref_code"];
$image_stock_name = $_POST["image_stock_name"];
$stock_quote_id = $_POST["stock_quote_id"];
$stock_or_photographer = $_POST["stock_or_photographer"];
$rep_or_stock_house = $_POST["vendor_id"];
$photographer_name = $_POST["photographer_name"];
$rights_managed_type = $_POST["rights_managed_type"];
$royalty_free_type = $_POST["royalty_free_type"];
$image_media_rights = "";
$image_media_rights_other = $_POST["image_media_rights_other"];
$image_notes = $_POST["image_notes"];
$image_usage_start = convert_datepicker_date($_POST["image_usage_start"]);
$image_usage_end = convert_datepicker_date($_POST["image_usage_end"]);
if(!empty($_POST["unlimited_usage"])){
	$unlimited_usage = $_POST["unlimited_usage"];
}else{
	$unlimited_usage = "";
}

if(!empty($_POST["image_territory"])){
	$image_territory = $_POST["image_territory"];
}else{
	$image_territory = "";
}

$image_territory_other = $_POST["image_territory_other"];
if(!empty($_POST["release_received"])){
	$release_received = $_POST["release_received"];
}else{
	$release_received = "";
}
$release_type = $_POST["release_type"];

if(!empty($_POST["image_exclusivity"])){
	$image_exclusivity = $_POST["image_exclusivity"];
}else{
	$image_exclusivity = "";
}

$exclusivity_notes = $_POST["exclusivity_notes"];
$image_usage_category = "";
$image_usage_category_other = $_POST["image_usage_category_other"];
$original_project_code = $_POST["original_project_code"];
$original_project_manager = $_POST["original_project_manager"];
$original_art_buyer = $_POST["original_art_buyer"];

if(!empty($_POST["posting_to_asset_library"])){
	$posting_to_asset_library = $_POST["posting_to_asset_library"];
}else{
	$posting_to_asset_library = "";
}

$high_resolution_location = "";
$low_resolution_location = "";

if(!empty($_POST["image_needs_retouching"])){
	$image_needs_retouching = $_POST["image_needs_retouching"];
}else{
	$image_needs_retouching = "";
}

if(!empty($_POST["image_has_been_replaced"])){
	$image_has_been_replaced = $_POST["image_has_been_replaced"];
}else{
	$image_has_been_replaced = "";
}

$current_meta_data = $_POST["current_meta_data"];
$meta_data_list = "";
$active = 0;
if(!empty($_POST["active"])){
	$active = 1;
}
$user_id = $_POST["user_id"];

foreach($_POST as $key=>$value)
{
	$variable_name = $key;
	$variable_value = $value;
	$first_two_characters = substr($variable_name, 0, 2);
	//print $first_three_characters . "<br>";
	if ($first_two_characters == "MR"){
		$image_media_rights .= $value . ",";
	}
	if ($first_two_characters == "UC"){
		$image_usage_category .= $value . ",";
	}
	if ($first_two_characters == "HR"){
		$high_resolution_location .= $value . ",";
	}
	if ($first_two_characters == "LR"){
		$low_resolution_location .= $value . ",";
	}
	if ($first_two_characters == "MD"){
		$meta_data_list .= $value . ",";
	}
	
}
$image_media_rights = substr($image_media_rights, 0, -1);
$image_usage_category = substr($image_usage_category, 0, -1);
$high_resolution_location = substr($high_resolution_location, 0, -1);
$low_resolution_location = substr($low_resolution_location, 0, -1);
$meta_data_list = substr($meta_data_list, 0, -1);
$arr_current_meta_data = explode(",", $current_meta_data);
$arr_incoming_meta_data_list = explode(",", $meta_data_list);
//move through the incoming meta data and insert rows for new meta data if they are not there.
if (!empty($arr_incoming_meta_data_list)){
	foreach ($arr_incoming_meta_data_list as $incoming_meta_data_id){
		if(!in_array($incoming_meta_data_id, $arr_current_meta_data)){
			//this meta tag is not in the current set, so add it.
			$insert_success = add_meta_data_to_image($image_id, $incoming_meta_data_id);
		}
	}
}

//move through the current meta data and delete items that are not in the incoming meta data.
if (!empty($arr_current_meta_data)){
	foreach ($arr_current_meta_data as $current_meta_data_id){
		if(!in_array($current_meta_data_id, $arr_incoming_meta_data_list)){
			//this meta tag is in current set, but it's not in the incoming set, so they've unchecked it. Delete it.
			$insert_success = del_image_meta_data($image_id, $current_meta_data_id);
		}
	}
}

if(empty($image_id)){
	$image_id = insert_image($width, $height, $file_size, $resolution, $stock_ref_code, $image_stock_name, $stock_quote_id, $stock_or_photographer, $rep_or_stock_house, $photographer_name, $rights_managed_type, $royalty_free_type, $image_media_rights, $image_media_rights_other, $image_notes, $image_usage_start, $image_usage_end, $unlimited_usage, $image_territory, $image_territory_other, $release_received, $release_type, $image_exclusivity, $exclusivity_notes, $image_usage_category, $image_usage_category_other, $original_project_code, $original_project_manager, $original_art_buyer, $posting_to_asset_library, $high_resolution_location, $low_resolution_location, $image_needs_retouching, $image_has_been_replaced, $user_id);
	if(!empty($image_id)){
		$update_success = 1;
	}else{
		$update_success = 0;
	}
}else{
	$update_success = update_image($image_id, $width, $height, $file_size, $resolution, $stock_ref_code, $image_stock_name, $stock_quote_id, $stock_or_photographer, $rep_or_stock_house, $photographer_name, $rights_managed_type, $royalty_free_type, $image_media_rights, $image_media_rights_other, $image_notes, $image_usage_start, $image_usage_end, $unlimited_usage, $image_territory, $image_territory_other, $release_received, $release_type, $image_exclusivity, $exclusivity_notes, $image_usage_category, $image_usage_category_other, $original_project_code, $original_project_manager, $original_art_buyer, $posting_to_asset_library, $high_resolution_location, $low_resolution_location, $image_needs_retouching, $image_has_been_replaced, $active);
}

if ($update_success <> 0){
	$location = "Location: image.php?i=" . $image_id;
}else{
	$location = "Location: image.php?e=1&i=" . $image_id;
}

header($location) ;
