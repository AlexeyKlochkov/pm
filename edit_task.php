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
		$error_message = "Task name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Task Updated.";
	}
}
if (!empty($_GET["t"])){
	$task_id = $_GET["t"];
}

$arr_task_info = get_task_info($task_id);
//print_r($arr_task_info );
$task_name = $arr_task_info[0]["task_name"];
$role_id  = $arr_task_info[0]["role_id"];
$task_rate = $arr_task_info[0]["task_rate"];
$active = $arr_task_info[0]["active"];
$is_approval = $arr_task_info[0]["is_approval"];

if ($active == 1){
	$active_checked = "checked";
}else{
	$active_checked = "";
}

if ($is_approval == 1){
	$is_approval_checked = "checked";
}else{
	$is_approval_checked = "";
}

$role_select = get_role_select($company_id, $role_id);


?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit Task</title>
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
				<h1>Edit Task</h1>
				<a href = "new_task.php">All Tasks</a><br>
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							Edit Task:<form id = "task_form" action = "update_task.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Task Name:</td>
									<td><input class = "required" type = "text" name = "task_name" value = "<?php echo $task_name ?>"></td>
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
									<td><input type = "text" name = "task_rate" value = "<?php echo $task_rate ?>"></td>
								</tr>
<?php
}
?>
								<tr>
									<td>Approval Task?:</td>
									<td><input <?php echo $is_approval_checked ?> type = "checkbox" value = "1" name = "is_approval"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "task_id" value = "<?php echo $task_id ?>">
									<input type = "submit" value = "Update Task"></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>Active:</td>
									<td><input type = "checkbox" <?php echo $active_checked ?> name = "active" value = "1"></td>
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