<?php 
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
//print $company_id;

if($_SESSION["user_level"] < 30){
		$location = "Location: loggedout.php";
		header($location) ;
}
$error_message = "";
$active_flag = 1;
if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "User added.";
	}
		if ($error_num == 2){
		$error_message = "An error occurred. This could be a duplicate user - did you check inactive users below?";
	}
}

$role_select = get_role_select($company_id, 0);

$javascript_initial_array = "var arr_initials = [";

$arr_users = get_users_by_company($company_id, 1);
$user_table = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"5\">Current Employees</th></tr><tr><th>Name</th><th>Role</th><th>Initials</th><th colspan = \"2\">&nbsp;</th></tr>";
$i=1;
if (!empty($arr_users)){
	foreach ($arr_users as $user_row){
		$user_id = $user_row["user_id"];
		$first_name = $user_row["first_name"];
		$last_name = $user_row["last_name"];
		$role_abbrev = $user_row["role_abbrev"];
		$initials = $user_row["initials"];
		$user_name = $first_name . " " . $last_name;
		$user_table .= "<tr><td>" . $user_name . "</td><td>" . $role_abbrev . "</td><td>" . $initials . "</td><td><a href = \"edit_user.php?u=" . $user_id . "\">edit</a></td><td><a href = \"activate_user.php?a=2&u=" . $user_id . "\">del</a></td></tr>";
		$javascript_initial_array .= "\"" . $initials . "\"";
		if ($i<>count($arr_users)){
			$javascript_initial_array .= ",";
		}
		$i++;
	
	}
}
$user_table .= "</table>";


$arr_inactive_users = get_users_by_company($company_id, 0);
$user_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"4\">Inactive Users</th></tr><tr><th>Name</th><th>Role</th><th>Initials</th><th colspan = \"1\">&nbsp;</th></tr>";
if (!empty($arr_inactive_users)){
	foreach ($arr_inactive_users as $user_row){
		$user_id = $user_row["user_id"];
		$first_name = $user_row["first_name"];
		$last_name = $user_row["last_name"];
		$role_abbrev = $user_row["role_abbrev"];
		$initials = $user_row["initials"];
		$user_name = $first_name . " " . $last_name . " (" . $role_abbrev . ")";
		$user_table2 .= "<tr><td>" . $user_name . "</td><td>" . $role_abbrev . "</td><td>" . $initials . "</td><td><a href = \"activate_user.php?a=1&u=" . $user_id . "\">activate</a></td></tr>";
		$javascript_initial_array .= ",\"" . $initials . "\"";
	}
}
$user_table2 .= "</table>";

$javascript_initial_array .= "];\n";
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Manage Users</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
jQuery.validator.addMethod("checkInitials",function(value) {
	
	<?php echo $javascript_initial_array ?>
    var val=$("#initials").val();
	//this needs to return false if the initials are in the array
	if ($.inArray(val, arr_initials) > -1) 
		{
			return false;
		}else{
			return true;
		}
}, "Initials are not unique.");

jQuery.validator.classRuleSettings.checkInitials = { checkInitials: true };

  $(document).ready(function(){
	$( "#user_form" ).validate({
  rules: {
    email: {
      required: true,
      email: true
    },
	initials: {
		checkInitials: true
	}
  }

});



	$('#initials').keyup(function(){
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
				<h1>Manage Users</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New User:<form id = "user_form" action = "add_user.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>First Name:</td>
									<td><input class = "required" type = "text" name = "first_name"></td>
								</tr>
								<tr>
									<td>Last Name:</td>
									<td><input class = "required" type = "text" name = "last_name"></td>
								</tr>
								<tr>
									<td>System User Name:</td>
									<td><input class = "required" type = "text" name = "system_user_name"></td>
								</tr>
								<tr>
									<td>Email:</td>
									<td><input class = "required" type = "text" name = "email"></td>
								</tr>
								<tr>
									<td>Initials (must be unique):</td>
									<td><input id = "initials" class = "required" type = "text" name = "initials" maxlength="4" size = "2"><br><div id = "initial_label"></td>
								</tr>
								<tr>
									<td>Role:</td>
									<td>
										<?php echo $role_select ?>
									</td>
								</tr>
								<tr>
									<td>Project Manager:</td>
									<td><input type = "checkbox" name = "is_project_manager" value = "1"></td>
								</tr>
								<tr>
									<td>APS Admin:</td>
									<td><input type = "checkbox" name = "is_aps_admin" value = "1"></td>
								</tr>
								<tr>
									<td>User Level:</td>
									<td><select name = "user_level">
											<option value = "10">User</option>
											<option value = "20">Project Manager</option>
											<option value = "30">Admin</option>
										   </select>
									
									</td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add User" id = "submit"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $user_table ?>
							<br><br>
							<?php echo $user_table2 ?>
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