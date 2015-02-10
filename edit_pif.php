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

if (!empty($_GET["pid"])){
	$pif_id = $_GET["pid"];
}else{
	$location = "loggedout.php";
	header(location);
}

$arr_pif = get_pif_info($pif_id);
$pif_project_name = $arr_pif[0]["pif_project_name"];
$marketing_owner_id = $arr_pif[0]["marketing_owner_id"];
$exec_sponsor_id = $arr_pif[0]["exec_sponsor_id"];
$business_unit_id = $arr_pif[0]["business_unit_id"];
$product_id = $arr_pif[0]["product_id"];
$pif_code = $arr_pif[0]["pif_code"];
$required_elem=$arr_pif[0]["required_elem"];
$background = $arr_pif[0]["background"];
$audience = $arr_pif[0]["audience"];
$objectives = $arr_pif[0]["objectives"];
$core_message = $arr_pif[0]["core_message"];
$support_points = $arr_pif[0]["support_points"];
//$request_date = convert_mysql_to_datepicker($arr_pif[0]["request_date"]);
$desired_delivery_date = convert_mysql_to_datepicker($arr_pif[0]["desired_delivery_date"]);
$target_in_market_date = convert_mysql_to_datepicker($arr_pif[0]["target_in_market_date"]);
$expiration_date = convert_mysql_to_datepicker($arr_pif[0]["expiration_date"]);
$budget = $arr_pif[0]["project_budget"];
$cost_code = $arr_pif[0]["cost_code"];
$project_description = $arr_pif[0]["project_description"];
$uopx_benefit = $arr_pif[0]["uopx_benefit"];
$uopx_risk = $arr_pif[0]["uopx_risk"];
$background = $arr_pif[0]["background"];
$audience = $arr_pif[0]["audience"];
$objectives = $arr_pif[0]["objectives"];
$core_message = $arr_pif[0]["core_message"];
$support_points = $arr_pif[0]["support_points"];
$project_objective = $arr_pif[0]["project_objective"];
$estimated_total_reach = $arr_pif[0]["estimated_total_reach"];
$segment_reach_potential_students = $arr_pif[0]["segment_reach_potential_students"];
$segment_reach_current_students = $arr_pif[0]["segment_reach_current_students"];
$segment_reach_employee = $arr_pif[0]["segment_reach_employee"];
$segment_reach_faculty = $arr_pif[0]["segment_reach_faculty"];
$segment_reach_alumni = $arr_pif[0]["segment_reach_alumni"];
$segment_reach_wfs = $arr_pif[0]["segment_reach_wfs"];
$orig_pif_id = $arr_pif[0]["orig_pif_id"];
if(empty($orig_pif_id )){
	$orig_pif_id = $pif_id;
}
$version = $arr_pif[0]["version"];
$aop_activity_type_id = $arr_pif[0]["aop_activity_type_id"];
$segment_quantity_potential_students = $arr_pif[0]["segment_quantity_potential_students"];
$segment_quantity_current_students = $arr_pif[0]["segment_quantity_current_students"];
$segment_quantity_employee = $arr_pif[0]["segment_quantity_employee"];
$segment_quantity_faculty = $arr_pif[0]["segment_quantity_faculty"];
$segment_quantity_alumni = $arr_pif[0]["segment_quantity_alumni"];

$request_date = date("m/d/Y");

$segment_reach_potential_students_checked = "";
if ($segment_reach_potential_students == 1){
	$segment_reach_potential_students_checked = "checked";
}

$segment_reach_current_students_checked = "";
if ($segment_reach_current_students == 1){
	$segment_reach_current_students_checked = "checked";
}

$segment_reach_employee_checked = "";
if ($segment_reach_employee == 1){
	$segment_reach_employee_checked = "checked";
}

$segment_reach_faculty_checked = "";
if ($segment_reach_faculty == 1){
	$segment_reach_faculty_checked = "checked";
}

$segment_reach_alumni_checked = "";
if ($segment_reach_alumni == 1){
	$segment_reach_alumni_checked = "checked";
}

$segment_reach_wfs_checked = "";
if ($segment_reach_wfs == 1){
	$segment_reach_wfs_checked = "checked";
}

$marketing_owner_select = get_user_select($company_id, "marketing_owner_id", "Please select", $marketing_owner_id , 1);
$exec_sponsor_select = get_user_select_by_role_abbrev($company_id, "exec_sponsor_id", "Please select", $exec_sponsor_id, 1, "VPBM");
$business_unit_select = get_business_unit_select($company_id, $business_unit_id);
$product_select = get_product_select($company_id, $product_id );

$pif_asset_type_list = array();
$arr_pif_assets = get_pif_assets($pif_id);
if (!empty($arr_pif_assets)){
	foreach ($arr_pif_assets as $pif_asset_type_row){
		$pif_asset_type_id_current = $pif_asset_type_row["pif_asset_type_id"];
		array_push($pif_asset_type_list, $pif_asset_type_id_current );
	}
}

//print_r($pif_asset_type_list);

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
		$pif_asset_type_checked = "";
		$pif_asset_quantity = "";
		if (in_array($pif_asset_type_id, $pif_asset_type_list)){
			$pif_asset_type_checked = " checked ";
			$pif_asset_quantity = get_quantity_for_asset_type($pif_id, $pif_asset_type_id);
		}
		if ($pif_asset_type_group_id == "1"){
			$dm_table .= "<tr><td><input type = \"checkbox\" name = \"pat-" . $pif_asset_type_id . "\" class = \"checkvalue\" id = \"cv_" . $pif_asset_type_id . "\" value = \"1\"" . $pif_asset_type_checked . "> " . $pif_asset_type_name . "</td><td><input type = \"text\" id = \"v_" . $pif_asset_type_id . "\" class = \"volume number\" size = \"6\" maxlength = \"12\" name = \"patvol-" . $pif_asset_type_id . "\" value = \"" . $pif_asset_quantity . "\"><div class = \"error\" id = \"e_" . $pif_asset_type_id . "\"></td></tr>\n";
		}
		if ($pif_asset_type_group_id == "2"){
			$digital_table .= "<tr><td><input type = \"checkbox\" class = \"checkvalue\" id = \"cv_" . $pif_asset_type_id . "\" name = \"pat-" . $pif_asset_type_id . "\" value = \"1\"" . $pif_asset_type_checked . "> " . $pif_asset_type_name . "</td><td><input type = \"text\" id = \"v_" . $pif_asset_type_id . "\" class = \"volume number\" size = \"6\" maxlength = \"12\" name = \"patvol-" . $pif_asset_type_id . "\" value = \"" . $pif_asset_quantity . "\"><div class = \"error\" id = \"e_" . $pif_asset_type_id . "\"></td></tr>\n";
		}
		if ($pif_asset_type_group_id == "3"){
			$offline_table .= "<tr><td><input type = \"checkbox\" class = \"checkvalue\" id = \"cv_" . $pif_asset_type_id . "\" name = \"pat-" . $pif_asset_type_id . "\" value = \"1\"" . $pif_asset_type_checked . "> " . $pif_asset_type_name . "</td><td><input type = \"text\" id = \"v_" . $pif_asset_type_id . "\" class = \"volume number\" size = \"6\" maxlength = \"12\" name = \"patvol-" . $pif_asset_type_id . "\" value = \"" . $pif_asset_quantity . "\"><div class = \"error\" id = \"e_" . $pif_asset_type_id . "\"></td></tr>\n";
		}
	}
}
$dm_table .= "</table>";
$digital_table .= "</table>";
$offline_table .= "</table>";



?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit PIF</title>
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
		//alert(current_id + " - checked!");
		if ($(this).prop('checked')){
			
			
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
									<td  colspan = "2">Project Name:<br><input class = "required" type = "text" name = "project_name" size = "100" maxlength="500" value = "<?php echo $pif_project_name ?>"></td>
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
										Desired Delivery Date:<br><input class = "datepicker" type = "text" name = "desired_delivery_date" size = "6" readonly="readonly" required value = "<?php echo $desired_delivery_date ?>">
									</td>
								</tr>
								<tr>
									<td>
										Target In-Market Date:<br><input class = "datepicker" type = "text" name = "target_in_market_date" size = "6" readonly="readonly" required value = "<?php echo $target_in_market_date ?>">
									</td>
									<td>
										Expiration Date:<br><input class = "datepicker" type = "text" name = "expiration_date" size = "6" readonly="readonly" required value = "<?php echo $expiration_date ?>">
									</td>
								</tr>
								<tr>
									<td>
										What is your budget?:<br><input class = "required number" type = "text" name = "budget" size = "10" maxlength="10" value = "<?php echo $budget ?>">
									</td>
									<td>
										Cost Code(s):<br><input class = "required" type = "text" name = "cost_code" size = "11" maxlength="11" value = "<?php echo $cost_code ?>">
									</td>
								</tr>
                                <?php $cur_num="III";?>
                                <?php if ( $uopx_benefit!="" || $uopx_risk!=""):
                                    $cur_num="IV";
                                    ?>
								<tr>
									<th colspan = "2">
										<?php echo $cur_num?>. REQUIREMENTS
									</th>
								</tr>
								<tr>
									<td>UOPX Benefit (What does UOPX benefit from doing this work)<br><textarea class = "required" type = "text" name = "uopx_benefit" rows="6" cols = "45" maxlength="5000"><?php echo $uopx_benefit ?></textarea></td>
									<td>UOPX Risk (What does UOPX risk from not doing this work)<br><textarea class = "required" type = "text" name = "uopx_risk" rows="6" cols = "45" maxlength="5000"><?php echo $uopx_risk ?></textarea></td>
								</tr>
                                <?php endif;?>
                                <?php if ($background!="" || $audience!="" || $objectives!="" || $core_message!="" || $support_points!=""):
                                    ?>
                                    <tr>
                                        <th colspan = "2"><?php echo $cur_num; if ($cur_num=="IV") $cur_num="V"; else $cur_num="IV";?>. Messaging</th>
                                    </tr>
									<tr>
										<td  colspan = "2">Background<br><textarea class = "required" type = "text" name = "background" rows="6" cols = "140" maxlength="5000"><?php echo $background?></textarea></td>
									</tr>
									<tr>
										<td>Core message<br><textarea class = "required" type = "text" name = "core_message" rows="6" cols = "60" maxlength="5000"><?php echo $core_message?></textarea></td>
										<td>Audience<br><textarea class = "required" type = "text" name = "audience" rows="6" cols = "60" maxlength="5000"><?php echo $audience?></textarea></td>
									</tr>
									<tr>
										<td>Objectives<br><textarea class = "required" type = "text" name = "objectives" rows="6" cols = "60" maxlength="5000"><?php echo $objectives?></textarea></td>
										<td>Support Points<br><textarea class = "required" type = "text" name = "support_points" rows="6" cols = "60" maxlength="5000"><?php echo $support_points?></textarea></td>
									</tr>
                                <?php endif;?>
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
													<input type = "checkbox" name = "segment_reach_potential_students" value = "1" class = "checkvalue" id = "cv_srps" <?php echo $segment_reach_potential_students_checked ?>> Potential Students
												</td>
												<td>
													<input class = "number" type = "text" name = "segment_quantity_potential_students" size = "10" maxlength="12" id = "v_srps"  value = "<?php echo $segment_quantity_potential_students ?>">
													<div class = "error" id = "e_srps"></div>
												</td>
											</tr>
											<tr>
												<td>
													<input type = "checkbox" name = "segment_reach_current_students" value = "1" class = "checkvalue" id = "cv_srcs" <?php echo $segment_reach_current_students_checked ?>> Current Students
												</td>
												<td>
													<input class = "number" type = "text" name = "segment_quantity_current_students" size = "10" maxlength="12" id = "v_srcs" value = "<?php echo $segment_quantity_current_students ?>">
													<div class = "error" id = "e_srcs"></div>
												</td>
											</tr>
											<tr>
												<td>
													<input type = "checkbox" name = "segment_reach_employee" value = "1"  class = "checkvalue" id = "cv_sre" <?php echo $segment_reach_employee_checked ?>> Employee
												</td>
												<td>
													<input class = "number" type = "text" name = "segment_quantity_employee" size = "10" maxlength="12"  id = "v_sre" value = "<?php echo $segment_quantity_employee ?>">
													<div class = "error" id = "e_sre"></div>
												</td>
											</tr>
											<tr>
												<td>
													<input type = "checkbox" name = "segment_reach_faculty" value = "1"  class = "checkvalue" id = "cv_srf" <?php echo $segment_reach_faculty_checked ?>> Faculty
												</td>
												<td>
													<input class = "number" type = "text" name = "segment_quantity_faculty" size = "10" maxlength="12"  id = "v_srf" value = "<?php echo $segment_quantity_faculty ?>">
													<div class = "error" id = "e_srf"></div>
												</td>
											</tr>
											<tr>
												<td>
													<input type = "checkbox" name = "segment_reach_alumni" value = "1"  class = "checkvalue" id = "cv_sra" <?php echo $segment_reach_alumni_checked ?>> Alumni
												</td>
												<td>
													<input class = "number" type = "text" name = "segment_quantity_alumni" size = "10" maxlength="12"  id = "v_sra"  value = "<?php echo $segment_quantity_alumni ?>">
													<div class = "error" id = "e_sra"></div>
												</td>
											</tr>
										</table>
										
										</div>
									</td>
								</tr>
								<tr>
									<th colspan = "2">
										<?php echo $cur_num?>.  MEDIA CHECKLIST * The Standard Lead Times below are general guidelines � each project will be reviewed by a pm to confirm the final timeline, scope and resources available. The clock starts ticking at user-accepted creative brief .
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
									<td  colspan = "2">Required elements<br><textarea class = "required" type = "text" name = "required_elem" rows="6" cols = "130" maxlength="5000"><?php echo $required_elem?></textarea></td>
									</td>
								</tr>
								<tr>
									<td>
										<input type = "hidden" name = "project_description" value = "<?php echo $project_description ?>">
										<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
										<input type = "hidden" name = "version" value = "<?php echo $version ?>">
										<input type = "hidden" name = "pif_id" value = "<?php echo $pif_id ?>">
										<input type = "hidden" name = "old_pif_code" value = "<?php echo $pif_code ?>">
										<input type = "hidden" name = "request_date" value = "<?php echo $request_date ?>">
										<input type = "hidden" name = "orig_pif_id" value = "<?php echo $orig_pif_id ?>">
										<input type = "hidden" name = "orig_business_unit_id" value = "<?php echo $business_unit_id ?>">
										<input type = "hidden" name = "aop_activity_type_id" value = "<?php echo $aop_activity_type_id ?>">
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