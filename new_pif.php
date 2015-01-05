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
		$error_message = "Role name or abbreviation exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Role Added.";
	}
}

$marketing_owner_select = get_user_select($company_id, "marketing_owner_id", "Please select", 0, 1);
$exec_sponsor_select = get_user_select($company_id, "exec_sponsor_id", "Please select", 0, 1);
$exec_sponsor_select = get_user_select_by_role_abbrev($company_id, "exec_sponsor_id", "Please select", 0, 1, "VPBM");
$business_unit_select = get_business_unit_select($company_id, 0);
$product_select = get_product_select($company_id, 0);

$dm_table = "<table width = \"100%\"><tr><td><b>Asset Type</b></td><td><b>Total Volume</b></td></tr>";
$digital_table = "<table width = \"100%\"><tr><td><b>Asset Type</b></td><td><b>Total Volume</b></td></tr>";
$offline_table = "<table width = \"100%\"><tr><td><b>Asset Type</b></td><td><b>Total Volume</b></td></tr>";

$arr_pif_asset_types = get_pif_asset_types($company_id, 1);
//print_r ($arr_pif_asset_types);
if (!empty($arr_pif_asset_types)){
	foreach ($arr_pif_asset_types as $pat_row){
		$pif_asset_type_id = $pat_row["pif_asset_type_id"];
		$pif_asset_type_group_id = $pat_row["pif_asset_type_group_id"];
		$pif_asset_type_name = $pat_row["pif_asset_type_name"];
		if ($pif_asset_type_group_id == "1"){
			$dm_table .= "<tr><td><input type = \"checkbox\" name = \"pat-" . $pif_asset_type_id . "\" value = \"1\" class = \"checkvalue\" id = \"cv_" . $pif_asset_type_id . "\"> " . $pif_asset_type_name . "</td><td><input type = \"text\" size = \"6\" maxlength = \"12\" name = \"patvol-" . $pif_asset_type_id . "\" id = \"v_" . $pif_asset_type_id . "\"  class = \"volume number\"><div class = \"error\" id = \"e_" . $pif_asset_type_id . "\"></td></tr>\n";
		}
		if ($pif_asset_type_group_id == "2"){
			$digital_table .= "<tr><td><input type = \"checkbox\" name = \"pat-" . $pif_asset_type_id . "\" value = \"1\" class = \"checkvalue\" id = \"cv_" . $pif_asset_type_id . "\"> " . $pif_asset_type_name . "</td><td><input type = \"text\" size = \"6\" maxlength = \"12\" name = \"patvol-" . $pif_asset_type_id . "\" id = \"v_" . $pif_asset_type_id . "\"  class = \"volume number\"><div class = \"error\" id = \"e_" . $pif_asset_type_id . "\"></td></td></tr>\n";
		}
		if ($pif_asset_type_group_id == "3"){
			$offline_table .= "<tr><td><input type = \"checkbox\" name = \"pat-" . $pif_asset_type_id . "\" value = \"1\" class = \"checkvalue\" id = \"cv_" . $pif_asset_type_id . "\"> " . $pif_asset_type_name . "</td><td><input type = \"text\" size = \"6\" maxlength = \"12\" name = \"patvol-" . $pif_asset_type_id . "\" id = \"v_" . $pif_asset_type_id . "\"  class = \"volume number\"><div class = \"error\" id = \"e_" . $pif_asset_type_id . "\"></td></td></tr>\n";
		}
	}
}
$dm_table .= "</table>";
$digital_table .= "</table>";
$offline_table .= "</table>";

$request_date = date("m/d/Y");

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New PIF</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#pif_form" ).validate({
		rules: {
			task_rate: {
			  required: false,
			  number: true
			}
		
		}
	});

	//if the checkbox is checked, make sure the reach/volume field is filled out
	$( "#pif_form" ).submit(function( event ) {
		//alert( "Handler for .submit() called." );
		var num_errors = 0;
		$(".checkvalue").each(function() {
		var current_id = $(this).attr("id");
		
		if ($(this).prop('checked')){
			//alert(current_id + " - checked!");
			
			var arr_current_id = current_id.split("_");
			var check_item = arr_current_id[1];
			var field_to_check_id = "v_" + check_item;
			var  field_to_check_value = $("#" + field_to_check_id).val();
			//alert (field_to_check_value);

			if(field_to_check_value < 1){
				$( "#e_" +  check_item).text( "Please enter a number greater than zero." );
				num_errors ++;
			}else{
				$( "#e_" +  check_item).text( "" );
			}

		}

	});
	  
	  if (num_errors > 0){
		//focus on the last one
		return false;
		$("#" + field_to_check_id).focus();
	  }
	  
		//make sure there are some assets requested
		 var volume_sum = 0;
		 $('.volume').each(function() {
			volume_sum += Number($(this).val());
		 });
		
		if(volume_sum == 0){
			//alert(volume_sum);
			$("#asset_area").focus();
			$("#asset_area").html( "<font color = 'red'><b>Please select at least one asset to produce.</b></font>" );
			return false;
		}

	  
	});

	
	$('#role_abbrev').keyup(function(){
		this.value = this.value.toUpperCase();
	});
	
	
	
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


		<!--container div tag--> 
		<div id="container"> 
			
			<div id="mainContent"> <!--mainContent div tag--> 
				<h1>Marketing Project Intake Form</h1>
				
					<table border = "0" width = "90%">
						<tr>
							<td valign="top">
							<form id = "pif_form" action = "add_pif.php" method = "POST">
							<div class = "pif_form">
							<table>
								<tr>
									<th colspan = "2">
										I. PROCESS CHECKLIST
									</th>
								</tr>
								<tr>
									<td colspan = "2">
										<ul>
											<li><b>Step 1:</b> Complete and submit this form by EOD Thursday. PIFs are viewed by Marketing Board every Monday at 10am.</li>
											<li><b>Step 2:</b> Receive "approved/unapproved" status email following Monday's meeting.</li>
											<li><b>Step 3:</b> If approved, you will receive an email with your assigned PM including next steps such as defining specs, creative briefing, resources & procurement contracts.</li>
										</ul>
									</td>
								</tr>
								<tr>
									<th colspan = "2">
										II. PROJECT INFORMATION
									</th>
								<tr>
								<tr>
									<td  colspan = "2">Project Name:<br><input class = "required" type = "text" name = "project_name" size = "100" maxlength="500"></td>
								</tr>
								<tr>
									<td>
										Brand Manager (primary  control reviewer):<br><?php echo $marketing_owner_select ?>
									</td>
									<td>
										Executive Approver:<br><?php echo $exec_sponsor_select ?>
									</td>
								</tr>
								<tr>
									<td>
										Line of Business:<br><?php echo $business_unit_select ?>
									</td>
									<td>
										Product:<br><?php echo $product_select ?>
									</td>
								</tr>
								<tr>
									<td>
										Request Date:<br><b><?php echo $request_date ?></b>
									</td>
									<td>
										Desired Delivery Date:<br><input class = "datepicker" type = "text" name = "desired_delivery_date" value ="" size = "6" readonly="readonly" required>
									</td>
								</tr>
								<tr>
									<td>
										Target In-Market Date:<br><input class = "datepicker" type = "text" name = "target_in_market_date" value ="" size = "6" readonly="readonly" required>
									</td>
									<td>
										Expiration Date:<br><input class = "datepicker" type = "text" name = "expiration_date" value ="" size = "6" readonly="readonly" required>
									</td>
								</tr>
								<tr>
									<td>
										What is your budget?:<br><input class = "required number" type = "text" name = "budget" size = "10" maxlength="10">
									</td>
									<td>
										Cost Code(s):<br><input class = "required" type = "text" name = "cost_code" size = "11" maxlength="11">
									</td>
								</tr>
								<tr>
									<th colspan = "2">
										III. REQUIREMENTS
									</th>
								</tr>
								<tr>
									<td  colspan = "2">Project Description: (The What)<br><textarea class = "required" type = "text" name = "project_description" rows="6" cols = "100" maxlength="5000"></textarea></td>
								</tr>
								<tr>
									<td>UOPX Benefit (What does UOPX benefit from doing this work)<br><textarea class = "required" type = "text" name = "uopx_benefit" rows="6" cols = "45" maxlength="5000"></textarea></td>
									<td>UOPX Risk (What does UOPX risk from not doing this work)<br><textarea class = "required" type = "text" name = "uopx_risk" rows="6" cols = "45" maxlength="5000"></textarea></td>
								</tr>
								<tr>
									<td  colspan = "2">Project Objective including estimated Return on Investment: (The Why & ROI)<br><textarea class = "required" type = "text" name = "project_objective" rows="6" cols = "100" maxlength="5000"></textarea></td>
								</tr>
								<tr>
									<td colspan = "2">
										<div class = "pif_media_table">
										<table width = "50%">
											<tr>
												<th>
													Segment
												</th>
												<th>
													Total Reach 
												</th>
											</tr>
											<tr>
												<td>
													<input type = "checkbox" name = "segment_reach_potential_students" value = "1" class = "checkvalue" id = "cv_srps"> Potential Students
												</td>
												<td>
													<input class = "number" type = "text" name = "segment_quantity_potential_students" size = "10" maxlength="12" id = "v_srps"  number>
													<div class = "error" id = "e_srps"></div>
												</td>
											</tr>
											<tr>
												<td>
													<input type = "checkbox" name = "segment_reach_current_students" value = "1" class = "checkvalue" id = "cv_srcs"> Current Students
												</td>
												<td>
													<input class = "number" type = "text" name = "segment_quantity_current_students" size = "10" maxlength="12" id = "v_srcs"  number>
													<div class = "error" id = "e_srcs"></div>
												</td>
											</tr>
											<tr>
												<td>
													<input type = "checkbox" name = "segment_reach_employee" value = "1"  class = "checkvalue" id = "cv_sre"> Employee
												</td>
												<td>
													<input class = "number" type = "text" name = "segment_quantity_employee" size = "10" maxlength="12"  id = "v_sre"  number>
													<div class = "error" id = "e_sre"></div>
												</td>
											</tr>
											<tr>
												<td>
													<input type = "checkbox" name = "segment_reach_faculty" value = "1"  class = "checkvalue" id = "cv_srf"> Faculty
												</td>
												<td>
													<input class = "number" type = "text" name = "segment_quantity_faculty" size = "10" maxlength="12"  id = "v_srf"  number>
													<div class = "error" id = "e_srf"></div>
												</td>
											</tr>
											<tr>
												<td>
													<input type = "checkbox" name = "segment_reach_alumni" value = "1"  class = "checkvalue" id = "cv_sra"> Alumni
												</td>
												<td>
													<input class = "number" type = "text" name = "segment_quantity_alumni" size = "10" maxlength="12"  id = "v_sra"  number>
													<div class = "error" id = "e_sra"></div>
												</td>
											</tr>
										</table>
										</div>
									</td>
								</tr>
								
								<tr>
									<th colspan = "2">
										IV.  MEDIA CHECKLIST * The Standard Lead Times below are general guidelines – each project will be reviewed by a pm to confirm the final timeline, scope and resources available. The clock starts ticking at user-accepted creative brief .
									</th>
								</tr>
								<tr>
									<td colspan = "2"
										<div id = "asset_area" class = "error"></div>
									</td>
								</tr>
								<tr>
									<td colspan = "2">
										<div class = "pif_media_table">
										<table width = "100%">
											<tr>
												<th>
													Direct Mail & Print (*lead time 2-8 weeks)
												</th>
												<th>
													Digital ($lead time: 3-4 weeks)
												</th>
												<th>
													Offline ($lead time 7-8 weeks)
												</th>
											</tr>
											<tr>
												<td valign = "top">
													<?php echo $dm_table ?>
												</td>
												<td valign = "top">
													<?php echo $digital_table ?>
												</td>
												<td valign = "top">
													<?php echo $offline_table ?>
												</td>
											</tr>
										</table>
										</div>
									</td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "hidden" name = "request_date" value = "<?php echo $request_date ?>">
									<input type = "hidden" name = "version" value = "">
									<input type = "submit" value = "Submit PIF"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
							</div>
						</td>

					</tr>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>