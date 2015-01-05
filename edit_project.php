<?php 
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
//print $company_id;
$error_message = "";

if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "Project updated.";
	}
		if ($error_num == 2){
		$error_message = "Update error.";
	}
}
if (!empty($_GET["p"])){
	$project_id = $_GET["p"];
}else{
	$project_id = 0;
}


$arr_project_info = get_project_info($project_id);
$campaign_code = $arr_project_info[0]["campaign_code"];
$campaign_id = $arr_project_info[0]["campaign_id"];
$campaign_description = $arr_project_info[0]["campaign_description"];
$project_code = $arr_project_info[0]["project_code"];
$project_name = $arr_project_info[0]["project_name"];
$product_id = $arr_project_info[0]["product_id"];
$audience_id = $arr_project_info[0]["audience_id"];
$project_manager_id = $arr_project_info[0]["project_manager_id"];
$acd_id = $arr_project_info[0]["acd_id"];
$project_status_id = $arr_project_info[0]["project_status_id"];
$project_summary = $arr_project_info[0]["project_summary"];
$cost_center = $arr_project_info[0]["cost_center"];
$project_budget_media = $arr_project_info[0]["media_budget"];
$project_budget_production = $arr_project_info[0]["production_budget"];
$start_date = $arr_project_info[0]["start_date"];
$start_date = translate_mysql_todatepicker($start_date);
$end_date = $arr_project_info[0]["end_date"];
$end_date = translate_mysql_todatepicker($end_date);
$approved_aop_activity = $arr_project_info[0]["approved_aop_activity"];
$compliance_project = $arr_project_info[0]["compliance_project"];
$upload_to_aps = $arr_project_info[0]["upload_to_aps"];
$project_active = $arr_project_info[0]["active"];
$business_unit_owner_id = $arr_project_info[0]["business_unit_owner_id"];
$project_requester = $arr_project_info[0]["project_requester"];
$aop_activity_type_id = $arr_project_info[0]["aop_activity_type_id"];

if ($project_active == 1){
	$active_checked = "checked";
}else{
	$active_checked = "";
}



$upload_checked_yes = "";
$upload_checked_no = "";
if ($upload_to_aps == 1){
	$upload_checked_yes = "checked";
}else{
	$upload_checked_no = "checked";
}

$compliance_checked_yes = "";
$compliance_checked_no = "";
if ($compliance_project == 1){
	$compliance_checked_yes = "checked";
}else{
	$compliance_checked_no = "checked";
}

$aop_checked_yes = "";
$aop_checked_no = "";
if ($approved_aop_activity == 1){
	$aop_checked_yes = "checked";
}else{
	$aop_checked_no = "checked";
}

$campaign_select = get_campaign_code_select_all($company_id, $campaign_id );
$product_select = get_product_select($company_id, $product_id);
$audience_select = get_audience_select($company_id, $audience_id);
$project_manager_select = get_project_manager_select($company_id, $project_manager_id);
$project_status_select = get_project_status_select($company_id, $project_status_id );
$business_unit_owner_select = get_user_select($company_id, "business_unit_owner_id", "Please select", $business_unit_owner_id, 0);
$acd_select = get_user_select($company_id, "acd_id", "Please select", $acd_id, 0);
$aop_activity_type_select = get_aop_activity_select($company_id, $aop_activity_type_id);
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit Project</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#project_form").validate();
	$( ".datepicker" ).datepicker();
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
				<h1>Edit <a href = "manage_project.php?p=<?php echo $project_id  ?>">Project <?php echo $project_code  ?></a></h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				<form id = "project_form" action = "update_project.php" method = "POST">
					<table class = "form_table">
						<tr>
							<td>Line of Business:</td>
							<td><?php echo $campaign_select ?></td>
						</tr>
						<tr>
							<td>Project Name</td>
							<td><input class = "required" type = "text" name = "project_name" value = "<?php echo $project_name ?>"></td>
						</tr>
						<tr>
							<td>Product:</td>
							<td><?php echo $product_select ?></td>
						</tr>
						<tr>
							<td>Brand Manager:</td>
							<td><?php echo $business_unit_owner_select ?></td>
						</tr>
						<tr>
							<td>Project Manager:</td>
							<td><?php echo $project_manager_select ?></td>
						</tr>
						<tr>
							<td>ACD:</td>
							<td><?php echo $acd_select  ?></td>
						</tr>
						
						
						<tr>
							<td>Project Requester:</td>
							<td><input type = "text" name = "project_requester" value = "<?php echo $project_requester ?>"></td>
						</tr>


						<tr>
							<td valign="top">Project Summary:</td>
							<td><textarea name = "project_summary" style="width: 500px; height: 150px;"><?php echo $project_summary ?></textarea></td>
						</tr>
						<tr>
							<td>Cost Center</td>
							<td><input type = "text" name = "cost_center" value = "<?php echo $cost_center ?>"></td>
						</tr>
						<tr>
							<td>Media Budget</td>
							<td><input type = "text" name = "media_budget" value = "<?php echo $project_budget_media ?>"></td>
						</tr>
						<tr>
							<td>Production Budget</td>
							<td><input type = "text" name = "production_budget" value = "<?php echo $project_budget_production ?>"></td>
						</tr>
						<tr>
							<td>Start Date</td>
							<td><input type = "text" name = "start_date" class="required datepicker" value = "<?php echo $start_date ?>"></td>
						</tr>
						<tr>
							<td>End Date</td>
							<td><input type = "text" name = "end_date" class="required datepicker" value = "<?php echo $end_date ?>"></td>
						</tr>
						<!--
						<tr>
							<td>Approved AOP Activity?</td>
							<td>Yes<input class = "required" type = "radio" <?php echo $aop_checked_yes ?> name = "approved_aop_activity" value="1">No<input type = "radio" <?php echo $aop_checked_no ?> name = "approved_aop_activity" value="0"></td>
						</tr>
						<tr>
							<td>Compliance Project?</td>
							<td>Yes<input class = "required" type = "radio" <?php echo $compliance_checked_yes ?> name = "compliance_project" value="1">No<input type = "radio" <?php echo $compliance_checked_no ?> name = "compliance_project" value="0"></td>
						</tr>
						-->
						<tr>
							<td>AOP Activity Type</td>
							<td><?php echo $aop_activity_type_select ?></td>
						</tr>
						<tr>
							<td>Upload to APS?</td>
							<td>Yes<input type = "radio" name = "upload_to_aps" <?php echo $upload_checked_yes ?> value="1">No<input type = "radio" <?php echo $upload_checked_no ?> name = "upload_to_aps" value="0"></td>
						</tr>
						<tr>
							<td>Project Status</td>
							<td><?php echo $project_status_select ?></td>
						</tr>
						<tr>
							<td>
								Active:
							</td>
							<td>
								<input type = "checkbox" name = "active" value = "1" <?php echo $active_checked ?>>
							</td>
						</tr>
						<tr>
							<td>
							<input type = "hidden" name = "audience_id" value = "">
							<input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
							<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
							<input type = "submit" value = "Update Project"></td>
							<td>&nbsp;</td>
						</tr>
					</table>
				
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