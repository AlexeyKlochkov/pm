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
		$error_message = "User group name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "User group updated.";
	}
		if ($error_num == 4){
		$error_message = "Deletion error occurred.";
	}
}
if (!empty($_GET["ug"])){
	$user_group_id = $_GET["ug"];
}

$arr_user_group_info = get_user_group_info($user_group_id);
//print_r($arr_phase_info );
$user_group_name = $arr_user_group_info[0]["user_group_name"];

$arr_user_group_members = get_user_group_members($user_group_id);
$group_member_table = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Group Members</th></tr>";

if (!empty($arr_user_group_members)){
	foreach ($arr_user_group_members as $member_row){
		$user_group_member_id = $member_row["user_group_member_id"];
		$user_id = $member_row["user_id"];
		$first_name = $member_row["first_name"];
		$last_name = $member_row["last_name"];
		$role_name = $member_row["role_name"];
		$role_abbrev = $member_row["role_abbrev"];

		$group_member_table .= "<tr><td>" . $first_name . " " . $last_name . "</td><td>" . $role_name . " (" . $role_abbrev . ")</td><td><a href = \"del_group_member.php?ugmid=" . $user_group_member_id . "&ug=" . $user_group_id . "\">del</a></td></tr>";
	}
}else{
	$group_member_table .= "<tr><td colspan = \"3\">No members.</td></tr>";
}
$group_member_table .= "</table>";


$potential_group_members = get_users_for_group($user_group_id, $company_id);
//print_r($arr_users);
$current_role_abbrev = "";
$user_table = "<form action = \"add_group_member.php\" method = \"post\"><table width = \"250\" class = \"budget\"><tr><td colspan = \"2\" align=\"right\"><input type = \"submit\" value = \"add group members\"></td></tr><tr><th>User</th><th>Add</th></tr>";
if (!empty($potential_group_members)){
	foreach ($potential_group_members as $user_row){
		$role_abbrev = $user_row["role_abbrev"];
		$role_name = $user_row["role_name"];
		$user_id = $user_row["user_id"];
		$first_name = $user_row["first_name"];
		$last_name = $user_row["last_name"];
		
		//if ($current_role_abbrev <> $role_abbrev){
		//	$user_table .= "<tr><td colspan = \"2\"><b>" . $role_name . "</b></td></tr>";
		//}
		$user_table .= "<tr><td>" . $first_name . " " . $last_name . " (" . $role_abbrev . ")</td><td><input type = \"hidden\" name = \"user_id\" value = \"" . $user_id . "\"><input type = \"checkbox\" name = \"uid-" . $user_id . "\"></td></tr>";
		$current_role_abbrev = $role_abbrev;
	}
}
$user_table .= "<tr><td colspan = \"2\" align=\"right\"><input type = \"hidden\" name = \"user_group_id\" value = \"" . $user_group_id . "\"><input type = \"submit\" value = \"add group members\"></td></tr></table></form>";

//print_r($arr_user_group_members);
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit User Group</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#user_group_form" ).validate();

	
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
				<h1>User Group <?php echo $user_group_name ?></h1>
				<a href = "new_user_group.php">All User Groups</a><br><br>
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							<form id = "user_group_form" action = "update_user_group.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>User Group Name:</td>
									<td><input class = "required" type = "text" name = "user_group_name" value = "<?php echo $user_group_name ?>" size = "50"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "user_group_id" value = "<?php echo $user_group_id ?>">
									<input type = "submit" value = "Update User Group Name"></td>
									<td>&nbsp;</td>
								</tr>
								</table></form>
						
							</td>
							<td>&nbsp;
							</td>
						</tr>
					<tr>
						<td width = "60" valign="top">
							<?php echo $group_member_table ?>
						</td>
						<td  valign="top">
							<?php echo $user_table ?>
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