<?php 
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";
//print $company_id;
$error_message = "";
$active_flag = 1;
if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "An error occurred.";
	}
		if ($error_num == 2){
		$error_message = "Task Completed.";
	}
}

if (!empty($_GET["stid"])){
	$schedule_task_id = $_GET["stid"];
}else{
	print "an error occurred.";
	//redirect
}

$arr_user_info = get_user_info($user_id);
$user_full_name = $arr_user_info[0]["first_name"] . " " . $arr_user_info[0]["last_name"];

$arr_project = get_project_info_by_schedule_task($schedule_task_id);
$project_id = $arr_project[0]["project_id"];
$project_name = $arr_project[0]["project_name"];
$project_code = $arr_project[0]["project_code"];
$project_manager_name = $arr_project[0]["pm_fname"] . " " . $arr_project[0]["pm_lname"];
$task_manager_name = $arr_project[0]["m_fname"] . " " . $arr_project[0]["m_lname"];
$schedule_id = $arr_project[0]["schedule_id"];
$schedule_name = $arr_project[0]["schedule_name"];
$task_name = $arr_project[0]["task_name"];
$task_complete = $arr_project[0]["complete"];
$approved_by = $arr_project[0]["approved_by"];
$approval_date = $arr_project[0]["approval_date"];
$approver_name = $arr_project[0]["a_fname"] . " " . $arr_project[0]["a_lname"];
$is_approved = $arr_project[0]["is_approved"];
$approval_notes = $arr_project[0]["approval_notes"];

$header_table = "<table class = \"budget\">";
$header_table .= "<tr><th align=\"left\">Project:</th><td><a href = \"manage_project.php?p=" . $project_id . "\" target=\"_blank\">" . $project_name . "</a></td></tr>";
$header_table .= "<tr><th align=\"left\">Project Code:</th><td>" . $project_code . "</td></tr>";
$header_table .= "<tr><th align=\"left\">Project Manager:</th><td>" . $project_manager_name . "</td></tr>";
$header_table .= "<tr><th align=\"left\">Schedule:</th><td><a href = \"manage_project.php?p=" . $project_id . "&show_schedules=1#schedules\" target=\"_blank\">" . $schedule_name . "</a></td></tr>";
$header_table .= "<tr><th align=\"left\">Task:</th><td>" . $task_name . "</td></tr>";
$header_table .= "<tr><th align=\"left\">Task Manager:</th><td>" . $task_manager_name . "</td></tr>";
$header_table .= "<tr><th align=\"left\">Approver:</th><td>" . $user_full_name  . "</td></tr>";
$header_table .= "</table>";

$project_file_table = "<table class = \"budget\">";

$directory = "project_files/" . $project_code . "/";

$not_approved_checked = "";
if ($is_approved ==2){
	$not_approved_checked = "checked";
}
$todays_date = date("m/d/Y");
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Task Completion</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#task_completion" ).validate({
  rules: {
    day: {
      required: true
    },
	hours: {
		number: true
	},
	minutes: {
		number: true
	}
  }
});
	
$( ".datepicker" ).datepicker();

	
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
				<h1>Task Completion</h1>
				<div class = "error"><?php echo $error_message ?></div>
				<table border = "0">
					<tr>
						<td valign="top">
							<?php echo $header_table ?>
						</td>
						<td valign="top">
							<form action = "fast_track_complete_task.php" id = "task_completion" method = "POST">
							<table class = "budget">
								<tr>
									<th colspan = "2">
									Task Completion
									</th>
								</tr>
<?php 
if ($task_complete<>1){
?>
								<tr>
									<td colspan = "2">
										<input class = "required" type = "checkbox" name = "complete" value = "1">&nbsp;&nbsp;Task is complete.
									</td>
								</tr>
								<tr>
									<td>
										Enter Time:
									</td>
									<td nowrap>
										h:<input class="required" type = "text" name = "hours" size = "1" value = "0"> m:<input class="required" type = "text" name = "minutes" size = "1" value = "00">
									</td>
								</tr>
								<tr>
									<td>
										Day:
									</td>
									<td>
										<input id = "day" type = "text" name = "day" class="datepicker" size = "8" value = "<?php echo $todays_date?>">
									</td>
								</tr>
								<tr>
									<td colspan = "2">
										<b>Task notes</b>:<br>
										<textarea name = "notes" rows="4" cols="40" maxlength="1000"><?php echo $approval_notes ?></textarea>
									</td>
								</tr>
								<tr>
									<td colspan = "2">
									<input type = "hidden" name = "schedule_task_id" value = "<?php echo $schedule_task_id ?>">
									<input type = "hidden" name = "schedule_id" value = "<?php echo $schedule_id ?>">
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
										<input type = "submit" value = "Submit">
									</td>
								</tr>
<?php
}else{
?>
								<tr>
									<td colspan = "2">
										<b>Task is complete.</b>
									</td>
								</tr>
<?php
}
?>
							</table>
							</form>
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