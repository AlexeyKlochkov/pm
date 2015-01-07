<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/functions.php";
include "functions/queries.php";
$campaign_id = $_POST["campaign_id"];
//$company_id = $_POST["company_id"];
$month_year = $_POST["month_year"];
$month_name = $_POST["month_name"];
//print $data_array;

//figure out previous month to show the percent change
$arr_month_year = explode("-", $month_year);
$current_year = $arr_month_year[0];
$current_month = $arr_month_year[1];

if ($current_month == 1){
	$prev_month = 12;
	$prev_year = $current_year -1;
}else{
	$prev_month = $current_month - 1;
	$prev_year = $current_year;
}

$prev_month_year = $prev_year . "-" . $prev_month . "-01";

$vendor_other_id = get_vendor_other_id($company_id);

$arr_spend = array();
$arr_headers = array("Spend ID", "Business Unit", "Project Code", "Project Name", "Vendor Name", "Media Budget", "Production Budget", "Total Project Cost", "Spend Month", "Spend Percent", "% Increase from Prev Month", "Spend Amount", "Accrue Amount", "Cost Expense Account", "PO Number", "Invoice Number", "Asset Name", "Spend Notes","Posted");

array_push($arr_spend, $arr_headers);
$arr_spend_report = get_spend_report($company_id, $campaign_id, $month_year, $month_name);

if (!empty($arr_spend_report)){
	foreach ($arr_spend_report as $spend_row){
			$spend_id = $spend_row["spend_id"];
			$business_unit_name = $spend_row["business_unit_name"];
			$project_code = $spend_row["project_code"];
			$project_name = $spend_row["project_name"];
			$vendor_id = $spend_row["vendor_id"];
			$vendor_name = $spend_row["vendor_name"];
			$vendor_other = $spend_row["vendor_other"];
			if($vendor_id == $vendor_other_id){
				$vendor_name = $vendor_other;
			}
			
			$media_budget = $spend_row["media_budget"];
			$production_budget = $spend_row["production_budget"];
			$total_project_cost = $spend_row["total_project_cost"];
			$spend_month = $spend_row["spend_month"];
			$spend_percent = $spend_row["spend_percent"];
			$spend_amount = $spend_row["spend_amount"];
			$accrue_amount = $spend_row["accrue_amount"];
			$cost_expense_account = $spend_row["cost_expense_account"];
			$po_number = $spend_row["po_number"];
			$invoice_number = $spend_row["invoice_number"];
			$asset_name = $spend_row["asset_name"];
			$spend_notes = $spend_row["spend_notes"];
			$posted = $spend_row["posted"];
			$posted_text = "no";
			if ($posted == 1){
				$posted_text = "yes";
			}
			$percent_increase = get_percent_increase($spend_id, $prev_month_year, $spend_percent);
			
		$arr_current_variables = array($spend_id , $business_unit_name, $project_code, $project_name, $vendor_name, $media_budget, $production_budget, $total_project_cost, $spend_month, $spend_percent, $percent_increase, $spend_amount, $accrue_amount, $cost_expense_account, $po_number, $invoice_number, $asset_name, $spend_notes,$posted_text);
		array_push($arr_spend, $arr_current_variables);
	}
}
download_send_headers("SOW_Report" . date("Y-m-d") . ".csv");
echo array2csv2($arr_spend);
die();
