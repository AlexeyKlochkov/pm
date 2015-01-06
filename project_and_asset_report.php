<?php 
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";
//print $company_id;
$error_message = "";
//print $_SESSION["user_level"] ;
$error_num = 0;
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
$start_month_year = $current_year . "-" . $current_month . "-01";
$end_month_year = $start_month_year;

$campaign_id = "";
if (!empty($_GET["campaign_id"])){
	$campaign_id = $_GET["campaign_id"];
}

if (!empty($_GET["start_month"])){
	$start_month_year = $_GET["start_month"];
}

if (!empty($_GET["end_month"])){
	$end_month_year = $_GET["end_month"];
}


$export = 0;
if (!empty($_GET["export"])){
	$export = 1;
}

//get names of months for the header
$arr_start_month = explode("-", $start_month_year);
$start_month_abbrev = get_month_abbrev($arr_start_month[1]);
$start_year = $arr_start_month[0];
$start_month_year_title = $start_month_abbrev . "-" . $start_year;
$arr_end_month = explode("-", $end_month_year);
$end_month_abbrev = get_month_abbrev($arr_end_month[1]);
$end_year = $arr_end_month[0];
$end_month_year_title = $end_month_abbrev . "-" . $end_year;


if((strtotime($end_month_year) < strtotime($start_month_year))){
	$error_num = 1;
	$error_message = "Please select an end date after your start date...";
}

//for these month-based queries, take the end month and add one month to it and use a less-than.
$end_month_year_time = strtotime($end_month_year);
$end_month_for_query = date("Y-m-d", strtotime("+1 month", $end_month_year_time));
//print $end_month_for_query;

$arr_aop_counts_by_month = get_project_aop_counts_by_month($company_id, $start_month_year , $end_month_for_query);
$num_cancelled_projects = get_number_of_cancelled_proejcts($company_id, $start_month_year , $end_month_for_query);

$project_total = 0;
//get total project count from the array
if (!empty($arr_aop_counts_by_month)){
	foreach ($arr_aop_counts_by_month as $aop_row){
		$num_projects = $aop_row["num_projects"];
		$project_total = $project_total + $num_projects;
	}
}
//print $project_total;
if($error_num <> 0){
	$cancelled_percentage = "";
}else{
	if(!empty($project_total)){
		$cancelled_percentage = round((($num_cancelled_projects/$project_total)*100),0);
	}else{
		$cancelled_percentage = "";
	}
}
$total_table = "<table class = \"\" width = \"30%\"><tr><th align=\"left\">Total Projects PIF'd</th><td align=\"left\">" . $project_total . "</td></tr></table>";

$aop_table = "<br><table class = \"stats_table\" width = \"30%\"><tr><th>Activity Type</th><th>Project Count</th><th>Percentage</th>";
if (!empty($arr_aop_counts_by_month)){
	foreach ($arr_aop_counts_by_month as $aop_row){
		$aop_activity_type_name = $aop_row["aop_activity_type_name"];
		$num_projects = $aop_row["num_projects"];
		$project_percentage = round((($num_projects/$project_total)*100),0);
		$aop_table .= "<tr><td>" . $aop_activity_type_name . "</td><td align=\"right\">" . $num_projects . "</td><td align=\"right\">" . $project_percentage . "%</td></tr>\n";
	}
	$aop_table .= "<tr><td>Cancelled</td><td align=\"right\">" . $num_cancelled_projects . "</td><td align=\"right\">" . $cancelled_percentage . "%</td></tr>\n";
}else{
	$aop_table .= "<tr><td colspan = \"3\">No results for this query</td></tr>";
}

$aop_table .= "</table>";
$arr_projects_by_month = get_project_counts_by_month($company_id, $start_month_year , $end_month_for_query);
$projects_by_month_table = "<br><table class = \"stats_table\" width = \"30%\"><tr><th>Month</th><th>Project Count</th>";
if (!empty($arr_projects_by_month)){
	foreach ($arr_projects_by_month as $month_row){
		$year = $month_row["year"];
		$month = $month_row["month"];
		$num_projects = $month_row["num_projects"];
		$projects_by_month_table .= "<tr><td align=\"right\">" . $month . "/". $year . "</td><td align=\"right\">" . $num_projects . "</td></tr>\n";
	}
}else{
	$projects_by_month_table .= "<tr><td colspan = \"3\">No results for this query</td></tr>";
}

$projects_by_month_table .= "</table>";

$arr_asset_counts = get_asset_counts_by_month_range($company_id, $start_month_year , $end_month_for_query);
$total_assets = 0;
$asset_table = "<br><table class = \"stats_table\" width = \"30%\"><tr><th>Asset Type</th><th>Count</th>";
if (!empty($arr_asset_counts)){
	foreach ($arr_asset_counts as $asset_row){
		$asset_type_name = $asset_row["asset_type_name"];
		$asset_type_category_abbrev = $asset_row["asset_type_category_abbrev"];
		$num_assets = $asset_row["num_assets"];
		$asset_table .= "<tr><td align=\"left\">" . $asset_type_category_abbrev . ": " . $asset_type_name . "</td><td align=\"right\">" . $num_assets . "</td></tr>\n";
		$total_assets = $total_assets + $num_assets;
	}
	$asset_table .= "<tr><td align=\"right\"><b>Total</b></td><td align=\"right\"><b>" . $total_assets . "</b></td></tr>\n";
}else{
	$asset_table .= "<tr><td colspan = \"3\">No results for this query</td></tr>";
}

$asset_table .= "</table>";

//re-using these spend select menus because it should work okay...
$spend_start_month_select = get_spend_month_select2($company_id, $start_month_year, "start_month");
$spend_end_month_select = get_spend_month_select2($company_id, $end_month_year, "end_month");


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
				<table class = "small_link" width = "40%">
					<tr>
						<form id = "sow_report" action = "project_and_asset_report.php" method = "GET">
						<td>&nbsp;</td>
						<td>From:<br><?php echo $spend_start_month_select ?></td>
						<td>To:<br><?php echo $spend_end_month_select ?></td>
						<td><input type = "submit" value = "go"></td>
						</form>
					</tr>
				</table>
				<h1>Project Report <?php echo $start_month_year_title . " to " .  $end_month_year_title ?></h1>
				<div class = "error"><?php echo $error_message ?></div><br>
				<?php echo $total_table ?><br>
				AOP Counts
				<?php echo $aop_table ?><br><br>
				Project Counts by Month
				<?php echo $projects_by_month_table ?><br><br>
				Total # of Assets produced
				<?php echo $asset_table ?><br><br>

				<br>
				<!--
				<form action = "export_sow_report_csv.php" method="POST">
				<input type = "hidden" name = "campaign_id" value = "<?php echo $campaign_id ?>">
				<input type = "hidden" name = "start_month" value = "<?php echo $start_month_year ?>">
				<input type = "hidden" name = "end_month" value = "<?php echo $end_month_year ?>">
				<input type = "submit" value = "export">
				</form>
				-->
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?>
	</div>
</div>
</body>
</html>