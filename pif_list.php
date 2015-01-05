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
		$error_message = "An Error Occurred.";
	}
		if ($error_num == 2){
		$error_message = "Project Brief Status Udated.";
	}
		if ($error_num == 3){
		$error_message = "New project created.";
	}
}


if (!empty($_GET["s"])){
	$pif_approval_status_id = $_GET["s"];
}else{
	$pif_approval_status_id = 1;
}

if (!empty($_GET["sb"])){
	$sortby = $_GET["sb"];
}else{
	$sortby = "p.pif_code";
}

if (!empty($_GET["ascdesc"])){
	$ascdesc = $_GET["ascdesc"];
}else{
	$ascdesc = "asc";
}

//$quarter_select = get_quarter_select($campaign_quarter);
$arr_pifs = get_pifs_with_sort($company_id, $pif_approval_status_id, $sortby, $ascdesc);
$ascdesc_new="";
//swap ascdesc for next click
if($ascdesc == "asc"){
	$ascdesc_new = "desc";
}
if($ascdesc == "desc"){
	$ascdesc_new = "asc";
}
if(empty($_GET["ascdesc"])){
	$ascdesc_new = "asc";
}

//print_r($arr_campaigns );
$pif_table  = "<form action = \"update_pif_rank.php\" method = \"POST\"><table class = \"stats_table\" width = \"100%\"><tr>";
if($pif_approval_status_id == 6){
	$pif_table .= "<th><a href = \"pif_list.php?s=" . $pif_approval_status_id . "&sb=p.pif_rank&ascdesc=asc\">Rank</a></th>";
}
$pif_table .= "<th><a href = \"pif_list.php?s=" . $pif_approval_status_id . "&sb=p.pif_code&ascdesc=" . $ascdesc_new . "\">Project Brief Code</a></th>";
$pif_table .= "<th><a href = \"pif_list.php?s=" . $pif_approval_status_id . "&sb=p.pif_project_name&ascdesc=" . $ascdesc_new . "\">Project Brief Project Name</a></th>";
$pif_table .= "<th><a href = \"pif_list.php?s=" . $pif_approval_status_id . "&sb=p.version&ascdesc=" . $ascdesc_new . "\">Version</a></th>";
$pif_table .= "<th><a href = \"pif_list.php?s=" . $pif_approval_status_id . "&sb=p.created_date&ascdesc=" . $ascdesc_new . "\">Created</a></th>";
$pif_table .= "<th><a href = \"pif_list.php?s=" . $pif_approval_status_id . "&sb=u1.last_name&ascdesc=" . $ascdesc_new . "\">Submitted By</a></th>";
$pif_table .= "<th><a href = \"pif_list.php?s=" . $pif_approval_status_id . "&sb=marketing_owner_last_name&ascdesc=" . $ascdesc_new . "\">Mktg Owner</a></th>";
$pif_table .= "<th><a href = \"pif_list.php?s=" . $pif_approval_status_id . "&sb=pas.pif_approval_status_name&ascdesc=" . $ascdesc_new . "\">Status</a></th>";
$pif_table .= "<th><a href = \"pif_list.php?s=" . $pif_approval_status_id . "&sb=aop.aop_activity_type_name&ascdesc=" . $ascdesc_new . "\">AOP Type</a></th>";
$pif_table .= "<th>Project</th>";
$pif_table .= "<th colspan = \"3\">&nbsp;</th>";

$num_pifs = 0;
$n=0;
if (!empty($arr_pifs)){
	$num_pifs = count($arr_pifs);
	//print $num_pifs ;
	foreach ($arr_pifs as $pif_row){
			$n++;
			//print $n;
			$pif_id = $pif_row["pif_id"];
			$pif_code = $pif_row["pif_code"];
			$pif_project_name = $pif_row["pif_project_name"];
			$status = $pif_row["pif_approval_status_name"];
			$version = $pif_row["version"];
			$created_date = $pif_row["created_date"];
			$project_code = "n/a";
			$orig_pif_id = $pif_row["orig_pif_id"];
             $pif_rank = $pif_row["pif_rank"];
			
			$marketing_owner_last_name = $pif_row["marketing_owner_last_name"];
			$aop_activity_type_name = $pif_row["aop_activity_type_name"];
			$project_id = $pif_row["project_id"];
			if(!empty($project_id)){
				$project_code = get_project_code($project_id);
				$project_code = "<a href = \"manage_project.php?p=" . $project_id . "\">" . $project_code . "</a>";
			}
			
			$submitted_by = $pif_row["requester_first_name"] . " " . $pif_row["requester_last_name"];
			//$campaign_code = $business_unit_abbrev . "-" . $campaign_quarter . substr($campaign_year,2,3);

			$pif_table .= "<tr>";
			if($pif_approval_status_id == 6){
				$pif_up_arrow = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				if ($pif_rank <> 1){
					$pif_up_arrow = "<a href = \"move_pif_rank.php?p=" . $pif_id . "&rank=". $pif_rank . "&dir=up\"><img src = \"images/arrow_up.png\" border=\"0\"></a>";
				}
				$pif_down_arrow = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				if ($n <> $num_pifs){
					$pif_down_arrow = "<a href = \"move_pif_rank.php?p=" . $pif_id . "&rank=". $pif_rank . "&dir=down\"><img src = \"images/arrow_down.png\" border=\"0\"></a>";
				}
				
				if($sortby<>"p.pif_rank"){
					$pif_up_arrow = "";
					$pif_down_arrow = "";
				}
				
				$pif_table .= "<td align=\"right\"><table border = \"0\"><tr><td>" . $pif_up_arrow . "</td><td>" . $pif_down_arrow . "</td><td><input type = \"text\" name = \"pif_rank-" . $pif_id . "\" value = \"" . $pif_rank . "\" class=\"pif_rank\" size=\"2\"></td></tr></table></td>";
			}
			
			$pif_table .= "<td><a href = \"view_pif.php?p=" . $pif_id . "\">" . $pif_code . "</a></td>";
			
			$pif_table .= "<td>" . $pif_project_name . "</td>";
			//$pif_table .= "<td>" . $campaign_quarter . "</td>";
			$pif_table .= "<td>" . $version . "</td>";
			$pif_table .= "<td>" . convertMySQLdatetime_to_PHP($created_date) . "</td>";
			$pif_table .= "<td>" . $submitted_by . "</td>";
			$pif_table .= "<td>" . $marketing_owner_last_name . "</td>";
			$pif_table .= "<td>" .  $status  . "</td>";
			$pif_table .= "<td>" .  $aop_activity_type_name  . "</td>";
			$pif_table .= "<td>" .  $project_code  . "</td>";
			if ($_SESSION["user_level"] >20){
			$pif_table .= "<td><a href = \"view_pif.php?p=" .  $pif_id  . "\">Approval</a></td>";
			$pif_table .= "<td><a href = \"edit_pif.php?pid=" .  $pif_id  . "\">Modify</a></td>";
			
			}
			$pif_table .= "<td><a id = \"p-" . $pif_id . "\" href = \"#\" class=\"history_click\">history</a></td>";
			$pif_table .= "</tr>";
			
			//add history table row
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
			$pif_table .= $history_section ;
			
	}
}else{
	$pif_table .= "<tr><td colspan = \"7\">No results for this query</td></tr>";
}

if($pif_approval_status_id == 6){
	$pif_table .= "<tr><td align=\"right\"><input type = \"submit\" value = \"set rank\"></td><td colspan = \"12\">&nbsp<input type = \"hidden\" name = \"ascdesc\" value = \"" . $ascdesc . "\"><input type = \"hidden\" name = \"sortby\" value = \"" . $sortby . "\"></td>";
}
$pif_table .= "</table></form>";

$pif_status_select = get_pif_status_select($company_id, $pif_approval_status_id);

//print $pif_approval_status_id;
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Project Briefs List</title>
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
				<a href="index.php"><img src = "logo.png"></a>
			</div>

		<?php 
		include "nav1.php";
		?> 
		<!--container div tag--> 
		<div id="container"> 
			
			<div id="mainContent"> <!--mainContent div tag--> 
				<h1>Project Briefs List</h1>
				<table class = "small_link" width = "100%" border = "0">
					<tr><form id = "get_pifs" action = "pif_list.php" method = "GET">
						<td>Status: <?php echo $pif_status_select ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href = "new_aop.php">New Project Brief</a></td>
						</form>
					</tr>
				</table>
				<div class = "error"><?php echo $error_message ?></div>
				<?php echo $pif_table ?>
				<br>
				<a href = "export_pif_list.php?s=<?php echo $pif_approval_status_id ?>&sb=<?php echo $sortby ?>&ascdesc=<?php echo $ascdesc ?>">export</a>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>