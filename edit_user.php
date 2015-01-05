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
		$error_message = "User updated.";
	}
		if ($error_num == 2){
		$error_message = "Update error - Make sure initials are unique.";
	}
}
if (!empty($_GET["u"])){
	$user_id = $_GET["u"];
}else{
	$user_id = 0;
}

if ($_SESSION["user_level"] < 20){
	$location = "Location: loggedout.php";
	header($location) ;
}
$arr_user_info = get_user_info($user_id);
$first_name = $arr_user_info[0]["first_name"];
$last_name = $arr_user_info[0]["last_name"];
$email = $arr_user_info[0]["email"];
$role_id = $arr_user_info[0]["role_id"];
$initials = $arr_user_info[0]["initials"];
$is_project_manager = $arr_user_info[0]["is_project_manager"];
$is_aps_admin = $arr_user_info[0]["is_aps_admin"];
$user_level = $arr_user_info[0]["user_level"];
$system_user_name = $arr_user_info[0]["system_user_name"];
$title = $arr_user_info[0]["title"];
$active = $arr_user_info[0]["active"];

if ($active == 1){
	$active_checked = "checked";
}else{
	$active_checked = "";
}

$is_project_manager_checked = "";
$upload_checked_no = "";
if ($is_project_manager == 1){
	$is_project_manager_checked = "checked";
}

$is_aps_admin_checked = "";
if ($is_aps_admin == 1){
	$is_aps_admin_checked = "checked";
}


$role_select = get_role_select($company_id, $role_id);

$user_level_select = get_user_level_select($company_id, $user_level);

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit User</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#user_form").validate();
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
				<a href = "manage_users.php">Manage Users</a><br>
				<h1>Edit User</a></h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				<form id = "user_form" action = "update_user.php" method = "POST">
					<table class = "form_table">
						<tr>
							<td>User Name&nbsp;&nbsp;</td>
							<td><h3><?php echo $system_user_name ?></h3></td>
						</tr>
						<tr>
							<td>First Name</td>
							<td><input class = "required" type = "text" name = "first_name" value = "<?php echo $first_name ?>"></td>
						</tr>
						<tr>
							<td>Last Name</td>
							<td><input class = "required" type = "text" name = "last_name" value = "<?php echo $last_name ?>"></td>
						</tr>
						<tr>
							<td>Email</td>
							<td><input class = "required email" type = "text" name = "email" value = "<?php echo $email ?>"></td>
						</tr>
						<tr>
							<td>System User Name</td>
							<td><input class = "required" type = "text" name = "system_user_name" value = "<?php echo $system_user_name ?>"></td>
						</tr>
						<tr>
							<td>Role:</td>
							<td><?php echo $role_select ?></td>
						</tr>
						<tr>
							<td>Initials:</td>
							<td><input class = "required" type = "text" name = "initials" value = "<?php echo $initials ?>" maxlength="4" size = "2"></td>
						</tr>
						<tr>
							<td valign="top">Is Project Manager</td>
							<td><input type = "checkbox" name = "is_project_manager" value = "1" <?php echo $is_project_manager_checked ?>></td>
						</tr>
						<tr>
							<td valign="top">Is APS Admin</td>
							<td><input type = "checkbox" name = "is_aps_admin" value = "1" <?php echo $is_aps_admin_checked ?>></td>
						</tr>
						<tr>
							<td valign="top">User Level</td>
							<td><?php echo $user_level_select ?></td>
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
							<input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
							<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
							<input type = "submit" value = "Update User"></td>
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