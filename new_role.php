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

$arr_role = get_roles2($company_id, 1);
$role_table = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Current Roles</th></tr><tr><th>Role Name</th><th>Abbrev.</th><th>&nbsp;</th></tr>";
if (!empty($arr_role)){
	foreach ($arr_role as $role_row){
		$role_id = $role_row["role_id"];
		$role_name = $role_row["role_name"];
		$role_abbrev = $role_row["role_abbrev"];
		$role_table .= "<tr><td>" . $role_name . "</td><td>" . $role_abbrev . "</td><td><a href = \"activate_role.php?a=2&r=" . $role_id . "\">del</a></td></tr>";
	
	}
}
$role_table .= "</table>";

$arr_retired_roles = get_roles2($company_id, 0);
$role_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Retired Roles</th></tr>";
if (!empty($arr_retired_roles)){
	foreach ($arr_retired_roles as $role_row){
		$role_id = $role_row["role_id"];
		$role_name = $role_row["role_name"];
		$role_abbrev = $role_row["role_abbrev"];
		$role_table2 .= "<tr><td>" . $role_name . "</td><td>" . $role_abbrev . "</td><td><a href = \"activate_role.php?a=1&r=" . $role_id . "\">activate</a></td></tr>";
	
	}
}
$role_table2 .= "</table>";

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Role</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#role_form" ).validate({
  rules: {
    task_rate: {
      required: false,
      number: true
    }
  }
});
	
	$('#role_abbrev').keyup(function(){
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
				<h1>Roles</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Role:<form id = "role_form" action = "add_role.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Role Name:</td>
									<td><input class = "required" type = "text" name = "role_name"></td>
								</tr>
								<tr>
									<td>Role Abbreviation:</td>
									<td><input id = "role_abbrev" class = "required" type = "text" name = "role_abbrev" size="2" maxlength="4"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add Role"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $role_table ?>
							<br><br>
							<?php echo $role_table2 ?>
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