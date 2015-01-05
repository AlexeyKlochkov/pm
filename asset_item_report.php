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
$start_month_year = $current_year . "-" . $current_month . "-01";
$end_month_year = $start_month_year;
$end_month_date = $start_month_year;

$campaign_id = "";
if (!empty($_GET["campaign_id"])){
	$campaign_id = $_GET["campaign_id"];
}

if (!empty($_GET["start_month"])){
	$start_month_year = $_GET["start_month"];
}

if (!empty($_GET["end_month"])){
	$end_month_year = $_GET["end_month"];
	 //$end_month_year;
	$end_month_date = substr($end_month_year, 0, -2) . "31";
	//print $end_month_date;
}

if (!empty($_GET["has_ge"])){
	$has_ge_checked = "checked";
	$has_ge = 1;
}else{
	$has_ge_checked = "";
	$has_ge = 0;
}

$asset_type_category_id = "";
if (!empty($_GET["asset_type_category_id"])){
	$asset_type_category_id = $_GET["asset_type_category_id"];
}

$export = 0;
if (!empty($_GET["export"])){
	$export = 1;
}

if (!empty($_GET["location_list"])){
	$location_list = $_GET["location_list"];
}else{
	$location_list = "All";
}

$vendor_other_id = get_vendor_other_id($company_id);

//$quarter_select = get_quarter_select($campaign_quarter);

$campaign_select = get_campaign_code_select($company_id, $campaign_id );
$campaign_select = str_replace("Please select", "All", $campaign_select );
$zip_file_list = "";
$arr_asset_report = get_asset_item_report_for_range($campaign_id, $start_month_year , $end_month_date, $has_ge, $asset_type_category_id, $location_list);
//print_r($arr_campaigns );
$asset_table = "<table class = \"stats_table\" width = \"100%\"><tr><th>#</th><th>AOP</th><th>Project</th><th>Asset Type</th><th>Asset Item Name</th><th>In Maket Date</th><th>Expiration Date</th><th>Has GE</th><th width = \"200\">Locations</th><th>File Folder</th><th>Link</th></tr>";
$asset_table .= "<form action = \"update_spend_posted.php\" method = \"POST\">";
if (!empty($arr_asset_report)){
	foreach ($arr_asset_report as $asset_item_row){
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
				$file_link = "<a href = \"project_files/" . $project_code . "/" . $project_file_name . "\" target = \"_blank\">file</a>\n";
				$zip_file_list .= "'project_files/" . $project_code . "/" . $project_file_name . "',";
			}else{
				$file_link = "&nbsp;";
			}
			

					
			$asset_table .= "<tr>";
			$asset_table .= "<td align = \"right\" valign=\"top\">" . $asset_item_id . "</td>";
			$asset_table .= "<td valign=\"top\">" . $business_unit_name . " (" . $campaign_code . ")</td>";
			$asset_table .= "<td valign=\"top\">" . $project_code . "</td>";
			$asset_table .= "<td valign=\"top\">" . $asset_type_category_name . " - " . $asset_type_name . "</td>";
			$asset_table .= "<td valign=\"top\">" . $asset_item_name . "</td>";
			$asset_table .= "<td valign=\"top\" align=\"right\">" . convert_mysql_to_datepicker($asset_item_in_market_date) . "</td>";
			$asset_table .= "<td valign=\"top\" align=\"right\">" . convert_mysql_to_datepicker($asset_item_expiration_date) . "</td>";
			$asset_table .= "<td valign=\"top\">" . $asset_item_has_ge_string . "</td>";
			$asset_table .= "<td width = \"200\" valign=\"top\">" . $asset_item_states . "</td>";
			$asset_table .= "<td width = \"150\" valign=\"top\">" . $file_network_folder . "</td>";
			$asset_table .= "<td valign=\"top\">" . $file_link . "</td>";
			$asset_table .= "</tr>";
			
			//$business_unit_name = $business_unit_row["business_unit_name"];
			//$business_unit_select .= "<option value = \"" . $business_unit_id . "\">" . $business_unit_name . "</option>";
			
	}
}else{
	$asset_table .= "<tr><td colspan = \"7\">No results for this query</td></tr>";
}
$asset_table .= "</tr><input type = \"hidden\" name = \"campaign_id\" value = \"" . $campaign_id . "\"><input type = \"hidden\" name = \"start_month\" value = \"" . $start_month_year . "\"><input type = \"hidden\" name = \"end_month\" value = \"" . $end_month_year . "\">";
$asset_table .= "</table></form>";

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

$spend_start_month_select = get_spend_month_select2($company_id, $start_month_year, "start_month");
$spend_end_month_select = get_spend_month_select2($company_id, $end_month_year, "end_month");
$asset_type_category_select = get_asset_type_category_select($company_id, $asset_type_category_id);
$asset_type_category_select = str_replace("Please Select","All",$asset_type_category_select);

$arr_states = get_states();
$states_table = "<table class = \"stats_table\" width = \"100%\" border = \"1\"><td>NAT<br><input type = \"checkbox\" name = \"chk_NAT\" value = \"1\" class = \"state_checkbox\" id = \"NAT\"></td>\n";
//print $location_list . "<br>";
if(!empty($arr_states)){
	foreach ($arr_states as $state_row){
		$state_is_checked = "";
		$state_id = $state_row["state_id"];
		$state_name = $state_row["state_name"];
		$state_abbrev = $state_row["state_abbrev"];
		$abbrev_position = strpos($location_list, $state_abbrev);
		//if it's the first item in the list, it's returning zero, which translates as empty. So this counters it.
		if ($abbrev_position === 0){
			$abbrev_position = 1;
		}
		//print $state_abbrev . "--" . $abbrev_position  . "<br>";
		
		if(!empty($location_list)){
			if (!empty($abbrev_position)){
				$state_is_checked = "checked";
				//print "Checked!<br>";
			}
		}
		$states_table .= "<td>" . $state_abbrev  . "<br><input type = \"checkbox\" name = \"chk_" . $state_abbrev  . "\" value = \"1\" class = \"state_checkbox\" id = \"" . $state_abbrev . "\" " . $state_is_checked . "></td>\n";
		if ($state_abbrev == "MO"){
			$states_table .= "</tr><tr>";
		}
	}
}
$states_table .= "</tr></table>";
$zip_file_list = substr($zip_file_list, 0, -1);
//print $zip_file_list ;
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Asset Item Report</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
  <script>
  $(document).ready(function(){
	$('#location_click').click(function() {
		$('#state_list').toggle();
		return false;
	});
	
	$('.state_checkbox').change(function() {
		var current_state_id = $(this).attr("id");
		//alert(current_state_id);
		var is_checked = this.checked;
		var current_location_list = $('#location_list').text();
		//alert(current_location_list);
		if(is_checked){
			if(current_location_list == "All"){
				$('#location_list').text(current_state_id);
			}else{
				$('#location_list').text(current_location_list + ", " + current_state_id);
			}
		}else{
			var new_location_list = current_location_list.replace(current_state_id + ", ", "");
			var new_location_list = new_location_list.replace(", " + current_state_id, "");
			var new_location_list = new_location_list.replace(current_state_id, "");
			$('#location_list').text(new_location_list);
		}
		$('#location_list_hidden').val($('#location_list').text());
		$('#location_list_hidden2').val($('#location_list').text());
		//alert($('#location_list_hidden2').val());
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
				<h1>Asset Item Report</h1>

				<table class = "small_link" width = "100%" border = "0">
					<tr><form id = "sow_report" action = "asset_item_report.php" method = "GET">
						<td>Active AOP Budgets:<br><?php echo $campaign_select ?></td>
						<td width = "150"><a href = "#" id = "location_click">Locations</a>:<br><div id = "location_list"><?php echo $location_list ?></div>
								<input type = "hidden" name = "location_list" id = "location_list_hidden" value = "<?php echo $location_list ?>"></td>
						<td>In Market From:<br><?php echo $spend_start_month_select ?></td>
						<td>Through:<br><?php echo $spend_end_month_select ?></td>
						<td>Asset Category:<br><?php echo $asset_type_category_select ?></td>
						<td><input type = "checkbox" name = "has_ge" <?php echo $has_ge_checked ?> value = "1"> Has GE</td>
						<td><input type = "submit" value = "go"></td>
						</form>
					</tr>
					<tr>
						<td colspan = "7"><div id = "state_list" style = "display:none;"><?php echo $states_table?></div></td>
					</tr>
				</table>
				<?php echo $asset_table ?>
				<br>
				<form action = "export_asset_item_report_csv.php" method="POST">
				<input type = "hidden" name = "campaign_id" value = "<?php echo $campaign_id ?>">
				<input type = "hidden" name = "start_month" value = "<?php echo $start_month_year ?>">
				<input type = "hidden" name = "end_month" value = "<?php echo $end_month_year ?>">
				<input type = "hidden" name = "asset_type_category_id" value = "<?php echo $asset_type_category_id ?>">
				<input type = "hidden" name = "has_ge" value = "<?php echo $has_ge ?>">
				<input type = "hidden" name = "location_list" id = "location_list_hidden2" value = "<?php echo $location_list ?>">
				<input type = "submit" value = "Export CSV File">
				</form>

<?php
if(!empty($zip_file_list)){
?>
				<form action = "export_asset_item_files_zip.php" method = "POST">
				<input type = "hidden" name = "zip_file_list" value = "<?php echo $zip_file_list ?>">
				<input type = "submit" value = "Save All Final Files">
				</form>
<?php
}
?>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>