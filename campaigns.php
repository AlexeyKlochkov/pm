<?php 
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";
//print $company_id;
$error_message = "";

if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "Please select a business unit.";
	}
		if ($error_num == 2){
		$error_message = "Duplicate campaign for the chosen business unit and year.";
	}
}

$business_unit_id = "";
if (!empty($_GET["business_unit_id"])){
	$business_unit_id = $_GET["business_unit_id"];
}

$campaign_id = "";
if (!empty($_GET["campaign_id"])){
	$campaign_id = $_GET["campaign_id"];
}

$campaign_quarter = "";
if (!empty($_GET["campaign_quarter"])){
	$campaign_quarter = $_GET["campaign_quarter"];
}
$orig_quarter = $campaign_quarter;
$campaign_year = "";
if (!empty($_GET["campaign_year"])){
	$campaign_year = $_GET["campaign_year"];
}
$orig_year = $campaign_year;
$active = 1;
if (!empty($_GET["active"])){
	$active = $_GET["active"];
}

$export = 0;
if (!empty($_GET["export"])){
	$export = 1;
}

//$quarter_select = get_quarter_select($campaign_quarter);
$year_select = get_year_select($campaign_year);
$business_unit_select = get_business_unit_select($company_id, $business_unit_id);
$business_unit_select = str_replace("Please select", "All", $business_unit_select );
$campaign_select = get_campaign_code_select($company_id, $campaign_id );
$campaign_select = str_replace("Please select", "All", $campaign_select );

$arr_campaigns = get_campaign_query($company_id, $campaign_id, $business_unit_id, $campaign_quarter, $campaign_year, $active);
//print_r($arr_campaigns );
$campaign_table = "<table class = \"stats_table\" width = \"100%\"><tr><th>AOP Code</th><th>Line of Business</th><th>Year</th><th>Description</th><th>Overall Budget</th><th>Current Spend</th>";

if ($_SESSION["user_level"] > 30){
	$campaign_table .= "<th>Work Effort</th>";
}
$campaign_table .= "<th colspan=\"2\">&nbsp;</th></tr>";

if (!empty($arr_campaigns)){
	foreach ($arr_campaigns as $campaign_row){
			$current_campaign_id = $campaign_row["campaign_id"];
			$total_campaign_spend = get_total_spend_by_campaign($current_campaign_id);
			$total_campaign_soft_cost = get_soft_cost_by_campaign($current_campaign_id);
			$total_soft_cost = 0;
			$business_unit_name = $campaign_row["business_unit_name"];
			$business_unit_abbrev = $campaign_row["business_unit_abbrev"];
			$campaign_quarter = $campaign_row["campaign_quarter"];
			$campaign_year = $campaign_row["campaign_year"];
			$campaign_code = $campaign_row["campaign_code"];
			//$campaign_code = $business_unit_abbrev . "-" . $campaign_quarter . substr($campaign_year,2,3);
			$campaign_description = $campaign_row["campaign_description"];
			$campaign_budget = add_commas($campaign_row["campaign_budget"]);
			$campaign_table .= "<tr><td><a href = \"projects.php?run=1&campaign_id=" . $current_campaign_id . "\">" . $campaign_code . "</a></td>";
			
			$campaign_table .= "<td>" . $business_unit_name . "</td>";
			//$campaign_table .= "<td>" . $campaign_quarter . "</td>";
			$campaign_table .= "<td>" . $campaign_year . "</td>";
			$campaign_table .= "<td>" . $campaign_description . "</td>";
			$campaign_table .= "<td align = \"right\">" . $campaign_budget . "</td>";
			$campaign_table .= "<td align = \"right\">" .  add_commas($total_campaign_spend) . "</td>";
			if ($_SESSION["user_level"] > 30){
				$campaign_table .= "<td align = \"right\">" .  add_commas($total_campaign_soft_cost) . "</td>";
			}
			if ($_SESSION["user_level"] > 10){
				$campaign_table .= "<td align = \"left\"><a href = \"edit_campaign.php?c=" . $current_campaign_id . "\">edit</a></td>";
			}
			$campaign_table .= "<td align = \"left\"><a href = \"projects.php?run=1&campaign_id=" . $current_campaign_id . "\">projects</a></td>";
			$campaign_table .= "</tr>";
			
			//$business_unit_name = $business_unit_row["business_unit_name"];
			//$business_unit_select .= "<option value = \"" . $business_unit_id . "\">" . $business_unit_name . "</option>";
			
	}
}else{
	$campaign_table .= "<tr><td colspan = \"7\">No results for this query</td></tr>";
}
$campaign_table .= "</table>";


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

$archive_select = "<select name = \"active\">\n";
$archive_select .= "<option value = \"1\"" . $active_checked . ">Active</option>\n";
$archive_select .= "<option value = \"2\"" . $archived_checked . ">Archived</option>\n";
$archive_select .= "<option value = \"3\"" . $all_checked . ">All</option>\n";
$archive_select .= "</select>\n";


?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Campaigns</title>
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
				<h1>Line of Business AOP Budgets</h1>
				<table width = "100%" class = "small_link">
					<tr>
						<td align = "right">
							<a href = "new_campaign.php">Add AOP Budget</a>
						</td>
					</tr>
				</table>
				<table class = "small_link" width = "100%">
					<tr><form id = "get_campaigns" action = "campaigns.php" method = "GET">
						<td width = "30%">&nbsp;</td>
						<td>Active AOP Budgets:<br><?php echo $campaign_select ?></td>
						<td>Business Unit:<br><?php echo $business_unit_select ?></td>
						<td>Year:<br><?php echo $year_select ?></td>
						<td>Active?<br><?php echo $archive_select ?></td>
						<td><input type = "submit" value = "go"></td>
						</form>
					</tr>
				</table>
				<?php echo $campaign_table ?>
				<br>
				<form action = "export_campaign_csv.php" method="POST">
				<input type = "hidden" name = "campaign_id" value = "<?php echo $campaign_id ?>">
				<input type = "hidden" name = "business_unit_id" value = "<?php echo $business_unit_id ?>">
				<input type = "hidden" name = "year" value = "<?php echo $orig_year ?>">
				<input type = "hidden" name = "active" value = "<?php echo $active_flag ?>">
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