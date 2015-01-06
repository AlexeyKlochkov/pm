<?php 

function get_pif_email($pif_id){
$company_id =$_SESSION["company_id"];
$arr_pif = get_pif_info($pif_id);
if (!empty($arr_pif)){
	$pif_code = $arr_pif[0]["pif_code"];
	$version = $arr_pif[0]["version"];
	$request_date = convert_mysql_to_datepicker($arr_pif[0]["request_date"]);
	$pif_project_name = $arr_pif[0]["pif_project_name"];
	$desired_delivery_date = convert_mysql_to_datepicker($arr_pif[0]["desired_delivery_date"]);
	$target_in_market_date = convert_mysql_to_datepicker($arr_pif[0]["target_in_market_date"]);
	$expiration_date = convert_mysql_to_datepicker($arr_pif[0]["expiration_date"]);
	$project_budget = $arr_pif[0]["project_budget"];
	$cost_code = $arr_pif[0]["cost_code"];
    $background=$arr_pif[0]["background"];
    $audience=$arr_pif[0]["audience"];
    $objectives=$arr_pif[0]["objectives"];
    $core_message=$arr_pif[0]["core_message"];
    $support_points=$arr_pif[0]["support_points"];
    $required_elem=$arr_pif[0]["required_elem"];
	$project_description = $arr_pif[0]["project_description"];
	$uopx_benefit = $arr_pif[0]["uopx_benefit"];
	$uopx_risk = $arr_pif[0]["uopx_risk"];
	$project_objective = $arr_pif[0]["project_objective"];
	$estimated_total_reach = $arr_pif[0]["estimated_total_reach"];
	$segment_reach_potential_students = $arr_pif[0]["segment_reach_potential_students"];
	$segment_reach_current_students = $arr_pif[0]["segment_reach_current_students"];
	$segment_reach_employee = $arr_pif[0]["segment_reach_employee"];
	$segment_reach_faculty = $arr_pif[0]["segment_reach_faculty"];
	$segment_reach_alumni = $arr_pif[0]["segment_reach_alumni"];
	$segment_reach_wfs = $arr_pif[0]["segment_reach_wfs"];
	$created_date = $arr_pif[0]["created_date"];
	$exec_sponsor = $arr_pif[0]["exec_sponsor_first_name"] . " " . $arr_pif[0]["exec_sponsor_last_name"];
	$marketing_owner_id = $arr_pif[0]["marketing_owner_id"];
	$marketing_owner = $arr_pif[0]["marketing_owner_first_name"] . " " . $arr_pif[0]["marketing_owner_last_name"];
	$line_of_business = $arr_pif[0]["business_unit_name"];
	$line_of_business_id = $arr_pif[0]["business_unit_id"];
	$product_name = $arr_pif[0]["product_name"];
	$product_id = $arr_pif[0]["product_id"];
	$pif_approval_status_id = $arr_pif[0]["pif_approval_status_id"];
	$requester_id = $arr_pif[0]["requester_id"];
	$requester = $arr_pif[0]["requester_first_name"] . " " . $arr_pif[0]["requester_last_name"];
	$segment_quantity_potential_students = $arr_pif[0]["segment_quantity_potential_students"];
	$segment_quantity_current_students = $arr_pif[0]["segment_quantity_current_students"];
	$segment_quantity_employee = $arr_pif[0]["segment_quantity_employee"];
	$segment_quantity_faculty = $arr_pif[0]["segment_quantity_faculty"];
	$segment_quantity_alumni = $arr_pif[0]["segment_quantity_alumni"];
	$aop_activity_type_id = $arr_pif[0]["aop_activity_type_id"];
	$aop_activity_type_name = $arr_pif[0]["aop_activity_type_name"];
}
$total_segment_reach = 0;

if (empty($aop_activity_type_name)){
	$aop_activity_type_name = "AOP type unassigned";
}

$str_segment = "<div class =\"pif_media_table\"><table width = \"300\" border = \"0\"><tr><th>Segment</th><th>Reach</th></tr>";
if ($segment_reach_potential_students == 1){
	$str_segment .= "<tr><td>Potential Students</td><td>" . $segment_quantity_potential_students . "</td></tr>";
	$total_segment_reach = $total_segment_reach + $segment_quantity_potential_students;
}
if ($segment_reach_current_students == 1){
	$str_segment .= "<tr><td>Current Students</td><td>" . $segment_quantity_current_students . "</td></tr>";
	$total_segment_reach = $total_segment_reach + $segment_quantity_current_students;
}
if ($segment_reach_employee == 1){
	$str_segment .= "<tr><td>Employee</td><td>" . $segment_quantity_employee . "</td></tr>";
	$total_segment_reach = $total_segment_reach + $segment_quantity_employee;
}
if ($segment_reach_faculty == 1){
	$str_segment .= "<tr><td>Faculty</td><td>" . $segment_quantity_faculty . "</td></tr>";
	$total_segment_reach = $total_segment_reach + $segment_quantity_faculty;
}
if ($segment_reach_alumni == 1){
	$str_segment .= "<tr><td>Alumni</td><td>" . $segment_quantity_alumni . "</td></tr>";
	$total_segment_reach = $total_segment_reach + $segment_quantity_alumni;
}
$str_segment .= "<tr><td align=\"right\">Total:</td><td>" . $total_segment_reach . "</td></tr></table></div>";
//print $pif_approval_status_id;

$pif_asset_list = "<div class = \"pif_media_table\"><table width = \"500\"><tr><th>Asset Type</th><th>Total Volume</th><th>Type</th></tr>";
$prev_group_name = "";
$arr_pif_assets = get_pif_assets($pif_id);
$total_asset_count = 0;

if (!empty($arr_pif_assets)){
	foreach ($arr_pif_assets as $pif_asset_row){
		$pif_asset_id = $pif_asset_row["pif_asset_id"];
		$asset_quantity = $pif_asset_row["asset_quantity"];
		$pif_asset_type_name = $pif_asset_row["pif_asset_type_name"];
		$pif_asset_type_group_name = $pif_asset_row["pif_asset_type_group_name"];
		//if ($pif_asset_type_group_name <> $prev_group_name){
		//	$pif_asset_list .= "<b>" . $pif_asset_type_group_name . "</b><br>";
		//}
		$pif_asset_list .= "<tr><td>" . $pif_asset_type_name . "</td><td>" . $asset_quantity . "</td><td>" . $pif_asset_type_group_name . "</td></tr>";
		
		$prev_group_name = $pif_asset_type_group_name;
		$total_asset_count = $total_asset_count + $asset_quantity;
	}
}
			
$pif_asset_list .= "<tr><td align=\"right\">Total:</td><td colspan = \"2\">" . $total_asset_count . "</td></tr></table></div>";

$pif_status_select = get_pif_status_select2($company_id, $pif_approval_status_id);
$project_manager_select = get_project_manager_select($company_id, 0);
$aop_activity_type_select = get_aop_activity_select($company_id, $aop_activity_type_id);

//get user list

$arr_users = get_users_for_project2(0);
//print_r($arr_users);
$user_table = "<div id = \"add_people\"><table width = \"250\" class = \"people\">";
if (!empty($arr_users)){
	foreach ($arr_users as $user_row){
		$role_abbrev = $user_row["role_abbrev"];
		$role_name = $user_row["role_name"];
		$current_user_id = $user_row["user_id"];
		$first_name = $user_row["first_name"];
		$last_name = $user_row["last_name"];
		$user_table .= "<tr><td><input type = \"checkbox\" name = \"u-" . $current_user_id . "\" value = \"1\"></td><td>" . $first_name . " " . $last_name . " (" . $role_abbrev . ")</td></tr>";
	}
}

$user_table .= "</table></div>";

//get the most recent active campaign for this business unit and set the campaign select equal to it.
$probable_campaign_id = get_probable_campaign($line_of_business_id);
$campaign_select = get_campaign_select($company_id, $probable_campaign_id);
$acd_select = get_user_select($company_id, "acd_id", "Please select", 0, 0);
$aop_activity_type_select = get_aop_activity_select($company_id, $aop_activity_type_id);
$email_html = "";

$email_html .= "
<html>
<head>

<title>Project Brief Approval</title>
<style>
#mainContent {
    margin: 0 0 0 0px; 
    padding: 0 20px 20px 20px; 
	z-index: 0; 
	font-family: 'Arial', sans-serif !important;
}

#container { 
    width: 600px;
	height: auto;
    background-color: #FFFFFF;
    margin: 0 auto; 
    text-align: left; 
	font-family: 'Arial', sans-serif !important;
	position: relative;
	top: 5px;
}

.pif_form table td, .pif_form table th { 
	padding: 3px 10px; 
	font-family: 'Arial', sans-serif !important;
}
.pif_form table th {
	background-color:#006699; 
	color:#FFFFFF; 
	font-size: 15px; 
	font-weight: bold; 
	border-left: 1px solid #0070A8; 
	font-family: 'Arial', sans-serif !important;
	text-align: left;
	border: none; 
}

.pif_form table td { 
	color: #00557F; 
	border-left: 1px solid #E1EEF4;
	border-bottom: 1px solid #E1EEF4;
	font-family: 'Arial', sans-serif !important;
	font-size: 13px;
	font-weight: normal; 
}

.pif_review_title { 
	font-weight: bold; 
	font-size: 13px;
	font-family: 'Arial', sans-serif !important;
	margin: 0px 0px 0px 0px;
}
.pif_code { 
	font-weight: bold; 
	font-family: 'Arial', sans-serif !important;
	font-size: 20px;
}
</style>
</head>
<body>
<div id = \"page\">
	<div id = \"main\">
		<!--container div tag--> 
		<div id=\"container\"> 
			
			<div id=\"mainContent\"> <!--mainContent div tag--> 
				
					<br>
					<table width = \"100%\">
						<tr>
							<td nowrap>
								<div class = \"pif_code\">Project Brief Received:" .  $pif_code . " </div><div class = \"pif_review_title\">Requested by " . $requester . " - " .  $request_date . "</div>
							</td>
							<td valign=\"top\" align = \"right\">
								<a href = \"http://ac-00019162.apollogrp.edu/pm/edit_pif.php?pid=" . $pif_id . "\">Modify</a>
							</td>
						</tr>
					</table>
					<div class = \"pif_form\">
					<div class = \"pif_admin\">
					</div>
					<table width = \"560\">
						<tr>
							<th colspan = \"2\">Project Information</th>
						</tr>
						<tr>
							<td valign=\"top\">
								<div class = \"pif_review_title\">Project Name:</div>
								" . $pif_project_name . "
							</td>
							<td valign=\"top\">
								<div class = \"pif_review_title\">Project Brief Code:</div>
								" .  $pif_code . "
							</td>
						</tr>
						<tr>
							<td><div class =\"pif_review_title\">Brand Manager:</div>
									" .  $marketing_owner . "
							<td><div class = \"pif_review_title\">Executive Approver:</div>
									" . $exec_sponsor . "
						</tr>
						<tr>
							<td><div class = \"pif_review_title\">Line of Business:</div>
									" . $line_of_business . "
							<td><div class = \"pif_review_title\">Product:</div>
									" . $product_name . "
						</tr>
						<tr>
							<td><div class = \"pif_review_title\">Submit Date:</div>
									" . $request_date . "
							<td><div class = \"pif_review_title\">Desired Delivery Date:</div>
									" .  $desired_delivery_date . "
						</tr>
						<tr>
							<td><div class = \"pif_review_title\">Target In-Market Date:</div>
									" . $target_in_market_date . "
							<td><div class = \"pif_review_title\">Expiration Date:</div>
									" . $expiration_date . "
						</tr>
						<tr>
							<td><div class = \"pif_review_title\">Budget:</div>
									" . $project_budget . "
							<td><div class = \"pif_review_title\">Cost Code:</div>
									" . $cost_code . "
						</tr>
						";
    if ($project_description!="" || $uopx_benefit!="" || $project_objective!="")$email_html.="
                        <tr>
							<th colspan = \"2\">Requirements</th>
						</tr>
						<tr>
							<td colspan = \"2\">
								<div class = \"pif_review_title\">UOPX Benefit:</div>
								" . $uopx_benefit . "
							</td>
						</tr>
						<tr>
							<td colspan = \"2\">
								<div class =\"pif_review_title\">UOPX Risk:</div>
								" . $uopx_risk . "
							</td>
						</tr>
						";
    if ($background!="" || $audience!="" || $objectives!="" || $core_message!=""|| $support_points!="")$email_html.="
<tr>
							<th colspan = \"2\">Messaging</th>
						</tr><tr>
							<td colspan = \"2\">
								<div class = \"pif_review_title\">Background:</div>
								" . $background . "
							</td>
						</tr>
						<tr>
							<td colspan = \"2\">
								<div class = \"pif_review_title\">Audience:</div>
								" . $audience . "
							</td>
						</tr>
						<tr>
							<td colspan = \"2\">
								<div class =\"pif_review_title\">Objectives:</div>
								" . $objectives . "
							</td>
						</tr>
						<tr>
							<td colspan = \"2\">
								<div class =\"pif_review_title\">Core message:</div>
								" . $core_message . "
							</td>
						</tr>
						<tr>
							<td colspan = \"2\">
								<div class =\"pif_review_title\">Support points:</div>
								" . $support_points . "
							</td>
						</tr>";
    $email_html.="
						<tr>
							<td colspan = \"2\">
								<div class = \"pif_review_title\">Segment Reach:</div>
								" . $str_segment . "
							</td>
						</tr>
					
						<tr>
							<th colspan = \"2\">Media Checklist</th>
						</tr>
						<tr>
							<td colspan = \"2\">
								" . $pif_asset_list . "
							</td>
						</tr>
						<tr>
							<td colspan = \"2\">
								<div class =\"pif_review_title\">Required elements:</div>
								" . $required_elem . "
							</td>
						</tr>
					</table>
					
						
				
				
				</div>
			</div> <!--end mainContent div tag--> 

		</div>


	</div>

</div>
</body>
</html>
";
return $email_html;
}
