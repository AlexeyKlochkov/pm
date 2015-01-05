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
		$error_message = "Task name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Task Added.";
	}
}

$role_select = get_role_select($company_id, 0);

$arr_task = get_tasks($company_id, 1);
$task_table = "<table width = \"400\" class = \"budget\"><tr><th colspan = \"4\">Current Tasks</th></tr>";
if (!empty($arr_task)){
	foreach ($arr_task as $task_row){
		$task_id = $task_row["task_id"];
		$task_name = $task_row["task_name"];
		$is_approval = $task_row["is_approval"];
		$str_approval = "&nbsp;";
		if ($is_approval == 1){
			$str_approval = "Approval";
		}
		$task_table .= "<tr><td>" . $task_name . "</td><td align=\"left\">" . $str_approval . "</td><td><a href = \"edit_task.php?t=" . $task_id . "\">edit</a></td><td><a href = \"activate_task.php?a=2&t=" . $task_id . "\">del</a></td></tr>";
	
	}
}
$task_table .= "</table>";


$arr_retired_tasks = get_tasks($company_id, 0);
$task_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"2\">Retired Tasks</th></tr>";
if (!empty($arr_retired_tasks)){
	foreach ($arr_retired_tasks as $task_row){
		$task_id = $task_row["task_id"];
		$task_name = $task_row["task_name"];
		$task_table2 .= "<tr><td>" . $task_name . "</td><td><a href = \"activate_task.php?a=1&t=" . $task_id . "\">activate</a></td></tr>";
	
	}
}
$task_table2 .= "</table>";

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Manage Tasks</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#task_form" ).validate({
  rules: {
    task_rate: {
      required: false,
      number: true
    }
  }
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
				<h1>Manage Tasks</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Task:<form id = "task_form" action = "add_task.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Task Name:</td>
									<td><input class = "required" type = "text" name = "task_name"></td>
								</tr>
								<tr>
									<td>Default Role:</td>
									<td>
										<?php echo $role_select ?>
									</td>
								</tr>
<?php
if ($_SESSION["user_level"] > 30){
?>
								<tr>
									<td>Task Rate:</td>
									<td><input type = "text" name = "task_rate"></td>
								</tr>
<?php
}
?>
								<tr>
									<td>Approval Task?:</td>
									<td><input type = "checkbox" value = "1" name = "is_approval"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
<?php
if ($_SESSION["user_level"] <= 30){
?>
									<input type = "hidden" name = "task_rate" value = "0">
<?php
}
?>
									<input type = "submit" value = "Add Task"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $task_table ?>
							<br><br>
							<?php echo $task_table2 ?>
						</td>
					</tr>
				</table>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>