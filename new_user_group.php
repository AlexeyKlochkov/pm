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
		$error_message = "User group name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "User group added.";
	}
}

$arr_user_group = get_user_groups($company_id);
$user_group_table = "<table width = \"500\" class = \"budget\"><tr><th colspan = \"3\">Current User Groups</th></tr>";
if (!empty($arr_user_group)){
	foreach ($arr_user_group as $user_group_row){
		$user_group_id = $user_group_row["user_group_id"];
		$user_group_name = $user_group_row["user_group_name"];
		$user_group_table .= "<tr><td width = \"60%\">" . $user_group_name . "</td>";
		$user_group_table .= "<td><a href = \"edit_user_group.php?ug=" . $user_group_id . "\">edit</a></td>";
		$user_group_table .= "<td><a href = \"activate_phase.php?a=2&ug=" . $user_group_id . "\">del</a></td></tr>";
	
	}
}
$user_group_table .= "</table>";



?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New User Group</title>
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
				<h1>User Groups</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New User Group:<form id = "user_group_form" action = "add_user_group.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Group Name:</td>
									<td><input class = "required" type = "text" name = "user_group_name" size = "50"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "hidden" name = "created_by" value = "<?php echo $user_id ?>">
									<input type = "submit" value = "Add Group"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $user_group_table ?>
							<br><br>

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