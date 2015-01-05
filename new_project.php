<?php 
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
//print $company_id;
$error_message = "";
$active_flag = 1;
if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "Please select a business unit.";
	}
		if ($error_num == 2){
		$error_message = "Duplicate campaign for the chosen business unit and quarter.";
	}
}

$project_manager_id = "";
if (!empty($_GET["pm"])){
	$project_manager_id = $_GET["pm"];
}

$campaign_id = "";
if (!empty($_GET["c"])){
	$campaign_id = $_GET["c"];
}

$audience_id = "";
if (!empty($_GET["a"])){
	$audience_id = $_GET["a"];
}

$product_id = "";
if (!empty($_GET["pr"])){
	$product_id = $_GET["pr"];
}

$campaign_select = get_campaign_code_select($company_id, $campaign_id );
$product_select = get_product_select($company_id, $product_id );
$audience_select = get_audience_select($company_id, $audience_id);
$project_manager_select = get_project_manager_select($company_id, $project_manager_id);
$project_status_select = get_project_status_select($company_id, 0);
$business_unit_owner_select = get_business_unit_owner_select($company_id, "business_unit_owner_id", "Please select", 0, 0);
$aop_activity_type_select = get_aop_activity_select($company_id, 0);
$acd_select = get_user_select($company_id, "acd_id", "Please select", 0, 0);
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Project</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
$(document).ready(function(){
	var cost_center_code = "";
	var business_unit_owner_id = "";
	cost_center_code += $( "#campaign_select option:selected" ).attr('costcenter');
	business_unit_owner_id += $( "#campaign_select option:selected" ).attr('business_unit_owner');
	$( "#cost_center" ).val( cost_center_code );
	$( "#business_unit_owner" ).val( business_unit_owner_id );

    $("#project_form").validate();
	$( ".datepicker" ).datepicker();

	$( "#campaign_select" ).change(function () {
    var cost_center_code = "";
	var business_unit_owner_id = "";
    $( "#campaign_select option:selected" ).each(function() {
      cost_center_code += $( this ).attr('costcenter');
	  business_unit_owner_id += $( this ).attr('business_unit_owner');
    });
	
	if(typeof cost_center_code === 'undefined'){
		cost_center_code = "";
	};
	
    $( "#cost_center" ).val( cost_center_code );
	
	

	//alert(business_unit_owner_id);
	$( "#business_unit_owner" ).val( business_unit_owner_id );
	
  })
  //.change();	
	
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
				<h1>Projects</h1>
				
				New Project:<br><div class = "error"><?php echo $error_message ?></div>
				<form id = "project_form" action = "add_project.php" method = "POST">
					<table class = "form_table">
						<tr>
							<td>Line of Business:</td>
							<td><?php echo $campaign_select ?></td>
						</tr>
						<tr>
							<td>Project Name</td>
							<td><input class = "required" type = "text" name = "project_name"></td>
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
							<td><?php echo $acd_select ?></td>
						</tr>
						<tr>
							<td>Project Requester:</td>
							<td><input type = "text" name = "project_requester"></td>
						</tr>
						<tr>
							<td valign="top">Project Summary:</td>
							<td><textarea name = "project_summary" style="width: 500px; height: 150px;"></textarea></td>
						</tr>
						<tr>
							<td>Cost Center</td>
							<td><input id = "cost_center" type = "text" name = "cost_center"></td>
						</tr>
						<tr>
							<td>Media Budget</td>
							<td><input type = "text" name = "media_budget"></td>
						</tr>
						<tr>
							<td>Production Budget</td>
							<td><input type = "text" name = "production_budget"></td>
						</tr>
						<tr>
							<td>Start Date</td>
							<td><input type = "text" name = "start_date" class="required datepicker"></td>
						</tr>
						<tr>
							<td>End Date</td>
							<td><input type = "text" name = "end_date" class="required datepicker"></td>
						</tr>
						<!--
						<tr>
							<td>Approved AOP Activity?</td>
							<td>Yes<input class = "required" type = "radio" name = "approved_aop_activity" value="1">No<input type = "radio" checked name = "approved_aop_activity" value="0"></td>
						</tr>
						<tr>
							<td>Compliance Project?</td>
							<td>Yes<input type = "radio" name = "compliance_project" value="1">No<input type = "radio" checked name = "compliance_project" value="0"></td>
						</tr>
						-->
						<tr>
							<td>AOP Activity Type</td>
							<td><?php echo $aop_activity_type_select ?></td>
						</tr>
						<tr>
							<td>Upload to APS?</td>
							<td>Yes<input type = "radio" name = "upload_to_aps" value="1">No<input type = "radio" checked name = "upload_to_aps" value="0"></td>
						</tr>

						<tr>
							<td>Project Status</td>
							<td><?php echo $project_status_select ?></td>
						</tr>
						<tr>
							<td>
							<input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
							<input type = "hidden" name = "audience_id" value = "">
							<input type = "submit" value = "Add Project"></td>
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