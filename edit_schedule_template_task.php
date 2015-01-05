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
		$error_message = "An error occurred..";
	}
		if ($error_num == 2){
		$error_message = "Schedule Template Task Updated.";
	}
}
if (!empty($_GET["stid"])){
	$schedule_template_id = $_GET["stid"];
}

if (!empty($_GET["sttid"])){
	$schedule_template_tasks_id = $_GET["sttid"];
}

$arr_schedule_template_task_info = get_schedule_template_task_info($schedule_template_tasks_id);
//print_r($arr_phase_info );
$task_id = $arr_schedule_template_task_info[0]["task_id"];
$manager_role_id = $arr_schedule_template_task_info[0]["manager_role_id"];
$assignee_role_id = $arr_schedule_template_task_info[0]["assignee_role_id"];
$start_day = $arr_schedule_template_task_info[0]["start_day"];
$end_day = $arr_schedule_template_task_info[0]["end_day"];
$total_time = $arr_schedule_template_task_info[0]["total_time"];

$arr_total_time = explode(":", $total_time);
$hours = $arr_total_time[0];
$minutes = $arr_total_time[1];

//$display_order = $arr_phase_info[0]["assignee_role_id"];
$predecessor = $arr_schedule_template_task_info[0]["predecessor"];

$task_select = get_task_select($company_id, $task_id);
$manager_role_select = get_role_select($company_id, $manager_role_id);
$manager_role_select =  str_replace("role_id", "manager_role_id", $manager_role_select );

$assignee_role_select = get_role_select($company_id, $assignee_role_id);
$assignee_role_select =  str_replace("role_id", "assignee_role_id", $assignee_role_select );

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit Schedule Template Task</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#task_form" ).validate();

	
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
				<h1>Edit Schedule Template Task</h1>
				<a href = "edit_schedule_template.php?stid=<?php echo $schedule_template_id ?>">Manage Schedule Template</a><br><br>
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							Schedule Template Task:<form id = "task_form" action = "update_schedule_template_task.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Task:</td>
									<td><?php echo $task_select ?></td>
								</tr>
								<tr>
									<td>Manager Role:</td>
									<td><?php echo $manager_role_select ?></td>
								</tr>
								<tr>
									<td>Assignee Role:</td>
									<td><?php echo $assignee_role_select ?></td>
								</tr>
								<tr>
									<td>Start Day:</td>
									<td><input type = "text" class = "required number" name = "start_day" size = "4"  value = "<?php echo $start_day ?>"></td>
								</tr>
								<tr>
									<td>End Day:</td>
									<td><input type = "text" class = "required number" name = "end_day" size = "4" value = "<?php echo $end_day ?>"></td>
								</tr>
								<tr>
									<td>Total Hours:</td>
									<td><input type = "text" class = "required number" name = "hours" size = "4" value = "<?php echo $hours ?>"></td>
								</tr>
								<tr>
									<td>Total Minutes:</td>
									<td><input type = "text" class = "required number" name = "minutes" size = "4" value = "<?php echo $minutes ?>"></td>
								</tr>
								<tr>
									<td>Predecessor:</td>
									<td><input type = "text" class = "number" name = "predecessor" size = "4" value = "<?php echo $predecessor ?>"></td>
								</tr>
								<tr>
									<td colspan = "2">
									<input type = "hidden" name = "schedule_template_tasks_id" value = "<?php echo $schedule_template_tasks_id ?>">
									<input type = "hidden" name = "schedule_template_id" value = "<?php echo $schedule_template_id ?>">
									<input type = "submit" value = "Update Schedule Template Task"></td>
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