<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/functions.php";
include "functions/queries.php";

$today = date("m/d/Y"); 
$day_of_week = date( "w", strtotime($today));
$monday_difference = (1-$day_of_week );
$monday = Date("m/d/Y", strtotime("$today $monday_difference Day"));
$thursday = Date("m/d/Y", strtotime("$monday -4 Day"));
$friday = Date("m/d/Y", strtotime("$monday -3 Day"));
$prev_friday = Date("m/d/Y", strtotime("$friday -1 Week"));

$date_range_message = "";
$current_checked = "";
$choose_checked = "";
$date_choice = "";
$start_date_field = $prev_friday;
$end_date_field = $friday;

if (!empty($_POST["date_choice"])){
	 $date_choice = $_POST["date_choice"];
}

if (empty($date_choice)){
	//if date_choice empty, default to the current week.
	$start_date = $prev_friday; //no beginning, anything that's open and was submitted up till Thursday
	$end_date = $friday; //set this to Friday - anything before Friday
	$date_range_message = "Week of " . $monday ;
	$current_checked = "checked";
}
//print ($date_choice);
if ($date_choice == "choose"){
	//dates have been selected
	//start_date and end_date are required fields in this case.
	$start_date = $_POST["start_date"];
	$end_date = $_POST["end_date"];
	$start_date_field = $start_date;
	$end_date_field = $end_date;
	$end_date = Date("m/d/Y", strtotime("$end_date +1 Day")); //add a day to the selected end date
	$date_range_message = $start_date . " through " . $end_date ;
	$choose_checked = "checked";
	
}
if ($date_choice == "current"){
	$start_date = $prev_friday; //no beginning, anything that's open and was submitted up till Thursday
	$end_date = $friday; //set this to Friday - anything before Friday
	$date_range_message = "Week of " . $monday ;
	$current_checked = "checked";
}
//print $end_date;
//print $thursday;

$export = 0;
if (!empty($_GET["export"])){
	$export = 1;
}

$vendor_other_id = get_vendor_other_id($company_id);

//$quarter_select = get_quarter_select($campaign_quarter);

$arr_lob = get_business_units($company_id, 1);
$planned_grand_total = 0;
$unplanned_grand_total = 0;
$compliance_grand_total = 0;
$pif_grand_total = 0;

$arr_pif_report_output = array();
$arr_headers1 = array("Submitted PIFs " . $date_range_message , "", "", "", "");
array_push($arr_pif_report_output, $arr_headers1);

$arr_headers2 = array("Line of Business" , "planned", "un-planned", "compliance", "total");
array_push($arr_pif_report_output, $arr_headers2);

if (!empty($arr_lob)){
	foreach ($arr_lob as $lob_row){
			$business_unit_id = $lob_row["business_unit_id"];
			//$project_id = $spend_row["project_id"];
			$business_unit_name = $lob_row["business_unit_name"];
			$business_unit_abbrev = $lob_row["business_unit_abbrev"];
			$num_planned = 0;
			$num_unplanned = 0;
			$num_compliance = 0;
			$total = 0;
			$arr_count_aop_type_pif = get_aop_type_pif_counts($company_id, $business_unit_id, $start_date, $end_date);
			if (!empty($arr_count_aop_type_pif)){
				foreach ($arr_count_aop_type_pif as $aop_type_row){
					$aop_activity_type_name = $aop_type_row["aop_activity_type_name"];
					$num_pifs = $aop_type_row["num_pifs"];
					if ($aop_activity_type_name == "Planned"){
						$num_planned = $num_pifs;
						$planned_grand_total = $planned_grand_total + $num_planned;
					}
					if ($aop_activity_type_name == "Unplanned"){
						$num_unplanned = $num_pifs;
						$unplanned_grand_total = $unplanned_grand_total + $num_unplanned;
					}
					if ($aop_activity_type_name == "Compliance"){
						$num_compliance = $num_pifs;
						$compliance_grand_total = $compliance_grand_total + $num_compliance;
					}
					$total = $num_planned +$num_unplanned + $num_compliance;
	
				}
			}
			$arr_current_pif_report_row = array($business_unit_name,  $num_planned,  $num_unplanned,  $num_compliance, $total);
			array_push($arr_pif_report_output, $arr_current_pif_report_row);
	}
}else{
	$arr_no_results = array("No results for this query", "", "", "", "");
	array_push($arr_pif_report_output, $arr_no_results);
}
$pif_grand_total = $planned_grand_total + $unplanned_grand_total + $compliance_grand_total;
$arr_grand_total = array("Totals:",  $planned_grand_total,  $unplanned_grand_total, $compliance_grand_total, $pif_grand_total);
array_push($arr_pif_report_output, $arr_grand_total);
$arr_blank_line = array("","","","","");
array_push($arr_pif_report_output, $arr_blank_line);

$arr_pif_asset_headers1 = array("PIF Asset Counts  " . $date_range_message , "", "", "", "");
array_push($arr_pif_report_output, $arr_pif_asset_headers1);

$arr_pif_asset_headers2 = array("PIF Asset Type", "planned", "un-planned", "compliance", "total");
array_push($arr_pif_report_output, $arr_pif_asset_headers2);

$grand_total_pif_asset_count_planned = 0;
$grand_total_pif_asset_count_unplanned = 0;
$grand_total_pif_asset_count_compliance = 0;

$arr_pif_asset_types = get_pif_asset_types($company_id, 1);
if (!empty($arr_pif_asset_types)){
	foreach ($arr_pif_asset_types as $pif_asset_type_row){
		$pif_asset_type_name = $pif_asset_type_row["pif_asset_type_name"];
		$pif_asset_type_id = $pif_asset_type_row["pif_asset_type_id"];
		$pif_asset_count_planned = get_pif_asset_count($company_id, $pif_asset_type_id, 1, $start_date, $end_date);
		$pif_asset_count_unplanned = get_pif_asset_count($company_id, $pif_asset_type_id, 2, $start_date, $end_date);
		$pif_asset_count_compliance = get_pif_asset_count($company_id, $pif_asset_type_id, 3, $start_date, $end_date);
		$total = $pif_asset_count_planned +$pif_asset_count_unplanned + $pif_asset_count_compliance;
		
		$arr_current_asset_report_row = array($pif_asset_type_name,  $pif_asset_count_planned,  $pif_asset_count_unplanned,  $pif_asset_count_compliance, $total);
		array_push($arr_pif_report_output, $arr_current_asset_report_row);
		
		$grand_total_pif_asset_count_planned = $grand_total_pif_asset_count_planned + $pif_asset_count_planned;
		$grand_total_pif_asset_count_unplanned = $grand_total_pif_asset_count_unplanned + $pif_asset_count_unplanned;
		$grand_total_pif_asset_count_compliance = $grand_total_pif_asset_count_compliance + $pif_asset_count_compliance;
		
		
		
	}
}else{
	$arr_no_results = array("No results for this query", "", "", "", "");
	array_push($arr_pif_report_output, $arr_no_results);
}
$pif_asset_count_grand_total = $grand_total_pif_asset_count_planned  + $grand_total_pif_asset_count_unplanned + $grand_total_pif_asset_count_compliance;

$arr_grand_total2 = array("Totals:",  $grand_total_pif_asset_count_planned,  $grand_total_pif_asset_count_unplanned, $grand_total_pif_asset_count_compliance, $pif_asset_count_grand_total);
array_push($arr_pif_report_output, $arr_grand_total2);


download_send_headers("PIF_Report" . $date_range_message . ".csv");
echo array2csv2($arr_pif_report_output);
die();





?>