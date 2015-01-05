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
		$error_message = "Business Unit name or abbreviation exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Business Unit Added.";
	}
}

$user_select = get_user_select($company_id, "owner_id", "Please select", 0, 0);
$arr_business_unit = get_business_units($company_id, 1);
//print_r($arr_business_unit);
$business_unit_table = "<table width = \"650\" class = \"budget\"><tr><th colspan = \"6\">Current Business Units</th></tr><tr><th>Business Unit</th><th>Abbrev.</th><th>Cost Code</th><th>Brand Manager</th><th colspan = \"2\">&nbsp;</th></tr>";
if (!empty($arr_business_unit)){
	foreach ($arr_business_unit as $business_unit_row){
		$business_unit_id = $business_unit_row["business_unit_id"];
		$business_unit_name = $business_unit_row["business_unit_name"];
		$business_unit_abbrev = $business_unit_row["business_unit_abbrev"];
		$default_cost_code = $business_unit_row["default_cost_code"];
		$business_unit_owner_name = $business_unit_row["first_name"] . " " . $business_unit_row["last_name"];;
		$business_unit_table .= "<tr><td>" . $business_unit_name . "</td><td>" . $business_unit_abbrev . "</td><td nowrap>" . $default_cost_code . "</td><td>" . $business_unit_owner_name . "</td><td><a href = \"edit_business_unit.php?a=2&b=" . $business_unit_id . "\">edit</a></td><td><a href = \"activate_business_unit.php?a=2&b=" . $business_unit_id . "\">del</a></td></tr>";
	
	}
}
$business_unit_table .= "</table>";

$arr_retired_business_units = get_business_units($company_id, 2);
$business_unit_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Retired Business Units</th></tr>";
if (!empty($arr_retired_business_units)){
	foreach ($arr_retired_business_units as $business_unit_row){
		$business_unit_id = $business_unit_row["business_unit_id"];
		$business_unit_name = $business_unit_row["business_unit_name"];
		$business_unit_abbrev = $business_unit_row["business_unit_abbrev"];
		$business_unit_table2 .= "<tr><td>" . $business_unit_name . "</td><td>" . $business_unit_abbrev . "</td><td><a href = \"activate_business_unit.php?a=1&b=" . $business_unit_id . "\">activate</a></td></tr>";
	
	}
}
$business_unit_table2 .= "</table>";

$business_unit_owner_select = get_user_select($company_id, "business_unit_owner_id", "Please select", 0, 0);

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Business Unit</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#business_unit_form" ).validate({
  rules: {
    task_rate: {
      required: false,
      number: true
    }
  }
});
	
	$('#business_unit_abbrev').keyup(function(){
		this.value = this.value.toUpperCase();
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
				<h1>Business Unit</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Business Unit:<form id = "business_unit_form" action = "add_business_unit.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Business Unit Name:</td>
									<td><input class = "required" type = "text" name = "business_unit_name"></td>
								</tr>
								<tr>
									<td>Role Abbreviation:</td>
									<td><input id = "business_unit_abbrev" class = "required" type = "text" name = "business_unit_abbrev" size="2" maxlength="4"></td>
								</tr>
								<tr>
									<td>Default Cost Code:</td>
									<td><input id = "default_cost_code" type = "text" name = "default_cost_code" size="10" maxlength="25"></td>
								</tr>
								<tr>
									<td>Brand Manager:</td>
									<td><?php echo $business_unit_owner_select ?></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add Business Unit"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $business_unit_table ?>
							<br><br>
							<?php echo $business_unit_table2 ?>
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