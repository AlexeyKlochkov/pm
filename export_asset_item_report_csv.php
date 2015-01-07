<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/functions.php";
include "functions/queries.php";

$campaign_id = "";
$start_month_year = "";
$end_month_year  = "";
$current_year = date("Y");
$current_month = date("m");
$month_name = get_month_abbrev($current_month);
//print $current_month;
//need format 2014-12-01
$start_month_year = $current_year . "-" . $current_month . "-01";
$end_month_year = $start_month_year;
$end_month_date = $start_month_year;

$campaign_id = "";
if (!empty($_POST["campaign_id"])){
	$campaign_id = $_POST["campaign_id"];
}

if (!empty($_POST["start_month"])){
	$start_month_year = $_POST["start_month"];
}

if (!empty($_POST["end_month"])){
	$end_month_year = $_POST["end_month"];
	 //$end_month_year;
	$end_month_date = substr($end_month_year, 0, -2) . "31";
	//print $end_month_date;
}

if (!empty($_POST["has_ge"])){
	$has_ge_checked = "checked";
	$has_ge = 1;
}else{
	$has_ge_checked = "";
	$has_ge = 0;
}

$asset_type_category_id = "";
if (!empty($_POST["asset_type_category_id"])){
	$asset_type_category_id = $_POST["asset_type_category_id"];
}

$export = 0;
if (!empty($_POST["export"])){
	$export = 1;
}

if (!empty($_POST["location_list"])){
	$location_list = $_POST["location_list"];
}else{
	$location_list = "";
}
//print $location_list;
$arr_asset_item = array();
$arr_headers = array("Asset Item #", "AOP", "Project Code", "Asset Type", "Asset Type Name Name", "In Market Date", "Expiration Date", "Has GE", "Locations", "File Folder", "File Link");

array_push($arr_asset_item, $arr_headers);
$arr_asset_item_report = get_asset_item_report_for_range($campaign_id, $start_month_year , $end_month_date, $has_ge, $asset_type_category_id, $location_list );

if (!empty($arr_asset_item_report)){
	foreach ($arr_asset_item_report as $asset_item_row){
			$asset_item_id = $asset_item_row["asset_item_id"];

			$asset_item_name = $asset_item_row["asset_item_name"];
			$asset_item_in_market_date = $asset_item_row["asset_item_in_market_date"];
			$asset_item_expiration_date = $asset_item_row["asset_item_expiration_date"];
			$asset_item_has_ge = $asset_item_row["asset_item_has_ge"];
			$project_code = $asset_item_row["project_code"];
			$project_file_name = $asset_item_row["project_file_name"];
			$campaign_code = $asset_item_row["campaign_code"];
			$asset_type_category_name = $asset_item_row["asset_type_category_name"];
			$asset_type_name = $asset_item_row["asset_type_name"];
			$asset_item_states = $asset_item_row["asset_item_states"];
			$business_unit_name = $asset_item_row["business_unit_name"];
			$file_network_folder = $asset_item_row["file_network_folder"];
			if($asset_item_has_ge == 1){
				$asset_item_has_ge_string = "yes";
			}else{
				$asset_item_has_ge_string = "no";
			}
			if(!empty($project_file_name)){
				$file_link = "http://ac-00019162.apollogrp.edu/pm/project_files/" . $project_code . "/" . $project_file_name;
			}else{
				$file_link = "&nbsp;";
			}
			
		$arr_current_variables = array($asset_item_id , $business_unit_name . " (" . $campaign_code . ")", $project_code, $asset_type_category_name . " - " . $asset_type_name, $asset_item_name, $asset_item_in_market_date, $asset_item_expiration_date, $asset_item_has_ge_string, $asset_item_states, $file_network_folder, $file_link);
		array_push($arr_asset_item, $arr_current_variables);
	}
}
download_send_headers("Asset_Item_Report" . date("Y-m-d") . ".csv");
echo array2csv2($arr_asset_item);
die();
