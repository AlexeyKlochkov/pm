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
		$error_message = "An error occurred.";
	}
		if ($error_num == 2){
		$error_message = "Business Unit Updated.";
	}
}
if (!empty($_GET["b"])){
	$business_unit_id = $_GET["b"];
}

$arr_business_unit_info = get_business_unit_info($business_unit_id, $company_id);
//print_r($arr_phase_info );
$business_unit_name = $arr_business_unit_info[0]["business_unit_name"];
$business_unit_abbrev = $arr_business_unit_info[0]["business_unit_abbrev"];
$default_cost_code = $arr_business_unit_info[0]["default_cost_code"];
$business_unit_owner_id = $arr_business_unit_info[0]["business_unit_owner_id"];
$active = $arr_business_unit_info[0]["active"];
$isMRI=$arr_business_unit_info[0]["is_mri"];

$business_unit_owner_select = get_user_select($company_id, "business_unit_owner_id", "Please select", $business_unit_owner_id, 0);

if ($active == 1){
	$active_checked = "checked";
}else{
	$active_checked = "";
}
if ($isMRI == 1){
    $isMRIChecked = "checked";
}else{
    $isMRIChecked = "";
}


?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit Business Unit</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#business_unit_form" ).validate();

	
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
				<h1>Business Units</h1>
				<a href = "new_business_unit.php">All Business Units</a><br><br>
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							Edit Business Unit:<form id = "business_unit_form" action = "update_business_unit.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Business Unit Name:</td>
									<td><input class = "required" type = "text" name = "business_unit_name" value = "<?php echo $business_unit_name ?>"></td>
								</tr>
								<tr>
									<td>Business Unit Abbreviation:</td>
									<td><input size="2" maxlength="4" class = "required" type = "text" name = "business_unit_abbrev" value = "<?php echo $business_unit_abbrev ?>"></td>
								</tr>
								<tr>
									<td>Default Cost Code:</td>
									<td><input size="10" maxlength="25" type = "text" name = "default_cost_code" value = "<?php echo $default_cost_code ?>"></td>
								</tr>
								<tr>
									<td>Brand Manager:</td>
									<td><?php echo $business_unit_owner_select ?></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "business_unit_id" value = "<?php echo $business_unit_id ?>">
									<input type = "submit" value = "Update Business Unit"></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>Active:</td>
									<td><input type = "checkbox" <?php echo $active_checked ?> name = "active" value = "1"></td>
								</tr>
                                <tr>
                                    <td>Is MRI:</td>
                                    <td><input type = "checkbox" <?php echo $isMRIChecked ?> name = "ismri" value = "1"></td>
                                </tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
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