<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/functions.php";
include "functions/queries.php";

$campaign_id = "";
$start_month_year = "";
$end_month_year  = "";
if (!empty($_POST["campaign_id"])){
	$campaign_id = $_POST["campaign_id"];
}

if (!empty($_POST["start_month"])){
	$start_month_year = $_POST["start_month"];
	//print_r($arr_month_year);
}

if (!empty($_POST["end_month"])){
	$end_month_year = $_POST["end_month"];
	//print_r($arr_month_year);
}


$vendor_other_id = get_vendor_other_id($company_id);

$arr_spend = array();
$arr_headers = array("Spend ID", "Business Unit", "Project Code", "Project Name", "Vendor Name", "Media Budget", "Production Budget", "Total Project Cost", "Spend Month", "Spend Percent", "% Increase from Prev Month", "Spend Amount", "Cumulative Accrue Amount", "Current Month Accrue Amount", "Cost Expense Account", "PO Number", "Invoice Number", "Asset Name", "Spend Notes","Posted");

array_push($arr_spend, $arr_headers);
$arr_spend_report = get_spend_report_for_range($company_id, $campaign_id, $start_month_year , $end_month_year);

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
			
			
			//figure out the previous month for the percent_increase function
			$arr_month_year = explode("-", $spend_row["spend_month"]);
			$current_date_year = $arr_month_year[0];
			$current_date_month = $arr_month_year[1];
			//figure out previous month to show the percent change
			if ($current_date_month == 1){
				$prev_date_month = 12;
				$prev_date_year = $current_date_year -1;
			}else{
				$prev_date_month = $current_date_month - 1;
				$prev_date_year = $current_date_year;
			}
			$prev_month_year = $prev_date_year . "-" . $prev_date_month . "-01";	
			
			$percent_increase = get_percent_increase($spend_id, $prev_month_year, $spend_percent);
			$current_month_accrue_amount = ($spend_amount * ($percent_increase/100));
			
		$arr_current_variables = array($spend_id , $business_unit_name, $project_code, $project_name, $vendor_name, $media_budget, $production_budget, $total_project_cost, $spend_month, $spend_percent, $percent_increase, $spend_amount, $accrue_amount, $current_month_accrue_amount, $cost_expense_account, $po_number, $invoice_number, $asset_name, $spend_notes,$posted_text);
		array_push($arr_spend, $arr_current_variables);
	}
}
download_send_headers("SOW_Report" . date("Y-m-d") . ".csv");
echo array2csv2($arr_spend);
die();





?>