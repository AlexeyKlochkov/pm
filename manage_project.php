<?php 

include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";
$campaign_id = 0;
$project_manager_id = 0;
$active_flag = 1;
$is_user = 0;

$file_error = "";
$approval_message = "";
$fast_track_status_message = "";
$asset_message = "";
if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$file_error = "Duplicate file name exists for this project. Please rename the file and re-upload.";
	}
	if ($error_num == 2){
		$approval_message = "Email sent.";
	}
	if ($error_num == 3){
		$approval_message = "Error sending email.";
	}
	if ($error_num == 4){
		$fast_track_status_message = "Fast Track turned off.";
	}
	if ($error_num == 5){
		$fast_track_status_message = "Fast Track started.";
	}
	if ($error_num == 6){
		$asset_message = "Asset added.";
	}
	if ($error_num == 7){
		$asset_message = "An error occurred while adding an asset.";
	}
}

$file_error_num = "";
$file_error_message = "";
if (!empty($_GET["fe"])){
	$file_error_num = $_GET["fe"];
	
	if ($file_error_num == 1){
		$file_error_message = "&nbsp;&nbsp;Uploaded file is too big. 20MB max please.";
	}
	if ($file_error_num == 2){
		$file_error_message = "&nbsp;&nbsp;Upload folder is not writable.";
	}
	if ($file_error_num == 3){
		$file_error_message = "&nbsp;&nbsp;File name exists. Please re-name your file before uploading.";
	}
	if ($file_error_num == 4){
		$file_error_message = "&nbsp;&nbsp;Only one file per asset item allowed.";
	}
	if ($file_error_num == 5){
		$file_error_message = "&nbsp;&nbsp;Don't forget to select a file...";
	}
}

if (!empty($_GET["p"])){
	$project_id = $_GET["p"];
}else{
		$location = "Location: loggedout.php";
		header($location) ;
}


$arr_project = get_project_info($project_id);

if (empty($arr_project)){
	$location = "Location: loggedout.php";
	header($location) ;
}

$_SESSION["project_id"] = $project_id;
//print_r($arr_projects);
$add_people_text = "Add People";
$show_users = "hide";
if (!empty($_GET["showusers"])){
	if ($_GET["showusers"] == 1){
		$show_users = "show";
		$add_people_text = "close";
	}
}

$show_files = "hide";
if (!empty($_GET["show_files"])){
	if ($_GET["show_files"] == 1){
		$show_files = "show";
	}
}

$show_schedules = "hide";
if (!empty($_GET["show_schedules"])){
	if ($_GET["show_schedules"] == 1){
		$show_schedules = "show";
	}
}

$show_assets = "hide";
if (!empty($_GET["showassets"])){
	if ($_GET["showassets"] == 1){
		$show_assets = "show";
	}
}

$show_legal = "hide";
if (!empty($_GET["showLegal"])){
	if ($_GET["showLegal"] == 1){
		$show_legal = "show";
	}
}

$show_studio = "hide";
if (!empty($_GET["showStudio"])){
	if ($_GET["showStudio"] == 1){
		$show_studio = "show";
	}
}

$show_financial = "hide";
if (!empty($_GET["showFinancial"])){
	if ($_GET["showFinancial"] == 1){
		$show_financial = "show";
	}
}

$show_final = "hide";
if (!empty($_GET["showFinal"])){
	if ($_GET["showFinal"] == 1){
		$show_final = "show";
	}
}

$show_cr = "hide";
if (!empty($_GET["showCR"])){
	if ($_GET["showCR"] == 1){
		$show_cr = "show";
	}
}

$show_cb = "hide";
if (!empty($_GET["showCB"])){
	if ($_GET["showCB"] == 1){
		$show_cb = "show";
	}
}

//print $show_users;
$asset_list = "";

$asset_type_select = get_asset_type_select($company_id, 0);
$current_year = date("Y");
$current_month = date("m");;
$spend_year_select = get_year_select_spend($current_year);
$spend_month_select = get_month_select($current_month);


$spend_table = "<table class=\"budget\"><tr><th>#</th><th>Type</th><th>Asset</th><th>Vendor</th><th>Notes</th><th>Invoice #</th><th>PO#</th><th>Cost Center</th><th>% Complete</th><th>Date</th><th>Amount</th><th>Remaining</th><th colspan = \"2\">&nbsp;</th></tr>";
$arr_spend = get_spend_by_project($project_id);

$media_spend_total = 0;
$production_spend_total = 0;
$other_spend_total = 0;

$vendor_other_id = get_vendor_other_id($company_id);

$i = 1;
if (!empty($arr_spend)){
	foreach ($arr_spend as $spend_row){
		$spend_id = $spend_row["spend_id"];
		$asset_id = $spend_row["asset_id"];
		$asset_name = $spend_row["asset_name"];
		$asset_type_name = $spend_row["asset_type_name"];
		$vendor_name = $spend_row["vendor_name"];
		$vendor_other = $spend_row["vendor_other"];
		$vendor_id = $spend_row["vendor_id"];
		if($vendor_id == $vendor_other_id){
			$vendor_name = $vendor_other;
		}
		
		$spend_type = $spend_row["spend_type"];
		$spend_notes = $spend_row["spend_notes"];
		$spend_amount = $spend_row["spend_amount"];
		$invoice_number = $spend_row["invoice_number"];
		$po_number = $spend_row["po_number"];
		$cost_expense_account = $spend_row["cost_expense_account"];
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
			$percent_left = (100-$percent_complete)/100;
			$spend_balance = ($spend_amount*($percent_left));
			//$spend_balance = round($spend_balance,2);
			$spend_balance = number_format((float)$spend_balance, 2, '.', '');
			
		}else{
			$spend_balance = $spend_amount;
		
		}
		
			
			
		$spend_table .= "<tr><td>" . $i . "</td><td>" . $spend_type . "</td><td>" . $asset_name . "</td><td>" . $vendor_name . "</td><td>" . $spend_notes . "</td><td>" . $invoice_number . "</td><td>" . $po_number . "</td><td>" . $cost_expense_account . "</td><td align=\"right\">" . $percent_complete . "</td><td align=\"right\">" . $spend_date . "</td><td align=\"right\">" .  add_commas($spend_amount) . "</td><td align=\"right\">" .  add_commas($spend_balance) . "</td><td><a href = \"edit_spend.php?p=" . $project_id . "&s=" . $spend_id . "\">edit</a></td><td><a href = \"del_spend.php?p=" . $project_id . "&s=" . $spend_id . "\" onclick=\"return confirm('Are you sure want to delete this spend entry?');\">del</a></td></tr>";
		
		if ($spend_type == "Media"){
			$media_spend_total = $media_spend_total + $spend_amount;
		}
		if ($spend_type == "Production"){
			$production_spend_total = $production_spend_total + $spend_amount;
		}
		if ($spend_type == "Other"){
			$other_spend_total = $other_spend_total + $spend_amount;
		}
		
		$i++;
	}
}else{
	$spend_table .= "<tr><td colspan = \"13\">no spend</td></tr>";
}
//print_r($arr_spend);
$spend_table .= "</table>";

$asset_area = "";

$asset_budget_table = "<table class=\"budget\"><tr><th>Asset</th><th>Asset Type</th><th>Quantity</th><th>Media Budget</th><th>Production budget</th></tr>";
$n = 1;
$arr_assets = get_asset_info($project_id);
$asset_count = 0;
$all_js_asset_item_states = "var allStatesObj = {\n";
$asset_item_select = "<select name = \"asset_item_id\"><option value = \"0\">Select</option>\n";
$show_asset_item_states =0;
if (!empty($arr_assets)){
	foreach ($arr_assets as $asset_row){
	$asset_id = $asset_row["asset_id"];
	$asset_name = $asset_row["asset_name"];
	$asset_type_name = $asset_row["asset_type_name"];
	$asset_type_id = $asset_row["asset_type_id"];
	$asset_budget_media = $asset_row["asset_budget_media"];
	$asset_budget_production = $asset_row["asset_budget_production"];
	$asset_quantity = $asset_row["asset_quantity"];
	$asset_start_date = $asset_row["asset_start_date"];
	$asset_end_date = $asset_row["asset_end_date"];
	$asset_has_ge = $asset_row["asset_has_ge"];
	$asset_for_aps = $asset_row["asset_for_aps"];
	$asset_type_category_abbrev = $asset_row["asset_type_category_abbrev"];
	$asset_type_category_name = $asset_row["asset_type_category_name"];
	$asset_type_template_id = $asset_row["asset_type_template_id"];
	if(!empty($asset_start_date)){
		$asset_start_date = convert_mysql_to_datepicker($asset_start_date);
	}
	
	if(!empty($asset_end_date)){
		$asset_end_date = convert_mysql_to_datepicker($asset_end_date);
	}
	
	if (empty($asset_quantity)){
		$asset_quantity = 1;
	}
	$has_ge_string = "";
	if($asset_has_ge == 1){
		$has_ge_string = "yes";
	}else{
		$has_ge_string = "no";
	}
	
	$for_aps_string = "";
	if($asset_for_aps == 1){
		$for_aps_string = "yes";
	}else{
		$for_aps_string = "no";
	}
	
	$asset_notes = $asset_row["asset_notes"];
	$asset_budget_table .= "<tr><td>" . $asset_type_category_abbrev . ": " . $asset_name . "</td>";
	$asset_budget_table .= "<td>" . $asset_type_name . "</td>";
	$asset_budget_table .= "<td align = \"right\">" . $asset_quantity . "</td>";
	$asset_budget_table .= "<td align = \"right\">" . $asset_budget_media . "</td>";
	$asset_budget_table .= "<td align = \"right\">" . $asset_budget_production . "</td></tr>";
	$asset_list .= $asset_type_category_abbrev . ": " . $asset_type_name . " (" . $asset_quantity . ")<br>";
	
	$asset_area .= "<div id = \"a_" . $asset_id . "\"><a name = \"asset_" . $asset_id . "\">\n";
	
	$asset_edit_link = "(<a href = \"edit_asset.php?a=" . $asset_id . "\">edit</a>)";
	//users can't edit or add assets
	if ($_SESSION["user_level"] <20){
		$asset_edit_link = "";
	}
	
	$asset_area .= "<table class = \"budget\" width = \"100%\"><tr><th align = \"left\" colspan = \"2\">" . $n . ". " . $asset_name . $asset_edit_link . "</th></tr>";
	$asset_area .= "<tr><td width = \"30%\">Asset Type:</td><td><b>" . $asset_type_category_name . ": " . $asset_type_name . "</b></td></tr>";
	$asset_area .= "<tr><td>Asset In-Market Date:</td><td>" . $asset_start_date . "</td></tr>";
	$asset_area .= "<tr><td>Asset Expiration Date:</td><td>" . $asset_end_date . "</td></tr>";
	$asset_area .= "<tr><td>Quantity:</td><td>" . $asset_quantity . "</td></tr>";
	$asset_area .= "<tr><td>Has GE:</td><td>" . $has_ge_string . "</td></tr>";
	$asset_area .= "<tr><td>Asset is for APS:</td><td>" . $for_aps_string . "</td></tr>";
	$asset_area .= "<tr><td>Notes:</td><td>" . $asset_notes . "</td></tr>";
	$asset_area .= "</table></div>";
	$asset_item_table = "<table width = \"80%\" class = \"stats_table\"><tr><td class = \"home_white\">&nbsp;</td><td class = \"home_project\"><b>Asset Items (<a href = \"add_asset_item.php?a=". $asset_id . "&p=" . $project_id . "&aimd=" . $asset_start_date . "&aed=" . $asset_end_date . "&hge=" . $asset_has_ge . "\">add</a>)</b></td><td class = \"home_project\"><b>Name</b></td><td class = \"home_project\"><b>Has GE</b></td><td class = \"home_project\"><b>In-Market</b></td><td class = \"home_project\"><b>Expiration</b></td><td class = \"home_project\"><b>APS Item #</b></td><td class = \"home_project\"><b>States</b></td><td class = \"home_project\" colspan = \"2\">&nbsp;</td></tr>";
	$arr_asset_items = get_asset_items($asset_id);
	if (!empty($arr_asset_items)){
		
		foreach ($arr_asset_items as $asset_item_row){
			$asset_item_id = $asset_item_row["asset_item_id"];
			$asset_item_code = $asset_item_row["asset_item_code"];
			$aps_product_id = $asset_item_row["aps_product_id"];
			$asset_item_name = $asset_item_row["asset_item_name"];
			$asset_item_num = $asset_item_row["asset_item_num"];
			$asset_item_has_ge = $asset_item_row["asset_item_has_ge"];
			$asset_item_in_market_date = $asset_item_row["asset_item_in_market_date"];
			$asset_item_expiration_date = $asset_item_row["asset_item_expiration_date"];
			$asset_item_states = $asset_item_row["asset_item_states"];
			if(!empty($asset_item_states)){
				$show_asset_item_states = 1;
			}
			$js_asset_item_states_array = "\"states_" . $asset_item_id . "\" : [\"" . str_replace(", ", "\",\"",  $asset_item_states) . "\"],\n";
			$all_js_asset_item_states .= $js_asset_item_states_array;
			
			$asset_item_select .= "<option value = \"" . $asset_item_id . "\">" . $asset_item_code . ": " . $asset_item_name . "</option>\n";
			if(!empty($asset_item_in_market_date)){
				$asset_item_in_market_date  = convert_mysql_to_datepicker($asset_item_in_market_date);
			}
			if(!empty($asset_item_expiration_date)){
				$asset_item_expiration_date  = convert_mysql_to_datepicker($asset_item_expiration_date);
			}

			$asset_item_has_ge_checked = "";
			if ($asset_item_has_ge == 1){
				$asset_item_has_ge_checked = "checked";
			}
			$asset_item_states = $asset_item_row["asset_item_states"];
			$asset_item_table .= "<tr><td class = \"home_white\">&nbsp;</td><td class = \"home_project\" valign=\"top\">". $asset_item_code . "<div id = \"aiid_" . $asset_item_id . "_message\" class = \"error\"></div></td>";
			$asset_item_table .= "<td class = \"home_project\" valign=\"top\"><input id = \"asset_item_name_" . $asset_item_id . "\" type = \"text\" name = \"asset_item_name\" value = \"" . $asset_item_name . "\">";
			$asset_item_table .= "<td class = \"home_project\" valign=\"top\"><input id = \"asset_item_has_ge_" . $asset_item_id . "\" type = \"checkbox\" name = \"asset_item_has_ge\" value = \"1\" " . $asset_item_has_ge_checked . "></td>";
			$asset_item_table .= "<td class = \"home_project\" valign=\"top\"><input id = \"asset_item_in_market_date_" . $asset_item_id . "\" type = \"text\" name = \"asset_item_in_market_date\" value = \"" . $asset_item_in_market_date . "\" class = \"datepicker\" size = \"6\">";
			$asset_item_table .= "<td class = \"home_project\" valign=\"top\"><input id = \"asset_item_expiration_date_" . $asset_item_id . "\" type = \"text\" name = \"asset_item_expiration_date\" value = \"" . $asset_item_expiration_date . "\" class = \"datepicker\" size = \"6\">";
			
			$asset_item_table .= "<td class = \"home_project\" valign=\"top\"><input id = \"aps_product_id_" . $asset_item_id . "\" type = \"text\" name = \"aps_product_id\" value = \"" . $aps_product_id . "\"><input type = \"button\" value = \"save\" class=\"update_aps_product_id\" aiid=\"" . $asset_item_id . "\"></td>\n";
			//state list
			$asset_item_table .= "<td class = \"home_project\" valign=\"top\">\n";
				//edit image
			$asset_item_table .= "<table border = \"0\" cellpadding = \"0\" cellspacing = \"0\" width = \"100%\"><tr><td><a href = \"#\" class = \"state_click\" aaid = \"" . $asset_item_id . "\"><img src = \"images/edit_sm.png\" border = \"0\"></a></td>\n";
				//select all checkbox div
			$asset_item_table .= "<td><div id = \"sel_states_" . $asset_item_id . "\" style = \"display:none;\">all <input type = \"checkbox\" aaid = \"" . $asset_item_id . "\" class = \"sel_states_click\"></div></td></tr></table>\n";
				//edit states div
			$asset_item_table .= "<div id = \"edit_states_" . $asset_item_id . "\" style = \"display:none;\"><form action = \"update_asset_item_states.php\" method = \"POST\" class=\"budget\" id = \"chk_state_list_" . $asset_item_id . "\"><div id = \"edit_states_checkboxes_" . $asset_item_id . "\"></div>\n";
			
				//submit button
			$asset_item_table .= "<br><input type = \"hidden\" name = \"aaid\" value = \"" . $asset_item_id . "\"><input type = \"hidden\" name = \"project_id\" value = \"" . $project_id . "\"><input type = \"hidden\" name = \"asset_id\" value = \"" . $asset_id . "\"><input type = \"submit\" value = \"update\"></form></div>\n";
				//state list
			$asset_item_table .= "<div id = \"state_list_" . $asset_item_id . "\">" . $asset_item_states . "</div>\n";
				//close td
			$asset_item_table .=  "</td>";
			$spec_link = "";
			if (!empty($asset_type_template_id)){
				$spec_link = "<a href = \"asset_item_specsheet.php?p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id . "\" target=\"_blank\">spec</a>";
			}
			$asset_item_table .= "<td class = \"home_project\" valign=\"top\">" . $spec_link . "</td><td class = \"home_project\" valign=\"top\"><a href = \"del_asset_item.php?p=" . $project_id . "&aiid=" . $asset_item_id . "&a=" . $asset_id . "\" onclick=\"return confirm('Are you sure want to delete this asset item?.');\">del</a></td></tr>\n";
			
		}
	
	}else{	
		$asset_item_table .= "<tr><td class = \"home_white\">&nbsp;</td><td class = \"home_project\" colspan = \"8\" valign=\"top\">No items.</td></tr>\n";
	}
		$asset_item_table .= "</table>";
		$asset_area .= $asset_item_table;
		$asset_count = $asset_count + $asset_quantity;
		$n++;
	}
	
	//print $all_js_asset_item_states;
	//print strlen($asset_item_states);
	$all_js_asset_item_states = substr($all_js_asset_item_states, 0, -2);
	
}else{
	$asset_budget_table .= "<tr><td colspan = \"5\">no assets</td></tr>";
	$asset_list = "No assets";
	$asset_area .= "<tr><td colspan = \"2\">No assets.</td></tr>";
}
$asset_item_select .= "</select>\n";
if ($asset_count > 0){
	$asset_list .= "<b>Total assets: " . $asset_count . "</b>";
}

$all_js_asset_item_states .= "\n}";	

if(empty($show_asset_item_states)){
	$all_js_asset_item_states = "var allStatesObj = {\"place_holder\" : [\"\"]   }";
}

$asset_area .= "</table>";

$asset_budget_table .= "</table>";
$arr_people = get_project_people($project_id);
$assignedUsers=Array();
foreach ($arr_people as $people){
	array_push($assignedUsers,$people["user_id"]);
}
$project_table = "<table class = \"display1\">";
if (!empty($arr_project)){

		$project_id = $arr_project[0]["project_id"];
		$project_code = $arr_project[0]["project_code"];
		$project_name = $arr_project[0]["project_name"];
		$popup_project_name = str_replace("'","",$project_name);
		$campaign_code = $arr_project[0]["campaign_code"];
		$campaign_name = $arr_project[0]["campaign_description"];
		$campaign_budget = $arr_project[0]["campaign_budget"];
		$business_unit_name = $arr_project[0]["business_unit_name"];
		$default_cost_code = $arr_project[0]["default_cost_code"];
		$campaign_year = $arr_project[0]["campaign_year"];
		$product_name = $arr_project[0]["product_name"];
		$audience_name = $arr_project[0]["audience_name"];
		$project_status = $arr_project[0]["project_status_name"];
		$production_budget = $arr_project[0]["production_budget"];
		$project_requester = $arr_project[0]["project_requester"];
		$project_soft_costs = get_soft_cost_by_project($project_id);
		$media_budget = $arr_project[0]["media_budget"];
		$pm_first_name = $arr_project[0]["first_name"];
		$pm_last_name = $arr_project[0]["last_name"];
		$aop_activity_type_name = $arr_project[0]["aop_activity_type_name"];
		$business_unit_owner_first_name = $arr_project[0]["business_unit_owner_first_name"];
		$business_unit_owner_last_name = $arr_project[0]["business_unit_owner_last_name"];
		$acd_first_name = $arr_project[0]["acd_first_name"];
		$acd_last_name = $arr_project[0]["acd_last_name"];
		$brand_manager_name = $business_unit_owner_first_name . " " . $business_unit_owner_last_name;
		$project_manager = $pm_first_name . " " . $pm_last_name;
		$acd_name = $acd_first_name . " " . $acd_last_name;
		$project_table .= "<tr><td class = \"left_header\">Line of Business:</td><td>" . $business_unit_name . " - " . $campaign_year . " (" . $campaign_code . ")</td></tr>";
		$project_table .= "<tr><td class = \"left_header\">Project:</td><td>" . $project_code . " - " . $project_name . "</td></tr>";
		$project_table .= "<tr><td class = \"left_header\">Product:</td><td>" . $product_name . "</td></tr>";
		$project_table .= "<tr><td class = \"left_header\">Brand Manager:</td><td>" . $brand_manager_name . "</td></tr>";
		$project_table .= "<tr><td class = \"left_header\">Project Manager:</td><td>" . $project_manager . "</td></tr>";
		$project_table .= "<tr><td class = \"left_header\">ACD:</td><td>" . $acd_name . "</td></tr>";
		$project_table .= "<tr><td class = \"left_header\">Project Requester:</td><td>" . $project_requester . "</td></tr>";
		$project_table .= "<tr><td class = \"left_header\">AOP Activity Type:</td><td>" . $aop_activity_type_name . "</td></tr>";
		$project_table .= "<tr><td class = \"left_header\">Current Status:</td><td>" . $project_status . "</td></tr>";	
		$pif_id = "";
		$pif_code = "";
		if ($_SESSION["user_level"] > 10 || (in_array($_SESSION["user_id"],$assignedUsers))){
			$arr_pif = get_max_pif_for_project($project_id);
			$pif_id = $arr_pif[0]["pif_id"];
			$pif_code = $arr_pif[0]["pif_code"];
			if(!empty($pif_id)){
			$project_table .= "<tr><td class = \"left_header\">Project Brief:</td><td><a href = \"view_pif.php?p=" . $pif_id . "\" target = \"_blank\">" . $pif_code . "</a></td></tr>";	
			}
		}


}
$project_table .= "<tr><td class = \"left_header\" valign = \"top\">Assets:</td><td>" . $asset_list  . "</td></tr>";	

$project_table .= "</table>";

$people_table = "<table width = \"250\" class = \"people\"><tr><td colspan = \"2\" class = \"mini_header\">Project People</td></tr>";
//$arr_people = get_project_people($project_id);
//print_r($arr_people);
if (!empty($arr_people)){
	foreach ($arr_people as $people_row){
		$project_user_id = $people_row["project_user_id"];
		$first_name =  $people_row["first_name"];
		$last_name =  $people_row["last_name"];
		$role_abbrev = $people_row["role_abbrev"];
		$people_table .= "<tr><td>" . $first_name . " " . $last_name . " (" . $role_abbrev . ")</td>";
		if ($_SESSION["user_level"] > 10){
			$people_table .= "<td><a href = \"del_project_person.php?puid=" . $project_user_id . "&p=" . $project_id . "\">del</a></td></tr>";
		}else{
			$people_table .= "<td>&nbsp;</td></tr>";
		}
	}
}

$arr_users = get_users_for_project2($project_id);
//print_r($arr_users);
$current_role_abbrev = "";
$user_table = "<table width = \"250\" class = \"people\">";
if (!empty($arr_users)){
	foreach ($arr_users as $user_row){
		$role_abbrev = $user_row["role_abbrev"];
		$role_name = $user_row["role_name"];
		$user_id = $user_row["user_id"];
		$first_name = $user_row["first_name"];
		$last_name = $user_row["last_name"];
		
		//if ($current_role_abbrev <> $role_abbrev){
		//	$user_table .= "<tr><td colspan = \"2\"><b>" . $role_name . "</b></td></tr>";
		//}
		$user_table .= "<tr><td>" . $first_name . " " . $last_name . " (" . $role_abbrev . ")</td><form action = \"add_project_person.php\" method = \"post\"><td><input type = \"hidden\" name = \"user_id\" value = \"" . $user_id . "\"><input type = \"hidden\" name = \"project_id\" value = \"" . $project_id . "\"><input type = \"submit\" value = \"add\"></td></form></tr>";
		$current_role_abbrev = $role_abbrev;
	}
}

$user_table .= "</table>";
if ($_SESSION["user_level"] > 10){
	$people_table .= "<tr><td colspan = \"2\"><a href=\"#\" id=\"add_people_click\">Add People</a><br><div id = \"add_people\" style = \"display:none;\">" . $user_table . "</div></td></tr>";
}
$people_table .= "</table>";

$remaining_media_budget = ($media_budget - $media_spend_total);
$remaining_production_budget = ($production_budget - $production_spend_total);
$total_spend = $media_spend_total + $production_spend_total + $other_spend_total;
$spend_colspan = "4";
if ($_SESSION["user_level"] > 30){
	$spend_colspan = "5";
}

$budget_table = "<table class=\"budget\">";
$budget_table .= "<tr><th colspan = \"3\" align=\"center\">Budgets</th><th colspan = \"" . $spend_colspan . "\" align=\"center\">Spend</th><th colspan = \"2\">Remaining</th></tr>";
$budget_table .= "<tr><th>AOP</th><th>Project<br>Media</th><th>Project <br>Production</th><th>Media</th><th>Production</th><th>Other</th>";

if ($_SESSION["user_level"] > 30){
	$budget_table .= "<th>Work<br>Effort</th>";
}
$budget_table .= "<th>Total<br>Spend</th><th>Media</th><th>Production</th></tr>";

$budget_table .= "<tr><td align=\"right\">" . add_commas($campaign_budget) . "</td><td align=\"right\">" . add_commas($media_budget) . "</td><td align=\"right\">" . add_commas($production_budget) . "</td>";
$budget_table .= "<td align=\"right\">" . add_commas($media_spend_total) . "</td><td align=\"right\">" . add_commas($production_spend_total) . "</td><td align=\"right\">" . add_commas($other_spend_total) . "</td>";

if ($_SESSION["user_level"] > 30){
	$budget_table .= "<td align=\"right\">" . add_commas($project_soft_costs) . "</td>";
}
$budget_table .= "<td align=\"right\">" . add_commas($total_spend) . "</td><td align=\"right\">" . add_commas($remaining_media_budget) . "</td><td align=\"right\">" . add_commas($remaining_production_budget) . "</td></tr>";
$budget_table .= "</table>";

$vendor_select = get_vendor_select($company_id, 0);
$asset_select = get_asset_select($project_id, 0);

$spend_form = "<a href=\"#\" id=\"add_spend_click\" style=\"color:#000000;\">Add Spend</a><br><div id = \"add_spend\"><form action = \"add_spend.php\" method = \"POST\" id=\"spend_form\"><table class = \"form_table\" border=\"0\">\n";


$spend_form .= "<tr><td>Spend Amount<br><input class = \"required\" type = \"text\"  name = \"spend_amount\"size = \"8\"></td>\n";
$spend_type_select = "<select name = \"spend_type\" class = \"required\"><option value = \"Media\">Media</option><option value = \"Production\">Production</option><option value = \"Other\">Other</option></select>";
$spend_form .= "<td>Type:<br>" . $spend_type_select . "</td>\n";
$spend_form .= "<td colspan = \"2\">Vendor (optional):<br>" . $vendor_select . "</td><td><div id = \"vendor_other\" style=\"display:none;\">Vendor Other:<br><input type = \"text\" name = \"vendor_other\" ></div></td>\n";
$spend_form .= "<td colspan = \"2\">Asset (optional):<br>" . $asset_select . "</td>\n";
$spend_form .= "</tr><tr>";

$spend_form .= "<td>Invoice #:<br><input type = \"text\"  name = \"invoice_number\" size = \"10\"></td>\n";
$spend_form .= "<td>PO #:<br><input type = \"text\"  name = \"po_number\" size = \"10\"></td>\n";
$percent_select =  get_percentage_select("percent_complete", 0);
$spend_form .= "<td> Notes:<br><input type = \"text\"  name = \"notes\"></td>\n";
$spend_form .= "<td> Cost Center:<br><input type = \"text\"  name = \"cost_expense_account\" id = \"cost_expense_account\" maxlength=\"21\" size=\"22\" value = \"" . $default_cost_code . "\"></td>\n";
$spend_form .= "<td nowrap>% Complete<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type = \"text\" class = \"required number\" name = \"percent_complete\" size = \"2\" maxlength=\"3\"></td>\n";
$today = date("m/d/y"); 
$spend_form .= "<td>Spend Month:<br>" . $spend_month_select . "</td>\n";
$spend_form .= "<td>Spend Year:<br>" . $spend_year_select . "</td>\n";

$spend_form .= "<td align=\"right\"><input type = \"hidden\" name = \"project_id\" value = \"" . $project_id . "\"><input type = \"hidden\" name = \"user_id\" value = \"" . $user_id . "\"><input type = \"hidden\"  name = \"spend_date\" value = \"1/1/1900\"><input type = \"submit\"  value = \"add\"></td>\n";
$spend_form .= "</tr></table></form></div>\n";

//Put together file section
$PIF_current_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$PIF_archive_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$CB_current_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$CB_archive_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$legal_current_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$legal_archive_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$studio_current_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$studio_archive_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$financial_current_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$financial_archive_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$final_current_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$final_archive_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$round_area = "";

$arr_project_files = get_project_files($project_id);
$prev_round = 0;
$has_rounds = 0;
$current_round = 0;
$approval_document_select = "No active documents";
if (!empty($arr_project_files)){
	$approval_document_select = "<select name = \"approval_project_file_id\"><option value = \"\">Please select (optional)</option>";
	foreach ($arr_project_files as $file_row){
		$project_file_id = $file_row["project_file_id"];
		$file_name = $file_row["project_file_name"];
		$file_notes = $file_row["file_notes"];
		$file_type = $file_row["file_type"];
		$file_active = $file_row["active"];
		$file_network_folder = $file_row["file_network_folder"];
		$file_asset_item_name = $file_row["asset_item_name"];
		$directory = "project_files/" . $project_code . "/";
		$file_location = $directory. $file_name;
		$notes_field = $file_notes;
		if ($_SESSION["user_level"] > 10){
			$notes_field = "<a href=\"#\" onclick=\"openpopup3('popup3','" . $project_file_id . "','" . $file_notes . "','" . $file_type . "','" . $file_name . "','" . $project_id . "')\"><img src = \"images/edit_sm.png\" border = \"0\"></a>&nbsp;&nbsp;" . $file_notes;
		
		}
		
		if ($file_type == "PIF"){
			if ($file_active == 1){
				$PIF_current_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $notes_field . "</td><td><a href =\"del_file.php?a=2&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">archive</a></td></tr>";
				$approval_document_select .= "<option value = \"" .  $project_file_id . "\">PIF - " . $file_name . "</option>\n"; 
			}else{
				$PIF_archive_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $file_notes . "</td><td><a href =\"del_file.php?a=1&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">activate</a></td></tr>";
			
			}
		}
		if ($file_type == "CB"){
			if ($file_active == 1){
				$CB_current_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $notes_field . "</td><td><a href =\"del_file.php?a=2&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">archive</a></td></tr>";
				$approval_document_select .= "<option value = \"" .  $project_file_id . "\">CB - " . $file_name . "</option>\n"; 
			}else{
				$CB_archive_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $file_notes . "</td><td><a href =\"del_file.php?a=1&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">activate</a></td></tr>";
			
			}
		
		}
		if ($file_type == "Legal"){
			if ($file_active == 1){
				$legal_current_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $notes_field . "</td><td><a href =\"del_file.php?a=2&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">archive</a></td></tr>";
				$approval_document_select .= "<option value = \"" .  $project_file_id . "\">LEGAL - " . $file_name . "</option>\n"; 
			}else{
				$legal_archive_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $file_notes . "</td><td><a href =\"del_file.php?a=1&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">activate</a></td></tr>";
			
			}
		
		}
		if ($file_type == "Studio"){
			if ($file_active == 1){
				$studio_current_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $notes_field . "</td><td><a href =\"del_file.php?a=2&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">archive</a></td></tr>";
				$approval_document_select .= "<option value = \"" .  $project_file_id . "\">STUDIO - " . $file_name . "</option>\n"; 
			}else{
				$studio_archive_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $file_notes . "</td><td><a href =\"del_file.php?a=1&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">activate</a></td></tr>";
			
			}
		
		}
		if ($file_type == "Financial"){
			if ($file_active == 1){
				$financial_current_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $notes_field . "</td><td><a href =\"del_file.php?a=2&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">archive</a></td></tr>";
				$approval_document_select .= "<option value = \"" .  $project_file_id . "\">FINANCIAL - " . $file_name . "</option>\n"; 
			}else{
				$financial_archive_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $file_notes . "</td><td><a href =\"del_file.php?a=1&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">activate</a></td></tr>";
			
			}
		
		}
		if ($file_type == "Final"){
			$file_network_location_string = "";
			if(!empty($file_network_folder)){
				$file_network_location_string = "<br><b>Location:</b> " . $file_network_folder;
			}
			$asset_item_name_string = "";
			if(!empty($file_asset_item_name)){
				$asset_item_name_string = "<br><b>Asset Item:</b> " . $file_asset_item_name;
			}
			
			if ($file_active == 1){
				$final_current_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a>" . $asset_item_name_string . $file_network_location_string  . "</td><td>" . $notes_field . "</td><td><a href =\"del_file.php?a=2&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">archive</a></td></tr>";
				$approval_document_select .= "<option value = \"" .  $project_file_id . "\">FINAL - " . $file_name . "</option>\n"; 
			}else{
				$final_archive_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $file_notes . "</td><td><a href =\"del_file.php?a=1&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">activate</a></td></tr>";
			
			}
		}
		
		
		//handle rounds, which are more complicated.
		if ($file_type[0] == "R"){
			$has_rounds = 1;
			$current_round = substr($file_type, 1);
			if ($prev_round <> $current_round){
				
				if($prev_round <> 0){
					//print "Round " . $current_round . "<br>";
					//if the prev round is not zero, close the tables and add to the round_area
					
					$round_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup1','" . $popup_project_name . "','" . $project_id . "','R" . $prev_round . "')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
					$round_archive_table .= "</table>";
					
					$container_table = str_replace("##CURRENT_TABLE##", $round_current_table, $container_table);
					$container_table = str_replace("##ARCHIVE_TABLE##", $round_archive_table, $container_table);
					$round_area .= $container_table . "&nbsp;";
				}
			
				//create container table and the two main tables for this round
				$container_table = "<table class = \"file_main\" width = \"80%\"><tr><th>Round " . $current_round . " Current</th><th>Round " . $current_round . " Archived</th></tr><tr><td width = \"50%\" valign=\"top\">##CURRENT_TABLE##</td><td width = \"50%\" valign=\"top\">##ARCHIVE_TABLE##</td></tr></table>";
				$round_current_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
				$round_archive_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
				
			}
			//add files to these containers
			if ($file_active == 1){
				$round_current_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $notes_field . "</td><td><a href =\"del_file.php?a=2&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">archive</a></td></tr>";
				$approval_document_select .= "<option value = \"" .  $project_file_id . "\">" . $file_type . " - " . $file_name . "</option>\n"; 
			}else{
				$round_archive_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $file_notes . "</td><td><a href =\"del_file.php?a=1&f=" . $file_type . "&pfid=" . $project_file_id . "&p=" . $project_id ."\">activate</a></td></tr>";
			}

			$prev_round = $current_round;
		}
	}
	$approval_document_select .= "</select>";
}
$PIF_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup1','" . $popup_project_name . "','" . $project_id . "','PIF')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
$PIF_archive_table .= "</table>";


$CB_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup1','" . $popup_project_name . "','" . $project_id . "','CB')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
$CB_archive_table .= "</table>";
$legal_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup1','" . $popup_project_name . "','" . $project_id . "','Legal')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
$legal_archive_table .= "</table>";
$studio_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup1','" . $popup_project_name . "','" . $project_id . "','Studio')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
$studio_archive_table .= "</table>";
$financial_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup1','" . $popup_project_name . "','" . $project_id . "','Financial')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
$financial_archive_table .= "</table>";

$final_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup4','" . $popup_project_name . "','" . $project_id . "','Final')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
$final_archive_table .= "</table>";

if ($has_rounds == 1){
	//handle the final round
	$round_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup1','" . $popup_project_name . "','" . $project_id . "','R" . $current_round . "')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
	$round_archive_table .= "</table>";
	
	$container_table = str_replace("##CURRENT_TABLE##", $round_current_table, $container_table);
	$container_table = str_replace("##ARCHIVE_TABLE##", $round_archive_table, $container_table);
	$round_area .= $container_table;

}

//no matter what, show the next round link:
$round_area .= "<table class = \"file_main\"><tr><th>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"#\" onclick=\"openpopup('popup1','" . $popup_project_name . "','" . $project_id . "','R" . ($current_round + 1) . "')\">Add Round " . ($current_round + 1) . " File</a><div class = \"error\">" . $file_error . "</div></th></tr></table>";
	
	
//build phase and project table
$phase_and_project_table = "<table class = \"schedule_container\" width = \"100%\"><tr><td><table class = \"budget\" width = \"100%\">";
$arr_projects_and_phases = get_project_phases_and_schedules($project_id);
//print_r($arr_projects_and_phases);
$current_phase = "";
$next_phase_id = "";
$i=0;
$approval_table_top = "<table class= \"budget\"><tr><th colspan = \"6\">Approvals</th></tr><tr><th>Schedule</th><th>Task</th><th>Status</th><th>Due</th><th>Notes</th><th>History</th></tr>";
$approval_count = 0;
if (!empty($arr_projects_and_phases)){
	foreach ($arr_projects_and_phases as $schedule_row){
		$schedule_down_arrow = "";
		$schedule_up_arrow = "";
		$schedule_id = $schedule_row["schedule_id"];
		$schedule_name = $schedule_row["schedule_name"];
		$schedule_phase_order = $schedule_row["schedule_phase_order"];
		$fast_track_status = $schedule_row["fast_track_status"];
		$phase_id = $schedule_row["phase_id"];
		$phase_name = $schedule_row["phase_name"];
		$asset_name = $schedule_row["asset_name"];
		//print $phase_name . "--" . $schedule_name;
		if (empty($phase_name)){
			$phase_name = "No phase";
		}
		if($current_phase <> $phase_id){
			//add phase header row
			$phase_and_project_table .= "<tr><th colspan = \"7\" align=\"left\">Phase: " . $phase_name . "</th></tr>";
		}
		if (!empty($asset_name)){
			$schedule_name .= "<br>Asset: " . $asset_name;
		}
		
		if ($fast_track_status  == 1){
			$fast_track_button = "<a href = \"toggle_fasttrack.php?a=2&s=" . $schedule_id . "&p=". $project_id . "\">stop fast track</a>";
		}else{
			$fast_track_button = "<a href = \"toggle_fasttrack.php?a=1&s=" . $schedule_id . "&p=". $project_id . "\">start fast track</a>";
		}
		$phase_and_project_table .= "<tr><td width = \"30\" align=\"right\" valign=\"top\"><b>" . $schedule_phase_order ."</b></td><td><b>Schedule: " . $schedule_name . "</b></td>";
		
		if ($_SESSION["user_level"] > 10){
		$phase_and_project_table .= "<td><a href = \"manage_schedules.php?p=" . $project_id . "&s=" . $schedule_id . "\">manage schedule</a></td><td><a href = \"manage_tasks.php?p=" . $project_id . "&s=" . $schedule_id . "\">manage tasks</a></td><td><a href = \"shift_schedule_tasks.php?p=" . $project_id . "&s=" . $schedule_id . "\">shift schedule</a></td><td>" . $fast_track_button  . "</td><td nowrap><a href = \"export_schedule_csv.php?s=" . $schedule_id . "\">save csv</a></td></tr>";
		$current_phase = $phase_id;
		}else{
			$phase_and_project_table .= "<td colspan = \"6\">&nbsp;</td>";
		}
		//Build task table

		$task_table = "<table width = \"100%\" class = \"task_table\"><tr><th>Order</th><th>Task</th><th>Manager</th><th>Start</th><th>End</th><th>Hours</th><th>Mins</th><th>Progress</th><th>Complete?</th><th>Assignee(s)</th><th>Approval</th><th>Cal</th>";
		
		if ($fast_track_status  == 1){
			$task_table .= "<th>Fast Track</th>";
		}
		
		$task_table .= "</tr>";
		$arr_schedule_tasks = get_schedule_tasks($schedule_id);
		$n=0;
		//print_r($arr_schedule_tasks);
		if (!empty($arr_schedule_tasks)){
			foreach ($arr_schedule_tasks as $task_row){
				$task_down_arrow = "";
				$task_up_arrow = "";
				$display_order = $task_row["display_order"];
				$schedule_task_id = $task_row["schedule_task_id"];
				$task_name = $task_row["task_name"];
				$manager_name = $task_row["initials"];
				$is_approval = $task_row["is_approval"];
				$is_approved = $task_row["is_approved"];
				$approver_initials = $task_row["approver"];
				$approval_notes = $task_row["approval_notes"];
				$is_current_task = $task_row["is_current_task"];
				$start_date = translate_mysql_todatepicker($task_row["start_date"]);
				$end_date = translate_mysql_todatepicker($task_row["end_date"]);
				$estimated_hours = $task_row["estimated_hours"];
				$complete = $task_row["complete"];
				$approval_string = "&nbsp;";
				
				//needs this format: 20130731
				$arr_calendar_start_date = explode("-", $task_row["start_date"]);
				//print_r($arr_calendar_start_date) . "<br>";
				$calendar_start_date = $arr_calendar_start_date[0] . $arr_calendar_start_date[1] . $arr_calendar_start_date[2];
				
				$arr_calendar_end_date = explode("-", $task_row["end_date"]);
				$calendar_end_date = $arr_calendar_end_date[0] . $arr_calendar_end_date[1] . $arr_calendar_end_date[2];
				
				$calendar_decription = "Project: " . $project_code . " - " . $project_name . "*";
				$calendar_decription .= "Task: " . $task_name . "*";
				$calendar_decription .= "PM: " . $project_manager . "*";
				$calendar_decription .= "Start Date: " . $start_date. "*";
				$calendar_decription .= "Due Date: " . $end_date. "**";
				//$calendar_decription .= "<a href = \"close_task.php?stid=" . $schedule_task_id . "&p=" . $project_id . "&s=" . $schedule_id . "&ft=" . $is_current_task . "\">close task</a>";
				
				if ($complete == 1){
					$complete_string = "yes";
				}else{
					$complete_string = "no";
					if ($_SESSION["user_level"] > 10){
						$complete_string .= " (<a href = \"close_task.php?stid=" . $schedule_task_id . "&p=" . $project_id . "&s=" . $schedule_id . "&ft=" . $is_current_task . "\">close</a>)";
					
					}
					
				}
				$assignee_list = get_assignee_initials($schedule_task_id);
				list($hours, $minutes, $seconds) = explode(":", $estimated_hours);
				$progress = $task_row["progress"];
				
				$send_string = "(<a href=\"#\" onclick=\"openpopup2('popup2','" . $schedule_name . "','" . $task_name . "','" . $assignee_list . "','" . $schedule_task_id . "')\">send</a>)";
				
				if($assignee_list=="Nobody Assigned."){
					$send_string = "(<a href = \"manage_tasks.php?p=" . $project_id . "&s=" . $schedule_id . "\">assign</a>)";
				}
				
				if ($is_approval == 1){
					$approval_string = "Approval pending - " . $assignee_list . " ". $send_string ;
					$approval_history_table = "";
					$history_link = "";
					if ($is_approved == 1){
						$approval_date =$task_row["approval_date"];
						$approval_string = "Approved by " . $approver_initials . ":<br>" . $approval_date;
						$approval_history_table = get_approval_history_table($schedule_task_id, $project_code);
						$history_link = "<a id = \"stid" . $schedule_task_id . "\" href = \"#\" class=\"view_approval_history_click\">view</a>";
					}elseif($is_approved == 2){
						$approval_date =$task_row["approval_date"];
						$approval_string = "<div class = \"error\">NOT APPROVED by " . $approver_initials . ":<br>" . $approval_date . " " . $send_string . "</div>";
						$approval_history_table = get_approval_history_table($schedule_task_id, $project_code);
						$history_link = "<a id = \"stid" . $schedule_task_id . "\" href = \"#\" class=\"view_approval_history_click\">view</a>";
					}
					$approval_table_top .= "<tr><td>" . $schedule_name . "</td><td>" . $task_name . "</td><td>" . $approval_string . "</td><td>" . $end_date . "</td><td>" . $approval_notes . "</td><td>" . $history_link . $approval_history_table . "</td></tr>";
					$approval_count ++;
				}
				
				
				if (!empty($arr_schedule_tasks[$n+1]["display_order"])){
					$next_task_order = $arr_schedule_tasks[$n+1]["display_order"];
				}else{
					$next_task_order = "0";
				}
				
				if ($display_order <> 1){
						$swap1 = $display_order;
						$swap2 = $display_order - 1;
						$task_up_arrow = "<a href = \"move_schedule_task.php?s=" . $schedule_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_up.png\" border=\"0\"></a>";
					}
				if ($display_order < $next_task_order){
					$swap1 = $display_order;
					$swap2 = $display_order + 1;
					$task_down_arrow = "<a href = \"move_schedule_task.php?s=" . $schedule_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_down.png\" border=\"0\"></a>";
				}
				
				$task_class = "";
				$todays_date = date("m/d/Y");
				
				
				
				if ($is_current_task == 1){
					$task_class = "current_task";
				}
				
				if ($complete == 0){
					if (strtotime($todays_date) > strtotime($end_date)){
						$task_class = "late";
						if ($is_current_task == 1){
							$task_class = "current_task_late";
						}
					}
				}

				
				$task_table .= "<tr class = \"task_row\">";
				$task_table .= "<td class = \"" . $task_class . "\" align=\"right\">" . $display_order . "</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\">" . $task_name . "</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\">" . $manager_name . "</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"right\">" . $start_date  . "</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"right\">" . $end_date  . "</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"right\">" . $hours  . "</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"right\">" . $minutes  . "</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"right\">" . $progress  . "%</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\">" . $complete_string  . "</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\">" . $assignee_list  . "</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\">" . $approval_string  . "</td>";
				$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\"><a href = \"add_outlook_meeting.php?date=" . $calendar_start_date . "&amp;startTime=1600&amp;endTime=1700&amp;subject=" . $project_code . " - " . $task_name . "&amp;desc=" . $calendar_decription . "\" border=\"0\"><img src = \"images/sm_calendar_icon.png\"></a></td>";
				
				//$task_table .= "<td>" . $task_up_arrow  . "</td>";
				//$task_table .= "<td>" . $task_down_arrow  . "</td>";
				if ($_SESSION["user_level"] > 10){
					if ($is_current_task  == 1){
						$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\"><a href = \"fast_track_complete_send.php?stid=" . $schedule_task_id . "&s=" . $schedule_id . "&p=" . $project_id . "\">complete/send</td>";
					}else{
						$task_table .= "";
					}
				}else{
					//$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\">&nbsp;</td>";
					$task_table .= "";
				}
				
				$task_table .= "</tr>";
				$n++;
			}
		}else{
			$task_table .= "<tr><td colspan = \"12\">No tasks</td></tr>";

		}

		$task_table .= "</table>";
		$phase_and_project_table .= "<tr><td>&nbsp;</td><td colspan = \"6\">" . $task_table . "</td></tr>";
		
		

		$i++;
	}

}else{
	$phase_and_project_table .= "<tr><td colspan = \"4\">No schedules.</td></tr>";
}

$phase_and_project_table .= "</table></td></tr></table>";

if ($approval_count == 0){
	$approval_table_top .= "<tr><td colspan = \"6\">No approvals for this project</td></tr>";
}

$approval_table_top .= "</table>";

$arr_states = get_states();
$js_all_states_array = "var arr_all_states = ['NAT',";
if(!empty($arr_states)){
	foreach ($arr_states as $state_row){
		$state_id = $state_row["state_id"];
		$state_name = $state_row["state_name"];
		$state_abbrev = $state_row["state_abbrev"];
		if ($state_abbrev <> "NAT"){
			$js_all_states_array .= "\"" . $state_abbrev . "\",";
		}
		
	}
	//delete the last comma
	$js_all_states_array = substr($js_all_states_array, 0, -1);
}
$js_all_states_array .= "];";

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />
<title>Manage Project</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	$("#spend_form").validate({
		rules: {
			percent_complete: {
				required: true,
				max: 100,
				min: 0,
				number: true
			}, 
			cost_expense_account: {
				required: true,
				minlength: 21
			}
		
		}
	
	});
	//js states
<?php echo $js_all_states_array . "\n" . $all_js_asset_item_states ?>


	$('.state_click').click(function() {
		
		var aaid = $(this).attr("aaid");
		
		//get the proper state list
		var arr_state_list = allStatesObj["states_" + aaid];
		//alert(arr_state_list);
		var arrayLength = arr_all_states .length;
		var strStateForm = "";
		for (var i = 0; i < arrayLength; i++) {
			var current_state_abbrev = arr_all_states[i];
			strStateForm = strStateForm + "<input type = 'checkbox' class = 'chk_state' name = 'chk_" + current_state_abbrev + "' value = '1' ";
			//if the current state is in the state list...
			if(jQuery.inArray(current_state_abbrev, arr_state_list)!==-1){
				strStateForm  = strStateForm  + " checked";
			}
			strStateForm  = strStateForm  + "> " + current_state_abbrev + "<br>\n";
		}
		//$('#state_list').html(strStateForm);
		$('#edit_states_checkboxes_' + aaid).html(strStateForm);
		$('#state_list_' + aaid).toggle();
		$('#edit_states_' + aaid).toggle();
		$('#sel_states_' + aaid).toggle();
		return false;
	});

	
	$('.sel_states_click').click(function(event) {  //on click 
		var aaid = $(this).attr("aaid");
        if(this.checked) { // check select status
			$('#chk_state_list_' + aaid + ' .chk_state').prop('checked', true);
        }else{
		   $('#chk_state_list_' + aaid + ' .chk_state').prop('checked', false);
        }
    });

	
	$('.view_approval_history_click').click(function() {
		var click_id = $(this).attr("id");
		var toggle_section = '#approval_history_' + click_id;
		//alert(toggle_section);
		$(toggle_section).toggle();
		return false;
	});
	
	//spend keyup function - replace cost code underscores with dashes
	$( "#cost_expense_account").keyup(function() {
		var cost_expense_value = $( "#cost_expense_account").val();
		cost_expense_value = cost_expense_value.replace("_","-");
		$("#cost_expense_account").val(cost_expense_value);
		//alert(cost_expense_value);
	});
	
	
	$("#add_project").validate();

	$('#asset_info').<?php echo $show_assets ?>();
	$('#add_spend').hide();
	$('#schedule_area').<?php echo $show_schedules ?>();
	
	$('#file_area').<?php echo $show_files ?>();
	
	
	$('#add_people').<?php echo $show_users ?>();
	$('#add_people_click').text("<?php echo $add_people_text?>");

	// jQuery functions go here.
	$('#add_people_click').click(function() {
		$('#add_people').toggle();
		var add_people_text = $('#add_people_click').text();
		if($("#add_people").is(":visible")){
			//alert("visible");
			$('#add_people_click').text("close");
		}else{
			
			$('#add_people_click').text("Add People");
			//alert("hidden");
		}
		
		//alert(add_people_text);
		//if(add_people_text == "Add People"){
		//	$('#add_people_click').text("close");
		//}
		//if(add_people_text == "close"){
		//	$('#add_people_click').text("Add People");
		//}
		return false;
	});
	$('#budget_info_click').click(function() {
		$('#budget_info').toggle();
		return false;
	});
	$('#asset_info_click').click(function() {
		$('#asset_info').toggle();
		return false;
	});
	$('#file_area_click').click(function() {
		$('#file_area').toggle();
		return false;
	});
	$('#schedule_area_click').click(function() {
		$('#schedule_area').toggle();
		return false;
	});
	$('#add_spend_click').click(function() {
		$('#add_spend').toggle();
		return false;
	});
	$( ".datepicker" ).datepicker();
	
	hide_all_files();

<?php
	if ($show_legal == "show"){
		echo "$(\"#legal\").show();";
		echo "$(\"#legal_link\").toggleClass(\"file_nav_selected\");\n";
	}elseif ($show_studio == "show"){
		echo "$(\"#studio\").show();";
		echo "$(\"#studio_link\").toggleClass(\"file_nav_selected\");\n";
	}elseif ($show_financial == "show"){
		echo "$(\"#financial\").show();";
		echo "$(\"#financial_link\").toggleClass(\"file_nav_selected\");\n";
	}elseif ($show_final == "show"){
		echo "$(\"#final\").show();";
		echo "$(\"#final_link\").toggleClass(\"file_nav_selected\");\n";
	}elseif ($show_cr == "show"){
		echo "$(\"#cr\").show();";
		echo "$(\"#cr_link\").toggleClass(\"file_nav_selected\");\n";
	}elseif ($show_cb == "show"){
		echo "$(\"#cb\").show();";
		echo "$(\"#cb_link\").toggleClass(\"file_nav_selected\");\n";
	}else{
		echo "$(\"#pif\").show();\n";
		echo "$(\"#pif_link\").toggleClass(\"file_nav_selected\");\n";
	}
?>
	$(".file_nav_link").click(function(){
	  var getName = $(this).attr("name"); 
	  var getID = $(this).attr("id");
	  hide_all_files();
	  $("#" + getName).fadeIn("slow");
	  $("#" + getID).toggleClass("file_nav_selected");
	  return false;

	});
	
	$("#vendor_select").change(function(){
		vendor_value = $( "#vendor_select option:selected" ).text();
		
		if(vendor_value == "_Other"){
			//alert(vendor_value);
			$("#vendor_other").show();
		}else{
			//alert("hide");
			$("#vendor_other").hide();
		}
	});
	
	function hide_all_files(){
		$(".file_section").hide();
		$(".file_nav_link").removeClass("file_nav_selected"); 
	}
	
	$( ".update_aps_product_id" ).click(function() {
		var asset_item_id = $(this).attr("aiid");
		
		var aps_item_value = $("#aps_product_id_" + asset_item_id).val();
		var asset_item_name = $("#asset_item_name_" + asset_item_id).val();
		var asset_item_has_ge_checked = $("#asset_item_has_ge_" + asset_item_id).prop('checked');
		var asset_item_in_market_date = $("#asset_item_in_market_date_" + asset_item_id).val();
		var asset_item_expiration_date = $("#asset_item_expiration_date_" + asset_item_id).val();
		if (asset_item_has_ge_checked){
			var asset_item_has_ge = 1;
		}else{
			var asset_item_has_ge = 0;
		}
		//alert( '--' + asset_item_in_market_date + '--' );
		 $.ajax({   
		   type: 'POST',   
		   url: 'update_aps_product_id.php',   
		   data: {aiid:asset_item_id ,aps_product_id:aps_item_value, name:asset_item_name, has_ge:asset_item_has_ge, asset_item_in_market_date:asset_item_in_market_date, asset_item_expiration_date:asset_item_expiration_date }
		});
		$("#aiid_" + asset_item_id + "_message").text("Updated.");
	});
	
});
    </script>

<script language="javascript"> 
function openpopup(id,project_name,project_id,file_type){ 
      //Calculate Page width and height 

      var pageWidth = window.innerWidth; 
      var pageHeight = window.innerHeight; 
      if (typeof pageWidth != "number"){ 
      if (document.compatMode == "CSS1Compat"){ 
            pageWidth = document.documentElement.clientWidth; 
            pageHeight = document.documentElement.clientHeight; 
      } else { 
            pageWidth = document.body.clientWidth; 
            pageHeight = document.body.clientHeight; 
      } 
      }  
      //Make the background div tag visible... 
      var divbg = document.getElementById('bg'); 
      divbg.style.visibility = "visible"; 
        
      var divobj = document.getElementById(id); 
      divobj.style.visibility = "visible"; 
      if (navigator.appName=="Microsoft Internet Explorer") 
      computedStyle = divobj.currentStyle; 
      else computedStyle = document.defaultView.getComputedStyle(divobj, null); 
      //Get Div width and height from StyleSheet 
      var divWidth = computedStyle.width.replace('px', ''); 
      var divHeight = computedStyle.height.replace('px', ''); 
      var divLeft = (pageWidth - divWidth) / 2; 
      var divTop = (pageHeight - divHeight) / 2; 
      //Set Left and top coordinates for the div tag 
      divobj.style.left = divLeft + "px"; 
      divobj.style.top = divTop + "px"; 
      //Put a Close button for closing the popped up Div tag 
      if(divobj.innerHTML.indexOf("closepopup('" + id +"')") < 0 ) 
      divobj.innerHTML = "<a href=\"#\" onclick=\"closepopup('" + id +"')\"><span class=\"close_button\">X</span></a>" + divobj.innerHTML; 
	  document.getElementById('project_id_pop1').value=project_id;
	  document.getElementById('project_id_pop4').value=project_id;
	  document.getElementById('file_type').value=file_type;
	  document.getElementById('file_type_id_pop4').value=file_type;
	  document.getElementById('pname').innerHTML=project_name;
	  document.getElementById('pname4').innerHTML=project_name;
	  document.getElementById('file_type_text').innerHTML=file_type;
	  document.getElementById('file_type_text4').innerHTML=file_type;

} 

function openpopup2(id,schedule_name,task_name,user_initials,schedule_task_id){ 
      //Calculate Page width and height 
      var pageWidth = window.innerWidth; 
      var pageHeight = window.innerHeight; 
      if (typeof pageWidth != "number"){ 
      if (document.compatMode == "CSS1Compat"){ 
            pageWidth = document.documentElement.clientWidth; 
            pageHeight = document.documentElement.clientHeight; 
      } else { 
            pageWidth = document.body.clientWidth; 
            pageHeight = document.body.clientHeight; 
      } 
      }  
      //Make the background div tag visible... 
      var divbg = document.getElementById('bg'); 
      divbg.style.visibility = "visible"; 
        
      var divobj = document.getElementById(id); 
      divobj.style.visibility = "visible"; 
      if (navigator.appName=="Microsoft Internet Explorer") 
      computedStyle = divobj.currentStyle; 
      else computedStyle = document.defaultView.getComputedStyle(divobj, null); 
      //Get Div width and height from StyleSheet 
      var divWidth = computedStyle.width.replace('px', ''); 
      var divHeight = computedStyle.height.replace('px', ''); 
      var divLeft = (pageWidth - divWidth) / 2; 
      var divTop = (pageHeight - divHeight) / 2; 
      //Set Left and top coordinates for the div tag 
      divobj.style.left = divLeft + "px"; 
      divobj.style.top = divTop + "px"; 
      //Put a Close button for closing the popped up Div tag 
      if(divobj.innerHTML.indexOf("closepopup('" + id +"')") < 0 ) 
      divobj.innerHTML = "<a href=\"#\" onclick=\"closepopup('" + id +"')\"><span class=\"close_button\">X</span></a>" + divobj.innerHTML; 
	  document.getElementById('schedule_name').innerHTML=schedule_name;
	  document.getElementById('task_name').innerHTML=task_name;
	  document.getElementById('user_initials').value=user_initials;
	  document.getElementById('schedule_task_id').value=schedule_task_id;
} 


function openpopup3(id,project_file_id,file_notes, file_type, file_name, project_id){ 
	  //alert("foo");
      //Calculate Page width and height 
      var pageWidth = window.innerWidth; 
      var pageHeight = window.innerHeight; 
      if (typeof pageWidth != "number"){ 
      if (document.compatMode == "CSS1Compat"){ 
            pageWidth = document.documentElement.clientWidth; 
            pageHeight = document.documentElement.clientHeight; 
      } else { 
            pageWidth = document.body.clientWidth; 
            pageHeight = document.body.clientHeight; 
      } 
      }  
      //Make the background div tag visible... 
      var divbg = document.getElementById('bg'); 
      divbg.style.visibility = "visible"; 
        
      var divobj = document.getElementById(id); 
      divobj.style.visibility = "visible"; 
      if (navigator.appName=="Microsoft Internet Explorer") 
      computedStyle = divobj.currentStyle; 
      else computedStyle = document.defaultView.getComputedStyle(divobj, null); 
      //Get Div width and height from StyleSheet 
      var divWidth = computedStyle.width.replace('px', ''); 
      var divHeight = computedStyle.height.replace('px', ''); 
      var divLeft = (pageWidth - divWidth) / 2; 
      var divTop = (pageHeight - divHeight) / 2; 
      //Set Left and top coordinates for the div tag 
      divobj.style.left = divLeft + "px"; 
      divobj.style.top = divTop + "px"; 
      //Put a Close button for closing the popped up Div tag 
      if(divobj.innerHTML.indexOf("closepopup('" + id +"')") < 0 ) 
      divobj.innerHTML = "<a href=\"#\" onclick=\"closepopup('" + id +"')\"><span class=\"close_button\">X</span></a>" + divobj.innerHTML; 
	  document.getElementById('pop3_project_file_id').value=project_file_id;
	  document.getElementById('pop3_file_notes').value=file_notes;
	  document.getElementById('pop3_file_type').value=file_type;
	  document.getElementById('pop3_project_id').value=project_id;
	  document.getElementById('pop3_file_name').innerHTML=file_name;
	  

} 


function closepopup(id){ 
      var divbg = document.getElementById('bg'); 
      divbg.style.visibility = "hidden"; 
      var divobj = document.getElementById(id); 
      divobj.style.visibility = "hidden"; 
} 
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
				<div class = "section_area">
					<div class = "section_header">

					<!--project header--> 
					<table width = "100%">
						<tr>
							<td>
								Project <?php echo $project_code . " - " . $project_name ?>
							</td>
							<td align = "right">
							<?php
								if ($_SESSION["user_level"] >= 20){ 
							?>
								<a href = "copy_project.php?p=<?php echo $project_id ?>">copy</a> | 
							
								<a href = "edit_project.php?p=<?php echo $project_id ?>">edit</a>
							<?php
								}
							?>
							</td>
						</tr>
					</table>

					
					</div>
				<table width = "100%" border = "0">
					<tr>
						<td valign="top"><!--project info--> 
						<?php echo $project_table  ?><br>
						</td>
						<td valign = "top">
							<?php echo $people_table ?>
						</td>
					</tr>
						<td colspan = "2">
							<div class = "error"><?php echo $approval_message ?></div>
							<?php echo $approval_table_top ?>
						</td>
				</table>
				</div><!--end section_area div tag--> 
				
				<!--budget area--> 
				<div class = "section_area">
				<div class = "section_header">
				<a name = "budget"></a>
					<!--project header--> 
					<table width = "100%">
						<tr>
							<td>
								<a href="#" id="budget_info_click">Budget</a>
							</td>
							<td align = "right">
							<?php
								if ($_SESSION["user_level"] >= 20){ 
							?>
								<a href = "edit_project.php?p=<?php echo $project_id ?>">edit</a>
							<?php
								}
							?>
							</td>
						</tr>
					</table>
				</div>
					<div id = "budget_info">
					<b>AOP and Project Budgets</b><br>
					<?php echo $budget_table ?><br>
<?php					
if ($_SESSION["user_level"] >= 20){
?>
					<b>Spend Details</b><br>
					<?php echo $spend_table ?><br>
					<?php echo $spend_form ?>
<?php
}
?>
					</div>
				</div>

				<!--asset area--> 
				<a name = "assets"></a>
				<div class = "error"><?php echo $asset_message ?></div>
				<div class = "section_area">
				<div class = "section_header">
				
					<!--project header--> 
					<table width = "100%">
						<tr>
							<td>
								<a href="#" id="asset_info_click">Assets</a>
							</td>
<?php					
if ($_SESSION["user_level"] >= 20){
?>
							<form action = "add_asset.php" method = "POST" id = "add_asset">
							<td align = "right">
								<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
								<?php echo $asset_type_select ?>&nbsp;<input type = "submit" value = "add">
							</td>
							</form>
<?php
}
?>
						</tr>
					</table>
				</div>
					<div id = "asset_info">
					<?php echo $asset_area ?>
					</div>
				</div>
				
				<!--file area--> 
				<a name = "files"></a>
				<div class = "section_area">
				<div class = "section_header">

					<!--area header--> 
					<table width = "100%">
						<tr>
							<td>
								<a href="#" id="file_area_click">Project Files</a>
							</td>
							<td align = "right">
								&nbsp;
							</td>
						</tr>
					</table>
				</div>
					<div id = "file_area">
					<div class = "error"><?php echo $file_error_message ?></div>
					<div id = "file_nav">
					<ul class="file_nav_ul">
						<li><a class = "file_nav_link" id = "pif_link" name = "pif" href = "#files">Project Brief</a></li>
						<li><a class = "file_nav_link" id = "cb_link" name = "cb" href = "#files">Creative Brief</a></li>
						<li><a class = "file_nav_link" id = "cr_link" name = "cr" href = "#files">Creative Rounds</a></li>
						<li><a class = "file_nav_link" id = "legal_link" name = "legal" href = "#files">Legal</a></li>
						<li><a class = "file_nav_link" id = "studio_link" name = "studio" href = "#files">Studio</a></li>
						<li><a class = "file_nav_link" id = "financial_link" name = "financial" href = "#files">Financial</a></li>
						<li><a class = "file_nav_link" id = "final_link" name = "final" href = "#files">Final</a></li>
					</ul>
				</div>
				<div class = "file_container">
					<div class = "file_section" id = "pif">
						<table class = "file_main" width = "80%">
							<tr>
								<th>Current</th>
								<th>Archived</th>
							</tr>
							<tr>
								<td width = "50%" valign="top">
									<?php echo $PIF_current_table ?>
								</td>
								<td width = "50%" valign="top">
									<?php echo $PIF_archive_table ?>
								</td>
							</tr>
						</table>
							
					</div>
					<div class = "file_section" id = "cb">
						<table class = "file_main" width = "80%">
							<tr>
								<th>Current</th>
								<th>Archived</th>
							</tr>
							<tr>
								<td width = "50%" valign="top">
									<?php echo $CB_current_table ?>
								</td>
								<td width = "50%" valign="top">
									<?php echo $CB_archive_table ?>
								</td>
							</tr>
						</table>
							
					</div>
					<div class = "file_section" id = "cr">
						<?php echo $round_area ?>
					</div>
					<div class = "file_section" id = "legal">
						<table class = "file_main" width = "80%">
							<tr>
								<th>Current</th>
								<th>Archived</th>
							</tr>
							<tr>
								<td width = "50%" valign="top">
									<?php echo $legal_current_table ?>
								</td>
								<td width = "50%" valign="top">
									<?php echo $legal_archive_table ?>
								</td>
							</tr>
						</table>
					</div>
					<div class = "file_section" id = "studio">
						<table class = "file_main" width = "80%">
							<tr>
								<th>Current</th>
								<th>Archived</th>
							</tr>
							<tr>
								<td width = "50%" valign="top">
									<?php echo $studio_current_table ?>
								</td>
								<td width = "50%" valign="top">
									<?php echo $studio_archive_table ?>
								</td>
							</tr>
						</table>
					</div>
					<div class = "file_section" id = "financial">
						<table class = "file_main" width = "80%">
							<tr>
								<th>Current</th>
								<th>Archived</th>
							</tr>
							<tr>
								<td width = "50%" valign="top">
									<?php echo $financial_current_table ?>
								</td>
								<td width = "50%" valign="top">
									<?php echo $financial_archive_table ?>
								</td>
							</tr>
						</table>
					</div>
					<div class = "file_section" id = "final">
						<table class = "file_main" width = "80%">
							<tr>
								<th>Current</th>
								<th>Archived</th>
							</tr>
							<tr>
								<td width = "50%" valign="top">
									<?php echo $final_current_table ?>
								</td>
								<td width = "50%" valign="top">
									<?php echo $final_archive_table ?>
								</td>
							</tr>
						</table>
					</div>
				</div>

					</div>
				</div>
				<!-- end file area--> 
				
				<!--schedule area--> 
				<a name = "schedules"></a>
				<div class = "section_area">
				<div class = "section_header">

					<!--area header--> 
					<table width = "100%">
						<tr>
							<td>
								<a href="#schedules" id="schedule_area_click">Schedules</a>
							</td>
							<td align = "right">
<?php					
if ($_SESSION["user_level"] >= 20){
?>
								<a href = "manage_schedules.php?p=<?php echo $project_id ?>">manage schedules</a>
<?php					
}
?>
							</td>
						</tr>
					</table>
				</div>
					<div id = "schedule_area">
					<div class = "error">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fast_track_status_message ?></div>
					<?php echo $phase_and_project_table ?>

					</div>
				</div>
				<!-- end file area--> 
				
				
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>

<div id="popup1" class="popup">
	<form id = "add_file" action = "add_file.php" method = "POST" enctype="multipart/form-data" class="budget">
		<table border = "0">
			<tr>
				<td>
					Project:
				</td>
				<td>
					<div id = "pname">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>
					File Type:
				</td>
				<td>
					<div id = "file_type_text">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>Select File:</td>
				<td><input type="file" name="file" id="file">
				</td>
			</tr>
			<tr>
				<td>File Notes:</td>
				<td>
					<input class = "required" type = "text" name = "file_notes">
				</td>
			</tr>
			<tr>
				<td colspan = "2">
					<input id = "project_id_pop1" type = "hidden" name = "project_id" value = "">
					<input id = "file_type" type = "hidden" name = "file_type" value = "">
					<input type = "submit" value = "add file">
				</td>
			</tr>
		</table>
	</form>
</div>
<div id="bg" class="popup_bg"></div> 


<div id="popup2" class="popup">
	<form id = "approval_email" action = "send_approval_email.php" method = "POST" class="budget">
		<table border = "0">
			<tr>
				<td>
					Project:
				</td>
				<td>
					<?php echo $project_code . " - " . $project_name ?>
				</td>
			</tr>
			<tr>
				<td>
					Schedule:
				</td>
				<td>
					<div id = "schedule_name">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>Task:</td>
				<td>
					<div id = "task_name">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>Approve Specific Document?</td>
				<td>
					<?php echo $approval_document_select ?>
				</td>
			</tr>
			<tr>
				<td>Comment:</td>
				<td>
					<textarea name = "comment" cols = "40">Your approval is required for this task.</textarea>
				</td>
			</tr>
			<tr>
				<td colspan = "2">
					<input id = "user_initials" type = "hidden" name = "user_initials" value = "">
					<input id = "schedule_task_id" type = "hidden" name = "schedule_task_id" value = "">
					<input type = "submit" value = "Send">
				</td>
			</tr>
		</table>
	</form>
</div>

<div id="popup3" class="popup">
	<form id = "edit_file_notes" action = "update_file_notes.php" method = "POST" class="budget">
		<table border = "0">
			<tr>
				<td>
					File:
				</td>
				<td>
					<div id = "pop3_file_name">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>
					Notes:
				</td>
				<td>
					<input type = "text" id = "pop3_file_notes" name = "file_notes" value = "">
				</td>
			</tr>
			
			<tr>
				<td colspan = "2">
					<input id = "pop3_project_file_id" type = "hidden" name = "project_file_id" value = "">
					<input id = "pop3_file_type" type = "hidden" name = "file_type" value = "">
					<input id = "pop3_project_id" type = "hidden" name = "project_id" value = "">
					<input type = "submit" value = "update">
				</td>
			</tr>
		</table>
	</form>
</div>

<div id="popup4" class="popup">
	<form id = "add_file" action = "add_file.php" method = "POST" enctype="multipart/form-data" class="budget">
		<table border = "0">
			<tr>
				<td>
					Project:
				</td>
				<td>
					<div id = "pname4">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>
					File Type:
				</td>
				<td>
					<div id = "file_type_text4">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>Select File:</td>
				<td><input type="file" name="file" id="file">
				</td>
			</tr>
			<tr>
				<td>File Notes:</td>
				<td>
					<input class = "required" type = "text" name = "file_notes">
				</td>
			</tr>
			<tr>
				<td>Asset Network Folder Location:</td>
				<td>
					<input class = "required" type = "text" name = "file_network_folder" size = "50">
				</td>
			</tr>
			<div id = "file_asset_item" style = "display:none;">
			<tr>
				<td>Asset Item:</td>
				<td>
					<?php echo $asset_item_select ?>
				</td>
			</tr>
			</div>
			<tr>
				<td colspan = "2">
					<input id = "project_id_pop4" type = "hidden" name = "project_id" value = "">
					<input id = "file_type_id_pop4" type = "hidden" name = "file_type" value = "">
					<input type = "submit" value = "add file">
				</td>
			</tr>
		</table>
	</form>
</div>

<div id="bg" class="popup_bg"></div> 
</body>
</html>
