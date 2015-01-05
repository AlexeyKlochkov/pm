<?php 
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
date_default_timezone_set('America/Los_Angeles');
//print $company_id;
$error_message = "";
$active_flag = 1;
$company_id = 2;
$wif_type_id = 0;
if (!empty($_GET["wtid"])){
	$wif_type_id=$_GET["wtid"];
}
//Hardcoded WIF form type!!
if ($wif_type_id==12){
	include "loggedin.php";
	$dest="add_bm.php";
}
else $dest="add_wif.php";
$wif_type_select = get_wif_type_select_web_only($company_id, $wif_type_id);
$request_date = date("m/d/Y");
$marketing_owner_select = get_user_select($company_id, "marketing_owner_id", "Please select", 0, 1);
$exec_sponsor_select = get_user_select($company_id, "exec_sponsor_id", "Please select", 0, 1);
$exec_sponsor_select = get_user_select_by_role_abbrev($company_id, "exec_sponsor_id", "Please select", 0, 1, "VPBM");
$business_unit_select = get_business_unit_select($company_id, 0);
$product_select = get_product_select($company_id, 0);
?>
<!DOCTYPE HTML>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New WIF</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#wif_form" ).validate({
		rules: {
			task_rate: {
			  required: false,
			  number: true
			}
		
		}
	});
	
	$( ".datepicker" ).datepicker();
	$(document).on("click",".addFile",function(){

            $(this).parent().parent().after($('<tr id="files_up"> <td > <input name="filesToUpload[]" id="filesToUpload" type="file" /> <input type="button" name="increase" value="+" class="addFile"/> </td> </tr>'));
            $(".addFile").hide();
            $(".addFile").last().show();
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


		<!--container div tag--> 
		<div id="container"> 
			
			<div id="mainContent"> <!--mainContent div tag--> 
				<h1>Web Intake Form</h1>
				
					<table border = "0" width = "90%">
						<tr>
							<td valign="top">
							<form id = "wif_form" action = "<?php echo $dest?>" method = "POST" enctype="multipart/form-data">
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
											<li><b>Step 1:</b> Complete and submit this form.</li>
										</ul>
									</td>
								</tr>
								<?php if ($wif_type_id!=12):?>
								<tr>
									<th colspan = "2">
										II. REQUEST INFORMATION
									</th>
								</tr>
								<tr>
									<td  colspan = "2">Your Full Name:<br><input class = "required" type = "text" name = "requester_name" size = "100" maxlength="100"></td>
								</tr>
								<tr>
									<td  colspan = "2">Your Email:<br><input class = "required email" type = "text" name = "requester_email" size = "100" maxlength="100"></td>
								</tr>
								<tr>
									<td  colspan = "2">Project Name:<br><input class = "required" type = "text" name = "wif_name" size = "100" maxlength="200"></td>
								</tr>
								<tr>
									<td  colspan = "2">
										Project Type:<br><?php echo $wif_type_select ?>
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
								<?php elseif ($wif_type_id==12):?>
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
								<?php endif;?>

								<tr>
									<th colspan = "2">
										III. REQUIREMENTS
									</th>
								</tr>
								<tr>
									<td  colspan = "2">Request Description: (The What / include URL(s))<br><textarea class = "required" type = "text" name = "wif_description" rows="6" cols = "100" maxlength="5000"></textarea></td>
								</tr>

								<tr>
									<th colspan = "2">
										IV.  ATTACHMENTS
									</th>
								</tr>
								<tr id="files_up">
                                            <td >
                                                <input name="filesToUpload[]" id="filesToUpload" type="file" />
						<input type="button" name="increase" value="+" class="addFile" />                                                
                                            </td>
                                        </tr>
									
									
									
								
								<tr>
									<td><br>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "hidden" name = "request_date" value = "<?php echo $request_date ?>">
									<input type = "hidden" name = "version" value = "">
									<input type = "checkbox" name = "emergency_wif" value = "1">Check this box if this project is an <font color = "red">EMERGENCY</font> and someone needs to address it immediately.<br><br>
									<input type = "submit" value = "Submit WIF"></td>
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
