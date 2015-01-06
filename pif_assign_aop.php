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
		$error_message = "Project Briefs updated.";
	}
}

if (!empty($_GET["s"])){
	$pif_approval_status_id = $_GET["s"];
}else{
	$pif_approval_status_id = 1;
}

$arr_pifs = get_pifs($company_id, $pif_approval_status_id);
$pif_table = "<table class = \"stats_table\" width = \"100%\"><tr><th>Project Brief Code</th><th>Project Brief Project Name</th><th>Version</th><th>Created</th><th>Submitted By</th><th>Status</th><th>Planned</th><th>Unplanned</th><th>Compliance</th>";

if (!empty($arr_pifs)){
	foreach ($arr_pifs as $pif_row){
			$pif_id = $pif_row["pif_id"];
			$pif_code = $pif_row["pif_code"];
			$pif_project_name = $pif_row["pif_project_name"];
			$status = $pif_row["pif_approval_status_name"];
			$version = $pif_row["version"];
			$created_date = $pif_row["created_date"];
			$project_code = "n/a";
			$orig_pif_id = $pif_row["orig_pif_id"];
			$aop_activity_type_id = $pif_row["aop_activity_type_id"];
			$planned_checked = "";
			$unplanned_checked = "";
			$compliance_checked = "";
			if ($aop_activity_type_id == 1){
				$planned_checked = "checked";
			}
			
			if ($aop_activity_type_id == 2){
				$unplanned_checked = "checked";
			}
			
			if ($aop_activity_type_id == 3){
				$compliance_checked = "checked";
			}
			
			$project_id = $pif_row["project_id"];
			$submitted_by = $pif_row["requester_first_name"] . " " . $pif_row["requester_last_name"];
			//$campaign_code = $business_unit_abbrev . "-" . $campaign_quarter . substr($campaign_year,2,3);
			$pif_table .= "<tr><td><a href = \"view_pif.php?p=" . $pif_id . "\" target = \"_blank\">" . $pif_code . "</a></td>";
			$pif_table .= "<td>" . $pif_project_name . "</td>";
			$pif_table .= "<td>" . $version . "</td>";
			$pif_table .= "<td>" . convertMySQLdatetime_to_PHP($created_date) . "</td>";
			$pif_table .= "<td>" . $submitted_by . "</td>";
			$pif_table .= "<td>" .  $status  . "</td>";
			$pif_table .= "<td align=\"center\"><input type = \"radio\" name = \"aop_" . $pif_id . "\" value = \"1\" " . $planned_checked . "></td>";
			$pif_table .= "<td align=\"center\"><input type = \"radio\" name = \"aop_" . $pif_id . "\" value = \"2\" " . $unplanned_checked . "></td>";
			$pif_table .= "<td align=\"center\"><input type = \"radio\" name = \"aop_" . $pif_id . "\" value = \"3\" " . $compliance_checked . "></td>";
			$pif_table .= "</tr>";
			
			//add history table row
			//leaving this here in case they want to re-add it later.
			$arr_pif_history = get_pif_history($orig_pif_id);
			$history_section = "<tr class = \"p-" . $pif_id . "-history\" style=\"display: none;\"><td class = \"home_white\">&nbsp;</td><td class = \"home_spend\"><b>Date</b></td><td class = \"home_spend\" colspan = \"2\"><b>Log Notes</b></td><td class = \"home_spend\" colspan = \"4\"><b>Approver Notes</b></td><td class = \"home_spend\" colspan = \"1\"><b>Submitted By</b></td></tr>";
			if (!empty($arr_pif_history)){
				foreach ($arr_pif_history as $pif_history_row){
					$pif_log_id = $pif_history_row["pif_log_id"];
					$pif_log_notes = $pif_history_row["pif_log_notes"];
					$pif_approver_notes = $pif_history_row["approver_notes"];
					$pif_approver_initials = $pif_history_row["approver_initials"];
					$pif_approver_name = $pif_history_row["approver_first_name"] . " " . $pif_history_row["approver_last_name"];
					$pif_log_created = convertMySQLdatetime_to_PHP($pif_history_row["pif_log_created"]);
					$history_section .= "<tr class = \"p-" . $pif_id . "-history\" style=\"display: none;\"><td class = \"home_white\">&nbsp;</td><td class = \"home_spend\">" . $pif_log_created . "</td><td class = \"home_spend\" colspan = \"2\">" . $pif_log_notes . "</td><td class = \"home_spend\" colspan = \"4\">" . $pif_approver_notes . "</td><td class = \"home_spend\" colspan = \"1\">" . $pif_approver_name . "</td></tr>";
				}	
			}else{
				$history_section .= "<tr class = \"p-" . $pif_id . "-history\" style=\"display: none;\"><td>&nbsp;</td><td>no history logged</td></tr>";
			}
			//$pif_table .= $history_section ;
	}
}else{
	$pif_table .= "<tr><td colspan = \"7\">No results for this query</td></tr>";
}
$pif_table .= "</table>";

$pif_status_select = get_pif_status_select($company_id, $pif_approval_status_id);

//print $pif_approval_status_id;
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Project Brief Assign AOP</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
  <script>
  $(document).ready(function(){
    $("#campaign_form").validate();
	
	$('.history_click').click(function() {
		var click_id = $(this).attr("id");
		var toggle_section = '.' + click_id + '-history';
		//alert(toggle_section);
		$(toggle_section).toggle();
		return false;
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
				<h1>Project Brief Assign AOP</h1>
				<table class = "small_link" width = "100%" border = "0">
					<tr><form id = "get_pifs" action = "pif_assign_aop.php" method = "GET">
						<td>Status: <?php echo $pif_status_select ?></td>
						</form>
					</tr>
				</table>
				<div class = "error"><?php echo $error_message ?></div>
				<form action = "update_pif_aop_status.php" method = "POST">
				<?php echo $pif_table ?>
				<table width = "100%">
					<tr>
						<td align = "right">
							<input type = "hidden" name = "pif_approval_status_id" value = "<?php echo $pif_approval_status_id ?>">
							<input type = "submit" value = "update">
						</td>
					</tr>
				</table>
				</form>
				<br>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?>
	</div>
</div>
</body>
</html>