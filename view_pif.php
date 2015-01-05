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


if (!empty($_GET["p"])){
	$pif_id = $_GET["p"];
}else{
	print "ERROR!!";
}

//$quarter_select = get_quarter_select($campaign_quarter);


$arr_pif = get_pif_info($pif_id);
if (!empty($arr_pif)){
	$pif_code = $arr_pif[0]["pif_code"];
	$version = $arr_pif[0]["version"];
	$request_date = convert_mysql_to_datepicker($arr_pif[0]["request_date"]);
	$pif_project_name = $arr_pif[0]["pif_project_name"];
	
	$pif_project_name = str_replace("\"", "'", $pif_project_name);
	$desired_delivery_date = convert_mysql_to_datepicker($arr_pif[0]["desired_delivery_date"]);
	$target_in_market_date = convert_mysql_to_datepicker($arr_pif[0]["target_in_market_date"]);
	$expiration_date = convert_mysql_to_datepicker($arr_pif[0]["expiration_date"]);
	$project_budget = $arr_pif[0]["project_budget"];
	$cost_code = $arr_pif[0]["cost_code"];
	$project_description = $arr_pif[0]["project_description"];
	$project_description = str_replace("\"", "'", $project_description);
	$uopx_benefit = $arr_pif[0]["uopx_benefit"];
    $background = $arr_pif[0]["background"];
    $audience = $arr_pif[0]["audience"];
    $objectives = $arr_pif[0]["objectives"];
    $core_message = $arr_pif[0]["core_message"];
    $support_points = $arr_pif[0]["support_points"];
    $required_elem=$arr_pif[0]["required_elem"];
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
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Project Brief Approval</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
  <script>
  $(document).ready(function(){
	$(".pif_approval_form").hide();

    $( "#status1" ).change(function() {
	 
	  var selected_status = $("#status1").val();
	   
	   if (selected_status == 3){
		//alert( selected_status );
		$(".pif_approval_form").show();
		$("#status_update").prop('value', 'Add Project');
	   }else{
		$(".pif_approval_form").hide();
		$("#status_update").prop('value', 'Update Status');
	   }
	});
	
	$("#aop_activity_type_select").change(function() {
		this.form.submit();
	});
	
	$( "#pif_approval" ).validate({
	  rules: {
		task_rate: {
		  required: false,
		  number: true
		}
	  }
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
				
					<br>
					<table width = "100%">
						<tr>
							<td nowrap>
								<div class = "pif_code"><?php echo $pif_code . " (" . $aop_activity_type_name . ")" ?></div> Requested by <?php echo $requester . " - " .  $request_date ?>
							</td>
							<td valign="top" align = "right">
								<a href = "pif_list.php">Project Brief List</a>
<?php
if ($_SESSION["user_level"] >25){
?>							<form action = "update_pif_aop_status2.php" method = "POST">
								Select AOP type: <?php echo $aop_activity_type_select ?>
								<input type = "hidden" name = "pif_id" value = "<?php echo $pif_id ?>">
								</form>

<?php
}
?>

							</td>
						</tr>
					</table>
					<div class = "pif_form">
					<div class = "pif_admin">
<?php
if ($_SESSION["user_level"] >25){
?>					
					<form action = "update_pif_status.php" method = "POST" id = "pif_approval">
					<table width = "850">
						<tr>
							<td valign="top">

								Change Status:<br><?php echo $pif_status_select ?>

							</td>
							<td valign="top">

								Approver Notes:<br>

								<textarea name = "approver_notes" maxlength = "5000" cols = "33" rows = "2"></textarea>
							</td>
							<td valign="top">
								<div class = "pif_approval_form">
								Project Manager: <?php echo $project_manager_select ?><br>
								ACD: <?php echo $acd_select ?>
								</div>
							</td>
							<td valign="top">
								<div class = "pif_approval_form">
								Campaign:<br>
								<?php echo $campaign_select ?>
								</div>
							</td>
						</tr>
						<tr>

							<td align="left" valign="top">
							<input id = "status_update" type = "submit" value = "Update Status">
							</td>
							<td valign="top" nowrap>
								<input type = "checkbox" name = "send_email" checked value = "1"> Send Email?<br>
								<input type = "checkbox" name = "send_requester" checked value = "1"> To: Requester (<?php echo $requester ?>)<br>
								<input type = "checkbox" name = "send_bm" value = "1"> To: Brand Manager (<?php echo $marketing_owner ?>)<br>
								<div class = "pif_approval_form">
									<input type = "checkbox" name = "send_pm" checked value = "1"> To: Project Manager<br>
								</div>
							</td>
							<td valign="top">
								<div class = "pif_approval_form">
								AOP Activity Type: <?php echo $aop_activity_type_select ?>
							</div>
							</td>
							<td valign="top">
								<div class = "pif_approval_form">
								Project People:<br>
								<?php echo $user_table ?>
								</div>
							</td>
						</tr>
					</table>
					<input type = "hidden" name = "pif_id" value = "<?php echo $pif_id ?>">
					<input type = "hidden" name = "project_name" value = "<?php echo $pif_project_name ?>">
					<input type = "hidden" name = "line_of_business_id" value = "<?php echo $line_of_business_id ?>">
					<input type = "hidden" name = "product_id" value = "<?php echo $product_id ?>">
					<input type = "hidden" name = "requester_id" value = "<?php echo $requester_id ?>">
					<input type = "hidden" name = "project_description" value = "<?php echo $project_description ?>">
					<input type = "hidden" name = "cost_code" value = "<?php echo $cost_code ?>">
					<input type = "hidden" name = "request_date" value = "<?php echo $request_date ?>">
					<input type = "hidden" name = "desired_delivery_date" value = "<?php echo $desired_delivery_date ?>">
					<input type = "hidden" name = "production_budget" value = "<?php echo $project_budget ?>">
					<input type = "hidden" name = "approved_by" value = "<?php echo $user_id ?>">
					<input type = "hidden" name = "business_unit_owner_id" value = "<?php echo $marketing_owner_id ?>">
					<input type = "hidden" name = "pif_code" value = "<?php echo $pif_code ?>">
					<input type = "hidden" name = "marketing_owner_id" value = "<?php echo $marketing_owner_id  ?>">
					
					</form>
					
<?php
}
?>	
					</div>
					<table width = "750">
						<tr>
							<th colspan = "2">Project Information</th>
						</tr>
						<tr>
							<td>
								<div class = "pif_review_title">Project Name:</div>
								<?php echo $pif_project_name ?>
							</td>
							<td>
								<div class = "pif_review_title">Project Brief Code:</div>
								<?php echo $pif_code ?>
							</td>
						</tr>
						<tr>
							<td><div class = "pif_review_title">Brand Manager:</div>
									<?php echo $marketing_owner ?>
							<td><div class = "pif_review_title">Executive Approver:</div>
									<?php echo $exec_sponsor ?>
						</tr>
						<tr>
							<td><div class = "pif_review_title">Line of Business:</div>
									<?php echo $line_of_business ?>
							<td><div class = "pif_review_title">Product:</div>
									<?php echo $product_name ?>
						</tr>
						<tr>
							<td><div class = "pif_review_title">Submit Date:</div>
									<?php echo $request_date ?>
							<td><div class = "pif_review_title">Desired Delivery Date:</div>
									<?php echo $desired_delivery_date ?>
						</tr>
						<tr>
							<td><div class = "pif_review_title">Target In-Market Date:</div>
									<?php echo $target_in_market_date ?>
							<td><div class = "pif_review_title">Expiration Date:</div>
									<?php echo $expiration_date ?>
						</tr>
						<tr>
							<td><div class = "pif_review_title">Budget:</div>
									<?php echo $project_budget ?>
							<td><div class = "pif_review_title">Cost Code:</div>
									<?php echo $cost_code ?>
						</tr>
                        <?php if ( $uopx_benefit!="" || $uopx_risk!=""):?>
						<tr>
							<th colspan = "2">Requirements</th>
						</tr>
						<tr>
							<td colspan = "2">
								<div class = "pif_review_title">Project Description:</div>
								<?php echo $project_description ?>
							</td>
						</tr>
						<tr>
							<td colspan = "2">
								<div class = "pif_review_title">UOPX Benefit:</div>
								<?php echo $uopx_benefit; ?>
							</td>
						</tr>
						<tr>
							<td colspan = "2">
								<div class = "pif_review_title">UOPX Risk:</div>
								<?php echo $uopx_risk; ?>
							</td>
						</tr>
						<tr>
							<td colspan = "2">
								<div class = "pif_review_title">Project Objective including estimated Return on Investment:</div>
								<?php echo $project_objective ?>
							</td>
						</tr>
                        <?php endif;?>
                        <?php if ($background!="" || $audience!="" || $objectives!="" || $core_message!="" || $support_points!=""):?>
                            <tr>
                                <th colspan = "2">Messaging</th>
                            </tr>
                            <tr>
                                <td colspan = "2">
                                    <div class = "pif_review_title">Background:</div>
                                    <?php echo $background ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan = "2">
                                    <div class = "pif_review_title">Audience:</div>
                                    <?php echo $audience ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan = "2">
                                    <div class = "pif_review_title">Objectives:</div>
                                    <?php echo $objectives ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan = "2">
                                    <div class = "pif_review_title">Core message:</div>
                                    <?php echo $core_message ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan = "2">
                                    <div class = "pif_review_title">Support points:</div>
                                    <?php echo $support_points ?>
                                </td>
                            </tr>
                        <?php endif;?>
						<?php
						if (!isset($_GET["is_bm"]) || $_GET["is_bm"]!=1):?>
						<tr>
							<td colspan = "2">
								<div class = "pif_review_title">Segment Reach:</div>
								<?php echo $str_segment ?>
							</td>
						</tr>
					
						<tr>
							<th colspan = "2">Media Checklist</th>
						</tr>
						<tr>
							<td colspan = "2">
								<?php echo $pif_asset_list ?>
							</td>
						</tr>
                        <tr>
								<td colspan = "2">
									<div class = "pif_review_title">Required elements:</div>
									<?php echo $required_elem ?>
								</td>
							</tr>

						<?php endif;?>
					</table>
				</div>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>
