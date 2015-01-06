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

$today = date("m/d/Y"); 
$day_of_week = date( "w", strtotime($today));
$monday_difference = (1-$day_of_week );
$monday = Date("m/d/Y", strtotime("$today $monday_difference Day"));
$thursday = Date("m/d/Y", strtotime("$monday -4 Day"));
$friday = Date("m/d/Y", strtotime("$monday -3 Day"));
$thursday = Date("m/d/Y", strtotime("$friday -1 Day"));
$prev_friday = Date("m/d/Y", strtotime("$friday -1 Week"));
$date_range_message = "";
$current_checked = "";
$choose_checked = "";
$date_choice = "";
$start_date_field = $prev_friday;
$end_date_field = $thursday;

if (!empty($_GET["date_choice"])){
	 $date_choice = $_GET["date_choice"];
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
	$start_date = $_GET["start_date"];
	$end_date = $_GET["end_date"];
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

//print_r($arr_campaigns );
$pif_report_table = "<table class = \"stats_table\" width = \"70%\"><tr><th colspan = \"5\">Submitted Project Briefs " . $date_range_message . "</th></tr><tr><th>Line of Business</th><th>planned</th><th>un-planned</th><th>compliance</th><th>total</th></tr>";
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
		
			$pif_report_table .= "<tr>";
			$pif_report_table .= "<td>" . $business_unit_name . "</td>";
			$pif_report_table .= "<td align = \"right\" width = \"80\">" .  $num_planned. "</td>";
			$pif_report_table .= "<td align = \"right\" width = \"80\">" .  $num_unplanned. "</td>";
			$pif_report_table .= "<td align = \"right\" width = \"80\">" .  $num_compliance. "</td>";
			$pif_report_table .= "<td align = \"right\" width = \"80\">" .  $total. "</td>";
			$pif_report_table .= "</tr>";
			
			//$business_unit_name = $business_unit_row["business_unit_name"];
			//$business_unit_select .= "<option value = \"" . $business_unit_id . "\">" . $business_unit_name . "</option>";
			
	}
}else{
	$pif_report_table .= "<tr><td colspan = \"7\">No results for this query</td></tr>";
}
$pif_grand_total = $planned_grand_total + $unplanned_grand_total + $compliance_grand_total;
$pif_report_table .= "<tr><td align = \"right\"><b>Totals:</b></td><td align = \"right\"><b>" . $planned_grand_total . "</b></td><td align = \"right\"><b>" . $unplanned_grand_total . "</b></td><td align = \"right\"><b>" . $compliance_grand_total . "</b></td><td align = \"right\"><b>" . $pif_grand_total . "</b></td></tr>";
$pif_report_table .= "</table></form>";


$pif_asset_table = "<table class = \"stats_table\" width = \"70%\"><tr><th colspan = \"5\">Project Brief Asset Counts " . $date_range_message . "</th></tr><tr><th>Project Brief Asset Type</th><th>planned</th><th>un-planned</th><th>compliance</th><th>total</th></tr>";
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
		$pif_asset_table .= "<tr>";
		$pif_asset_table .= "<td>" . $pif_asset_type_name . "</td>";
		$pif_asset_table .= "<td align = \"right\" width = \"80\">" .  $pif_asset_count_planned. "</td>";
		$pif_asset_table .= "<td align = \"right\" width = \"80\">" .  $pif_asset_count_unplanned. "</td>";
		$pif_asset_table .= "<td align = \"right\" width = \"80\">" .  $pif_asset_count_compliance. "</td>";
		$pif_asset_table .= "<td align = \"right\" width = \"80\">" .  $total. "</td>";
		$pif_asset_table .= "</tr>";
		
		$grand_total_pif_asset_count_planned = $grand_total_pif_asset_count_planned + $pif_asset_count_planned;
		$grand_total_pif_asset_count_unplanned = $grand_total_pif_asset_count_unplanned + $pif_asset_count_unplanned;
		$grand_total_pif_asset_count_compliance = $grand_total_pif_asset_count_compliance + $pif_asset_count_compliance;
		
	}
}else{
	$pif_asset_table .= "<tr><td colspan = \"7\">No results for this query</td></tr>";
}
$pif_asset_count_grand_total = $grand_total_pif_asset_count_planned  + $grand_total_pif_asset_count_unplanned + $grand_total_pif_asset_count_compliance;

$pif_asset_table .= "<tr><td align = \"right\"><b>Totals:</b></td><td align = \"right\"><b>" . $grand_total_pif_asset_count_planned . "</b></td><td align = \"right\"><b>" . $grand_total_pif_asset_count_unplanned . "</b></td><td align = \"right\"><b>" . $grand_total_pif_asset_count_compliance . "</b></td><td align = \"right\"><b>" . $pif_asset_count_grand_total . "</b></td></tr>";

$pif_asset_table .= "</table>";
?>
<html>
<head>
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Project Brief Count Report</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

  <script>
$(document).ready(function(){
    
	$( ".datepicker" ).datepicker();
	
	$( "#pif_form" ).validate({
	  rules: {
		start_date: {
			required: true
		},
		end_date: {
			required: true
		}

	  }
	});

	$( ".datepicker" ).change(function() {
	  $('#date_choice_choose').prop('checked',true);
	});
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
				<h1>Project Brief Count Report</h1>
				<form id = "pif_form" action = "pif_count_report.php" method = "GET">
				<table class = "small_link" width = "30%">
					<tr>
						<td colspan = "3">
						<input type = "radio" name = "date_choice" value = "current" <?php echo $current_checked ?> id = "date_choice_current">Current &nbsp;&nbsp;&nbsp;&nbsp;<input type = "radio" name = "date_choice" value = "choose" <?php echo $choose_checked ?>  id = "date_choice_choose">Choose Dates
						</td>
					</tr>
					<tr>
						<td>From: <input type = "text" name = "start_date" class="datepicker" size = "8" id = "start_date" value = "<?php echo $start_date_field?>"></td>
						<td>To: <input type = "text" name = "end_date" class="datepicker" size = "8" id = "end_date" value = "<?php echo $end_date_field?>"></td>
						<td><input type = "submit" value = "go"></td>
						
					</tr>
				</table>
				</form>
				<?php echo $pif_report_table ?>
				<br>
				<?php echo $pif_asset_table ?>
				<form action = "export_pif_count_report_csv.php" method="POST">
				<input type = "hidden" name = "date_choice" value = "<?php echo $date_choice ?>">
				<input type = "hidden" name = "start_date" value = "<?php echo $start_date ?>">
				<input type = "hidden" name = "end_date" value = "<?php echo $end_date ?>">
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