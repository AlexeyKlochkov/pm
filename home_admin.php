<?php 
require_once "loggedin.php";
require_once "functions/dbconn.php";
require_once "functions/queries.php";
require_once "functions/functions.php";

$year_get = "";
$quarter_get = "";
$campaign_detail_quarter =  "";
$campaign_detail_year = "";

if (!empty($_GET["campaign_year"])){
	$year_get = $_GET["campaign_year"];
}
if (!empty($_GET["campaign_quarter"])){
	$quarter_get = $_GET["campaign_quarter"];
}

if (!empty($_GET["campaign_detail_year"])){
	$campaign_detail_year = $_GET["campaign_detail_year"];
}

if (!empty($_GET["campaign_detail_quarter"])){
	$campaign_detail_quarter = $_GET["campaign_detail_quarter"];
}

$year_select = get_year_select_style1($year_get);
$quarter_select = get_quarter_select_style1($quarter_get);

$vendor_other_id = get_vendor_other_id($company_id);

$campaign_stats_table = "<table class = \"stats_table\" width = \"100%\" ><th colspan = \"5\">AOP Line of Business</th><th>AOP Budget</th><th>Total Spend</th>";

if ($_SESSION["user_level"] > 30){
	$campaign_stats_table .= "<th>Work Effort Cost</th>";
}

$campaign_stats_table .= "<th>Remaining Budget</th></tr>";
$arr_active_campaign_totals = get_active_campaigns($company_id, $year_get);

if (!empty($arr_active_campaign_totals)){
	foreach ($arr_active_campaign_totals as $campaign_totals_row){
		//$quarter = $campaign_totals_row["campaign_quarter"];
		$campaign_id = $campaign_totals_row["campaign_id"];
		$business_unit_name = $campaign_totals_row["business_unit_name"];
		$business_unit_abbrev = $campaign_totals_row["business_unit_abbrev"];
		$year = $campaign_totals_row["campaign_year"];
		$total_budget = $campaign_totals_row["total_budget"];
		$num_projects = $campaign_totals_row["num_projects"];
		$campaign_total_spend = get_total_spend_by_campaign($campaign_id);
		$campaign_total_work_effort = get_work_effort_spend_per_campaign($company_id, $campaign_id);
		$remaining_budget = ($total_budget - $campaign_total_spend);
		$campaign_stats_table .= "<tr>";
		$campaign_stats_table .= "<td align = \"left\" colspan = \"3\" widht = \"30%\">" . $year . " - " . $business_unit_name . "</td>";
		$campaign_stats_table .= "<td align = \"left\" colspan = \"2\"><a id = \"c" . $campaign_id . "\" href = \"#\" class=\"campaign_project_click\">Projects (" . $num_projects . ")</a></td>";
		$campaign_stats_table .= "<td align = \"right\">" . add_commas($total_budget) . "</td>";
		$campaign_stats_table .= "<td align = \"right\">" . add_commas($campaign_total_spend) . "</td>";
		if ($_SESSION["user_level"] > 30){
			$campaign_stats_table .= "<td align = \"right\">" . add_commas($campaign_total_work_effort) . "</td>";
		}
		$campaign_stats_table .= "<td align = \"right\">" . add_commas($remaining_budget) . "</td>";
		$campaign_stats_table .= "</tr>";
		
		$arr_campaign_projects = get_campaign_projects($campaign_id);
			//print_r($arr_campaign_projects);
		if (!empty($arr_campaign_projects)){
			foreach ($arr_campaign_projects as $project_row){
				$project_id = $project_row["project_id"];
				$project_code= $project_row["project_code"];
				$project_name = $project_row["project_name"];
				$audience_name = $project_row["audience_name"];
				$pm_last_name = $project_row["pm_last_name"];
				$product_name = $project_row["product_name"];
				$media_budget= $project_row["media_budget"];
				$production_budget= $project_row["production_budget"];
				$total_project_spend = $project_row["total_spend"];
				$total_project_work_effort = get_work_effort_spend_per_project($project_id);
				$remaining_project_budget = (($media_budget + $production_budget) - $total_project_spend);
				$campaign_stats_table .= "<tbody class = \"c" . $campaign_id . "-projects\" style=\"display: none;\">";
				$campaign_stats_table .= "<tr>";
				$campaign_stats_table .= "<td class = \"home_white\" style=\"width:10px\">&nbsp;</td>";
				$campaign_stats_table .= "<td class = \"home_project\" align = \"left\" valign=\"top\" colspan = \"2\" width = \"20%\"><b>Project:</b> <a href = \"manage_project.php?p=" . $project_id . "\">" . $project_code . "</a><br>" .  $project_name . "</td>";
				$campaign_stats_table .= "<td class = \"home_project\" align = \"left\" valign=\"top\" colspan = \"2\"><b>Product:</b> " . $product_name . "<br><b>IPM:</b> " . $pm_last_name . "</td>";
				$campaign_stats_table .= "<td class = \"home_project\" align = \"right\" valign=\"top\"><b>Media Budget:</b> " . add_commas($media_budget) . "<br>";
				$campaign_stats_table .= "<b>Production Budget:</b> " . add_commas($production_budget) . "</td>";
				$campaign_stats_table .= "<td class = \"home_project\" align = \"right\" valign=\"top\">" . add_commas($total_project_spend) . "<br><a id = \"p" . $project_id . "\" href = \"#\" class=\"project_spend_click\">Details</a></td>";
				if ($_SESSION["user_level"] > 30){
					$campaign_stats_table .= "<td class = \"home_project\" align = \"right\" valign=\"top\">" . add_commas($total_project_work_effort) . "</td>";
				}
				$campaign_stats_table .= "<td class = \"home_project\" align = \"right\" valign=\"top\">" . add_commas($remaining_project_budget) . "</td>";
				$campaign_stats_table .= "</tr>\n";
				$arr_spend = get_spend_by_project($project_id);

				if (!empty($arr_spend)){
					//put in the Spend header row
					$campaign_stats_table .= "<tr class = \"p" . $project_id . "-spend\" style=\"display: none;\">";
					$campaign_stats_table .= "<td class = \"home_white\" style=\"width:10px\">&nbsp;</td>";
					$campaign_stats_table .= "<td class = \"home_white\" style=\"width:10px\">&nbsp;</td>";
					$campaign_stats_table .= "<td class = \"home_spend\" align=\"left\"><b>Spend</b></td>";
					$campaign_stats_table .= "<td class = \"home_spend\" align=\"left\"><b>Vendor</b></td>";
					$campaign_stats_table .= "<td class = \"home_spend\" align=\"left\"><b>Asset</b></td>";
					$campaign_stats_table .= "<td class = \"home_spend\" align=\"left\"><b>Type</b></td>";
					$campaign_stats_table .= "<td class = \"home_spend\" align=\"left\"><b>Amount</b></td>";
					$campaign_stats_table .= "<td class = \"home_spend\" align=\"left\" colspan=\"2\"><b>% Complete</b></td>";
					$campaign_stats_table .= "</tr>";
					foreach ($arr_spend as $spend_row){
						$spend_id = $spend_row["spend_id"];
						$asset_id = $spend_row["asset_id"];
						$asset_name = $spend_row["asset_name"];
						//if(empty($asset_name)){
						//	$asset_name = "n/a";
						//}
						$arr_spend_percent = get_max_spend_percent($spend_id);
						$percent_complete = "n/a";
						$spend_date = "n/a";
						if (!empty($arr_spend_percent)){

							$percent_complete = $arr_spend_percent[0]["spend_percent"] . "%";
							$spend_month_date = $arr_spend_percent[0]["spend_month"];
							$arr_spend_month_date = explode("-", $spend_month_date);
							$year = $arr_spend_month_date[0];
							$month_abbrev = get_month_abbrev($arr_spend_month_date[1]);
							$spend_date = $month_abbrev . "-" . $year;
							
						}

						$asset_type_name = $spend_row["asset_type_name"];
						$vendor_id = $spend_row["vendor_id"];
						$vendor_name = $spend_row["vendor_name"];
						$vendor_other = $spend_row["vendor_other"];
						if($vendor_id == $vendor_other_id){
							$vendor_name = $vendor_other;
						}
						$spend_type = $spend_row["spend_type"];
						$spend_notes = $spend_row["spend_notes"];
						$spend_amount = $spend_row["spend_amount"];
						$invoice_number = $spend_row["invoice_number"];
						$po_number = $spend_row["po_number"];
						//$percent_complete = $spend_row["percent_complete"];
						//if(empty($percent_complete)){
						//	$percent_complete = "";
						//}else{
						//	$percent_complete = "(" . $percent_complete . "%)";
						//}
						
						//$spend_date = $spend_row["spend_date"];
						$campaign_stats_table .= "<tr class = \"p" . $project_id . "-spend\" style=\"display: none;\">";
						$campaign_stats_table .= "<td class = \"home_white\" style=\"width:10px\">&nbsp;</td>";
						$campaign_stats_table .= "<td class = \"home_white\" style=\"width:10px\">&nbsp;</td>";
						$campaign_stats_table .= "<td class = \"home_spend\" align=\"left\">" . $spend_notes . "</td>";
						$campaign_stats_table .= "<td class = \"home_spend\">" . $vendor_name . "</td>";
						$campaign_stats_table .= "<td class = \"home_spend\">" . $asset_name . "</td>";
						$campaign_stats_table .= "<td class = \"home_spend\" align=\"left\">" . $spend_type . "</td>";
						$campaign_stats_table .= "<td class = \"home_spend\" align=\"right\">" . $spend_amount . "</td>";
						$campaign_stats_table .= "<td class = \"home_spend\" align=\"left\" colspan = \"2\">" . $percent_complete . "</td>";
						$campaign_stats_table .= "</tr>\n";
					}
				}else{
					$campaign_stats_table .= "<tr class = \"p" . $project_id . "-spend\" style=\"display: none;\">";
					$campaign_stats_table .= "<td class = \"home_white\" style=\"width:10px\">&nbsp;</td>";
					$campaign_stats_table .= "<td class = \"home_white\" style=\"width:10px\">&nbsp;</td>";
					$campaign_stats_table .= "<td class = \"home_spend\" colspan = \"7\">No spend for this project.";
					$campaign_stats_table .= "</td>";
					$campaign_stats_table .= "</tr>\n";
				}
			$campaign_stats_table .= "</tbody>\n";
			}
		}
		
	}
}else{
	$campaign_stats_table .= "<tr><td colspan=\"2\">No current campaigns</td></tr>";
}
$campaign_stats_table .= "</table>";

$pm_list_table = "<div id = \"pm_projects\" style=\"display: none;\"><table class = \"stats_table\" width = \"100%\"><th>PM</th><th>Num Projects</th></tr>";
$arr_pm_list = get_counts_by_pm($company_id);

if (!empty($arr_pm_list)){
	foreach ($arr_pm_list as $pm_row){
		$num_projects = $pm_row["num_projects"];
		$project_manager_id = $pm_row["user_id"];
		$project_manager_name = $pm_row["first_name"] . " " . $pm_row["last_name"];
		$pm_list_table .= "<tr><td>" . $project_manager_name . "</td>";
		$pm_list_table .= "<td align = \"right\"><a href = \"projects.php?project_manager_id=" . $project_manager_id . "\">" . $num_projects . "</a></td>";
		$pm_list_table .= "</tr>";
		
	}
}else{
	$pm_list_table .= "<tr><td colspan=\"2\">No current project managers or projects.</td></tr>";

}
$pm_list_table .= "</table></div>";

$show_campaign_table = 0;
if(!empty($campaign_detail_quarter)){
	if(!empty($campaign_detail_year)){
		$show_campaign_table = 1;
	}
}
$campaign_table = "";

$area1_content = "<b>Fiscal Year Totals</b>" . $campaign_stats_table;
$area2_content = "<b><a href = \"#\" id = \"pm_project_list_click\">IPM Project Counts</a></b>" . $pm_list_table;
$area3_content = "<b>Campaigns/Projects/Spend</b><br>" . $campaign_detail_quarter . "&nbsp;&nbsp;-&nbsp;&nbsp;" . $campaign_detail_year . "<br>" . $campaign_table;


$today = date("m/d/Y"); 
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Home</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script>
  $(document).ready(function(){
   	$('.campaign_project_click').click(function() {
		var click_id = $(this).attr("id");
		var toggle_section = '.' + click_id + '-projects';
		//alert(toggle_section);
		$(toggle_section).toggle();
		return false;
	});
   	$('.project_spend_click').click(function() {
		var click_id = $(this).attr("id");
		var toggle_section = '.' + click_id + '-spend';
		//alert(toggle_section);
		$(toggle_section).toggle();
		return false;
	});

	$('#pm_project_list_click').click(function() {
	
	$('#pm_projects').toggle();
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
			<table width = "100%" border = "0">
				<tr>
						<td width = "60%" valign = "top">
							<form action = "index.php" method = "GET">
							<?php echo $year_select ?>
							</form>
						</td>
						<td>
							<?php echo $area2_content ?>
						
						</td>
				</tr>
			</table>
			<div id = "home_content">
				<table width = "100%" border = "0" class = "home_grid">
					<tr>
						<td width = "100%" valign = "top"><!--top left TD--> 
							
							<div id = "area1_admin">
							<?php echo $area1_content ?>
							</div>
						</td>
					</tr>

				</table>
			</div> <!--end home_content div tag--> 
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>
</div>



</body>
</html>