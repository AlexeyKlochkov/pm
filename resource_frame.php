<?php 
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$error_message = "";
if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "Time added.";
	}
		if ($error_num == 2){
		$error_message = "An error occurred.";
	}
}

$employee_id = "";
if (!empty($_GET["employee_id"])){
	$employee_id = $_GET["employee_id"];
}

$role_id = "";
if (!empty($_GET["role_id"])){
	$role_id = $_GET["role_id"];
}

$project_id = "";
if (!empty($_GET["project_id"])){
	$project_id = $_GET["project_id"];
}
//print $project_id;
$run_report = 0;
if (!empty($_GET["run_report"])){
	$run_report = $_GET["run_report"];
}

$task_name = "";
if (!empty($_GET["task_name"])){
	$task_name = $_GET["task_name"];
}

$schedule_id = "";
if (!empty($_GET["schedule_id"])){
	$schedule_id = $_GET["schedule_id"];
}

$schedule_task_id = "";
if (!empty($_GET["schedule_task_id"])){
	$schedule_task_id = $_GET["schedule_task_id"];
}

$assignee_initials = "";
if (!empty($_GET["assignee_list"])){
	$assignee_initials = $_GET["assignee_list"];
	$arr_assignee_initials = explode(", ", $assignee_initials);
}

$is_approval_task = "";
if (!empty($_GET["is_approval"])){
	$is_approval_task = $_GET["is_approval"];
}
//print $is_approval_task;

//print_r($arr_assignee_initials);
$field_name = "employee_id";
$select_label = "All";
$selected_id = 0;
$required = 0;
$user_select = get_user_select($company_id, $field_name, $select_label, $employee_id, $required);
$user_select =  str_replace("<select", "<select id = \"user\"", $user_select );

$role_select = get_role_select($company_id, $role_id);
$role_select = str_replace("class = \"required\"", "id=\"role\"", $role_select);
$role_select = str_replace("Please Select", "All", $role_select);

$today = date("m/d/y"); 
$start_date = $today;
$end_date = $today;
$orig_end_date = $today;
$orig_start_date = $today;
if (!empty($role_id)){
	//role trumps everything. 
	$arr_employees = get_employees_by_role_and_active($role_id, 1);

}else{
	$arr_employees = get_users_for_resource_report_and_active($company_id, $employee_id, 1);

}

$error_message = "";
if (!empty($_GET["start_date"])){
	$start_date = $_GET["start_date"];
	$orig_start_date = $start_date;
}
if (!empty($_GET["end_date"])){
	$end_date = $_GET["end_date"];
	$orig_end_date = $end_date;
}

$resource_table = "<div class = \"\"><form action = \"add_users_to_schedule.php\" method = \"POST\"><table class = \"budget\"><input type = \"submit\" value = \"Add People to Task " . $task_name . "\">";
if ($run_report == 1){
	$arr_dates = get_date_array($start_date, $end_date);
	//print_r($arr_dates);

	$resource_table .= "<tr><th>Add</th><th>Employee</th><th>Role</th>";

	foreach ($arr_dates as $current_date){
		//print $current_date;
		$weekday =  date('l', strtotime( $current_date));
		$resource_table .= "<th>" . $weekday . "<br>" . $current_date . "</th>";

	}
	$resource_table .= "</th>";
	$str_employee_ids_in_view = "";
	if (!empty($arr_employees)){
	foreach ($arr_employees as $current_employee){

		//$arr_user_info = get_user_info($current_employee);
		//print_r($arr_user_info);
		$employee_user_id = $current_employee["user_id"];
		$user_first_name = $current_employee["first_name"];
		$user_last_name = $current_employee["last_name"];
		$user_role_abbrev = $current_employee["role_abbrev"];
		$user_initials = $current_employee["initials"];
		
		$str_employee_ids_in_view .= $employee_user_id . ",";
		
		$checked = "";
		if(in_array($user_initials, $arr_assignee_initials)){
			$checked = " checked ";
		}
		$user_name = $user_first_name . " " . $user_last_name . " (" . $user_initials . ")";
		if($is_approval_task == 1){
			$resource_table .= "<tr><td><input type = \"radio\" name = \"user_id-R\" value =\"" . $employee_user_id . "\"" . $checked . ">";
		}else{
			$resource_table .= "<tr><td><input type = \"checkbox\" name = \"user_id-" . $employee_user_id . "\"" . $checked . ">";
		}
		$resource_table .= "</td><td nowrap>" . $user_name . "</td><td>" . $user_role_abbrev  . "</td>";
		foreach ($arr_dates as $current_date){
			//print $current_date;
			$orig_date = $current_date;
			$td_class = "";
			$hours_worked = get_user_hours_worked_by_day_no_complete($employee_user_id, $current_date);
			if ($hours_worked > 8){
				$td_class = "red_td";
			}
			$resource_table .= "<td class = \"" . $td_class . "\" align=\"right\"><a href = \"resource_frame.php?show_user=1&run_report=1&uid=" . $employee_user_id . "&employee_id=" . $employee_id . "&role_id=" . $role_id . "&start_date=" . $start_date . "&end_date=" . $orig_end_date . "&ustart=" . $orig_date . "&uend=" . $orig_date . "&assignee_list=" . $assignee_initials . "&schedule_task_id=" . $schedule_task_id . "&project_id=" . $project_id . "&schedule_id=" . $schedule_id . "&task_name=" . $task_name . "&is_approval=" . $is_approval_task . "\">" . $hours_worked . "</a></td>";

		}
		$resource_table .= "</tr>" ;

	}
	$str_employee_ids_in_view = substr($str_employee_ids_in_view, 0, -1);
	}else{
		$resource_table .= "<tr><td colspan = \"3\">No employees for this query.</td></tr>";
	}
}
$resource_table .= "</table><input type = \"hidden\" name = \"schedule_task_id\" value = \"" . $schedule_task_id . "\"><input type = \"hidden\" name = \"schedule_id\" value = \"" . $schedule_id . "\"><input type = \"hidden\" name = \"orig_initials\" value = \"" . $assignee_initials . "\"><input type = \"hidden\" name = \"project_id\" value = \"" . $project_id . "\"><input type = \"submit\" value = \"Add People to Task " . $task_name . "\"><input type = \"hidden\" name = \"employee_ids_in_view\" value = \"" . $str_employee_ids_in_view . "\"></form></div>";

$show_user = 0;
if (!empty($_GET["show_user"])){
	$show_user = $_GET["show_user"];
}

$task_user = "";
if (!empty($_GET["uid"])){
	$task_user = $_GET["uid"];
}

$task_table = "";
if ($show_user == 1){
	if (!empty($_GET["ustart"])){
		$start_date = $_GET["ustart"];
	}
	if (!empty($_GET["uend"])){
		$end_date = $_GET["uend"];
	}
	
	$arr_user_tasks = get_tasks_for_user($company_id, $task_user, $start_date, $end_date);
	//print_r ($arr_user_tasks);
	$total_hours = 0;
	$task_table = "<table class = \"budget\"><tr><th>Employee</th><th>Role</th><th>Project</th><th>Schedule</th><th>Task</th><th>Start</th><th>End</th><th>Daily Hours</th></tr>";
	if (!empty($arr_user_tasks)){
		foreach ($arr_user_tasks as $task_row){
		$employee_user_id = $task_row["user_id"];
		$user_first_name = $task_row["first_name"];
		$user_last_name = $task_row["last_name"];
		$user_initials = $task_row["initials"];
		$user_role_abbrev = $task_row["role_abbrev"];
		$project_name =  $task_row["project_name"];
		$project_id =  $task_row["project_id"];
		$schedule_name =  $task_row["schedule_name"];
		$schedule_id =  $task_row["schedule_id"];
		$task_name =  $task_row["task_name"];
		$start_date =  $task_row["start_date"];
		$end_date =  $task_row["end_date"];
		$daily_hours =  $task_row["daily_hours"];
		$complete =  $task_row["complete"];
		if ($complete == 1){
			$task_name = "<del>" . $task_name . "</del>";
			$daily_hours = "<del>" . $daily_hours . "</del>";
		}else{
			$total_hours = $total_hours + $daily_hours;
		}
		$task_table .= "<tr>";
		$task_table .= "<td nowrap>" . $user_first_name . " " . $user_last_name . " (" . $user_initials . ")</td>";
		$task_table .= "<td>" . $user_role_abbrev . "</td>";
		$task_table .= "<td><a href = \"manage_project.php?p=". $project_id . "\" target=\"_blank\">" . $project_name . "</a></td>";
		$task_table .= "<td><a href = \"manage_schedules.php?p=". $project_id . "\" target=\"_blank\">" . $schedule_name . "</a></td>";
		$task_table .= "<td><a href = \"manage_tasks.php?s=". $schedule_id . "\" target=\"_blank\">" . $task_name . "</td>";
		$task_table .= "<td nowrap>" . $start_date . "</td>";
		$task_table .= "<td nowrap>" . $end_date . "</td>";
		$task_table .= "<td align=\"right\">" . $daily_hours . "</td>";
		$task_table .= "</tr>";
		
		}
		$total_class = "";
		if ($total_hours > 8){
			$total_class = "red_td";
		}
		
		$task_table .= "<tr><td  colspan = \"7\" align=\"right\"><b>Total:</b></td><td align=\"right\" class = \"" . $total_class . "\"><b>" . $total_hours . "</b></td></tr>";
	}else{
		
	$task_table .= "<tr><td colspan = \"8\">No tasks for this time frame</td></tr>";
	}
$task_table .= "</table>";

}
?>
<html>
<head>
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />
<link href='style.css' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
$(document).ready(function(){
$( ".datepicker" ).datepicker();

$("#role").change(function() {
		if ($('#role').val() != ''){
			$("#user").val(0);
		}
	});

	$("#user").change(function() {
		if ($('#user').val() != ''){
			$("#role").val(0);

		}
	});	


});

</script>
</head>
<body style = "background-color: #FFFFFF;">
<div id="resource_frame">
				<form action = "resource_frame.php" method = "GET" id="resource">
				<table width = "50%" class = "form_table">
					<tr>
						<td>
							Employee:<br><?php echo $user_select ?><br>
							
						</td>
						<td>
							Role:<br><?php echo $role_select ?>
						</td>
						<td>
							Start Date:<br><input type = "text" name = "start_date" id="start_date" class="datepicker" size = "8"value = "<?php echo $orig_start_date ?>">
						</td>
						<td>
							End Date:<br><input type = "text" name = "end_date" id="end_date" class="datepicker" size = "8"value = "<?php echo $orig_end_date ?>">
						</td>
						<td>
							<input type = "hidden" name = "run_report" value = "1"><input type = "hidden" name = "assignee_list" value = "<?php echo $assignee_initials ?>"><input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>"><input type = "hidden" name = "schedule_task_id" value = "<?php echo $schedule_task_id ?>"><input type = "hidden" name = "is_approval" value = "<?php echo $is_approval_task ?>"><input type = "hidden" name = "task_name" value = "<?php echo $task_name ?>"><input type = "submit" value = "go">
						</td>
					</tr>
				</table>
				</form>
				<?php echo $task_table ?><br>
				<?php echo $resource_table  ?>
</div>
</body>
</html>		