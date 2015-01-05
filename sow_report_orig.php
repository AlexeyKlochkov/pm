<?php 
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";
//print $company_id;
$error_message = "";
//print $_SESSION["user_level"] ;
if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "Please select a business unit.";
	}
		if ($error_num == 2){
		$error_message = "Duplicate campaign for the chosen business unit and year.";
	}
}

$current_year = date("Y");
$current_month = date("m");
$month_name = get_month_abbrev($current_month);
//print $current_month;
//need format 2014-12-01
$month_year = $current_year . "-" . $current_month . "-01";



$campaign_id = "";
if (!empty($_GET["campaign_id"])){
	$campaign_id = $_GET["campaign_id"];
}

if (!empty($_GET["spend_month"])){
	$month_year = $_GET["spend_month"];
	$arr_month_year = explode("-", $month_year);
	$current_year = $arr_month_year[0];
	$current_month = $arr_month_year[1];
	//print_r($arr_month_year);
}

//figure out previous month to show the percent change
if ($current_month == 1){
	$prev_month = 12;
	$prev_year = $current_year -1;
}else{
	$prev_month = $current_month - 1;
	$prev_year = $current_year;
}

$prev_month_year = $prev_year . "-" . $prev_month . "-01";

//print $prev_month_year;

$export = 0;
if (!empty($_GET["export"])){
	$export = 1;
}

$vendor_other_id = get_vendor_other_id($company_id);

//$quarter_select = get_quarter_select($campaign_quarter);

$campaign_select = get_campaign_code_select($company_id, $campaign_id );
$campaign_select = str_replace("Please select", "All", $campaign_select );

$arr_spend_report = get_spend_report($company_id, $campaign_id, $month_year, $month_name);
//print_r($arr_campaigns );
$spend_table = "<table class = \"stats_table\" width = \"100%\"><tr><th>#</th><th>Line of Business</th><th>Project</th><th>Project Code</th><th>Vendor</th><th>Media Budget</th><th>Production Budget</th><th>Total Budget</th><th>Month</th><th>% Complete</th><th>% Increase</th><th>Spend Amount</th><th>Accrue Amount</th><th>Expense Account</th><th>PO #</th><th>Invoice #</th><th>Asset</th><th>Notes</th><th>Posted</th></tr>";
$spend_table .= "<form action = \"update_spend_posted.php\" method = \"POST\">";
if (!empty($arr_spend_report)){
	foreach ($arr_spend_report as $spend_row){
			$spend_id = $spend_row["spend_id"];
			//$project_id = $spend_row["project_id"];
			$project_name = $spend_row["project_name"];
			$project_code = $spend_row["project_code"];
			$vendor_name = $spend_row["vendor_name"];
			$vendor_other = $spend_row["vendor_other"];
			$vendor_id = $spend_row["vendor_id"];
			if($vendor_id == $vendor_other_id){
				$vendor_name = $vendor_other;
			}
			$asset_name = $spend_row["asset_name"];
			$invoice_number = $spend_row["invoice_number"];
			$po_number = $spend_row["po_number"];
			$percent_complete =  $spend_row["spend_percent"];
			$spend_date =  convertMySQLdate_to_PHP_month_year($spend_row["spend_month"]);
			$spend_amount = $spend_row["spend_amount"];
			$business_unit_name =  $spend_row["business_unit_name"];
			$project_media_budget = $spend_row["media_budget"];
			$project_production_budget = $spend_row["production_budget"];
			$spend_notes = $spend_row["spend_notes"];
			$cost_expense_account = $spend_row["cost_expense_account"];
			$posted = $spend_row["posted"];
			$total_budget = ($project_media_budget + $project_production_budget);
			$accrue_amount = ($spend_amount * ($percent_complete/100));
			$posted_checked = "";
			$posted_text = "no";
			if ($posted == 1){
				$posted_checked = "checked";
				$posted_text = "yes";
			}
			
			$posted_checkbox = "<input type = \"checkbox\" name = \"posted_" . $spend_id . "\" " . $posted_checked . ">";
			$posted_checkbox .= "<input type = \"hidden\" name = \"spend_" . $spend_id . "\" " . $posted_checked . " value = \"" . $posted . "\">";
			if ($_SESSION["user_level"] < 25){
				$posted_checkbox  = $posted_text;
			}
			
			$percent_increase = get_percent_increase($spend_id, $prev_month_year, $percent_complete);
		
			$spend_table .= "<tr>";
			$spend_table .= "<td align = \"right\">" . $spend_id . "</td>";
			$spend_table .= "<td>" . $business_unit_name . "</td>";
			$spend_table .= "<td>" . $project_name . "</td>";
			$spend_table .= "<td>" . $project_code . "</td>";
			$spend_table .= "<td>" . $vendor_name . "</td>";
			$spend_table .= "<td align = \"right\">" .  $project_media_budget. "</td>";
			$spend_table .= "<td align = \"right\">" .  $project_production_budget. "</td>";
			$spend_table .= "<td align = \"right\">" .  $total_budget. "</td>";
			$spend_table .= "<td align = \"right\">" .  $spend_date. "</td>";
			$spend_table .= "<td align = \"right\">" .  $percent_complete. "</td>";
			$spend_table .= "<td align = \"right\">" .  $percent_increase. "</td>";
			$spend_table .= "<td align = \"right\">" .  $spend_amount. "</td>";
			$spend_table .= "<td align = \"right\">" .  $accrue_amount. "</td>";
			$spend_table .= "<td align = \"left\">" .  $cost_expense_account. "</td>";
			$spend_table .= "<td align = \"left\">" .  $po_number. "</td>";
			$spend_table .= "<td align = \"left\">" .  $invoice_number. "</td>";
			$spend_table .= "<td align = \"left\">" .  $asset_name. "</td>";
			$spend_table .= "<td align = \"left\">" .  $spend_notes. "</td>";
			$spend_table .= "<td align = \"middle\">" .  $posted_checkbox . "</td>";
			$spend_table .= "</tr>";
			
			//$business_unit_name = $business_unit_row["business_unit_name"];
			//$business_unit_select .= "<option value = \"" . $business_unit_id . "\">" . $business_unit_name . "</option>";
			
	}
}else{
	$spend_table .= "<tr><td colspan = \"7\">No results for this query</td></tr>";
}
$spend_table .= "</tr><input type = \"hidden\" name = \"campaign_id\" value = \"" . $campaign_id . "\"><input type = \"hidden\" name = \"spend_month\" value = \"" . $month_year . "\">";
if ($_SESSION["user_level"] > 20){
	$spend_table .= "<tr><td colspan = \"17\">&nbsp;</td><td><input type = \"submit\" value = \"update\"></td></tr>";
}
$spend_table .= "</table></form>";

$active_checked = "";
$archived_checked = "";
$all_checked = "";

if (!empty($_GET["active"])){
	if(($_GET["active"]) == 1){
		$active_checked = " selected";
	}
	if(($_GET["active"]) == 2){
		$archived_checked = " selected";
	}
	if(($_GET["active"]) == 3){
		$all_checked = " selected";
	}
}

$spend_month_select = get_spend_month_select($company_id, $month_year);



?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>SOW Report</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
  <script>
  $(document).ready(function(){
    $("#campaign_form").validate();
  });
  </script>
</head>
<body>
<div id = "page">
	<div id = "main">
			<div id = "logo">
				<img src = "logo.png">
			</div>

		<?php 
		include "nav1.php";
		?> 
		<!--container div tag--> 
		<div id="container"> 
			
			<div id="mainContent"> <!--mainContent div tag--> 
				<h1>SOW Report</h1>

				<table class = "small_link" width = "100%">
					<tr><form id = "sow_report" action = "sow_report.php" method = "GET">
						<td width = "30%">&nbsp;</td>
						<td>Active AOP Budgets:<br><?php echo $campaign_select ?></td>
						<td>Month/Year:<br><?php echo $spend_month_select ?></td>

						<td><input type = "submit" value = "go"></td>
						</form>
					</tr>
				</table>
				<?php echo $spend_table ?>
				<br>
				<form action = "export_sow_report_csv.php" method="POST">
				<input type = "hidden" name = "campaign_id" value = "<?php echo $campaign_id ?>">
				<input type = "hidden" name = "month_year" value = "<?php echo $month_year ?>">
				<input type = "hidden" name = "month_name" value = "<?php echo $month_name ?>">
				<input type = "submit" value = "export">
				</form>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>