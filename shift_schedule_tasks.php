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
		$error_message = "An error occurred. Make sure you select one or more checkboxes below.";
	}
		if ($error_num == 2){
		$error_message = "Tasks shifted.";
	}

}
$schedule_id = 0;
if (!empty($_GET["s"])){
	$schedule_id = $_GET["s"];
}

$arr_schedule_info = get_schedule_info($schedule_id);
$schedule_name = $arr_schedule_info[0]["schedule_name"];
$project_id = $arr_schedule_info[0]["project_id"];
$project_name = $arr_schedule_info[0]["project_name"];
$project_code = $arr_schedule_info[0]["project_code"];

$task_select = get_task_select($company_id, 0);
$manager_select = get_project_user_select($project_id, "task_manager_id", "None", 0, 0);
$assignee1_select = get_project_user_select($project_id, "assignee1", "Please Select", 0, 1);
$assignee2_select = get_project_user_select($project_id, "assignee2", "None", 0, 0);
$percentage_select = get_percentage_select("progress", 0);


$task_table = "<table width = \"100%\" class = \"budget\"><tr><th>Shift?</th><th>Order</th><th>Task</th><th>Manager</th><th>Start</th><th>End</th><th>Hours</th><th>Mins</th><th>Pred</th><th>Progress</th><th>Complete</th><th>Assignee(s)</th></tr>";
$arr_schedule_tasks = get_schedule_tasks($schedule_id);
$n=0;
//print_r($arr_schedule_tasks);
$schedule_task_id_list = "";
if (!empty($arr_schedule_tasks)){
	foreach ($arr_schedule_tasks as $task_row){
		$task_down_arrow = "";
		$task_up_arrow = "";
		$display_order = $task_row["display_order"];
		$schedule_task_id = $task_row["schedule_task_id"];
		$task_name = $task_row["task_name"];
		$task_id = $task_row["task_id"];
		$manager_name = $task_row["initials"];
		$manager_id = $task_row["user_id"];
		$start_date = translate_mysql_todatepicker($task_row["start_date"]);
		$end_date = translate_mysql_todatepicker($task_row["end_date"]);
		$estimated_hours = $task_row["estimated_hours"];
		$is_approval = $task_row["is_approval"];
		$predecessor = $task_row["predecessor"];
		$assignee_list = get_assignee_initials($schedule_task_id);
		if ($is_approval == 1){
			$assignee_form = get_assignee_form_radio($project_id, $schedule_task_id, $schedule_id);
		}else{
			$assignee_form = get_assignee_form($project_id, $schedule_task_id, $schedule_id);
		}
		
		$complete= $task_row["complete"];
		$complete_string = "no";
		if ($complete == 1){
			$complete_string = "yes";
		}
		list($hours, $minutes, $seconds) = explode(":", $estimated_hours);
		$progress = $task_row["progress"];
		
		if (!empty($arr_schedule_tasks[$n+1]["display_order"])){
			$next_task_order = $arr_schedule_tasks[$n+1]["display_order"];
		}else{
			$next_task_order = "0";
		}
		
		$task_table .= "<tr>";
		$task_table .= "<td align=\"right\"><input class=\"task_check\" type = \"checkbox\" name = \"shift-" . $schedule_task_id . "\"></td>";
		$task_table .= "<td align=\"right\">" . $display_order . "</td>";
		$task_table .= "<td>" . $task_name . "</td>";
		$task_table .= "<td>" . $manager_name . "</td>";
		$task_table .= "<td align=\"right\">" . $start_date . "<input type = \"hidden\" name = \"sd-" . $schedule_task_id . "\" value=\"" . $start_date . "\"></td>";
		$task_table .= "<td align=\"right\">" . $end_date . "<input type = \"hidden\" name = \"ed-" . $schedule_task_id . "\" value=\"" . $end_date . "\"></td>";
		$task_table .= "<td align=\"right\">" . $hours . "</td>";
		$task_table .= "<td align=\"right\">" . $minutes . "</td>";
		$task_table .= "<td align=\"right\">" . $predecessor . "</td>";
		$task_table .= "<td align=\"right\">" . $progress  . "%</td>";
		$task_table .= "<td>" . $complete_string  . "</td>";
		$task_table .= "<td>" . $assignee_list  . "</td>";
		$task_table .= "</tr>";
		
		$schedule_task_id_list .= $schedule_task_id . "-";
		
		$n++;
	}
	$schedule_task_id_list = substr($schedule_task_id_list, 0, -1);
	
	}else{
$task_table .= "<tr><td colspan = \"11\">No tasks</td></tr>";

}

$task_table .= "</table>";


$schedule_template_select = get_schedule_template_select($company_id, 0);

$task_num_select = "";

if(!empty($arr_schedule_tasks)){
	$task_count = count($arr_schedule_tasks);
	//print $task_count;
	$task_num_select .= "<select name = \"after_task\">\n";
	for ($i=1; $i<=$task_count; $i++)
	  {
		$task_num_select .= "<option value = \"" . $i . "\">" . $i . "</option>\n";
	  }
	$task_num_select .= "</select>";
}

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
    $( "#shift_schedule" ).validate({
	  rules: {
		start_date: {
			required: true
		},
		end_date: {
			required: true
		}

	  }
	});

	
$(function () {
    $('#selectall').toggle(
        function() {
            $('#shift_schedule .task_check').prop('checked', true);
        },
        function() {
            $('#shift_schedule .task_check').prop('checked', false);
        }
    );
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
				<h1>Shift Schedule</h1>
				<h3>Schedule: <a href = "manage_schedules.php?p=<?php echo $project_id ?>"><?php echo $schedule_name  ?></a>
				<br>Project: <a href = "manage_project.php?p=<?php echo $project_id ?>&show_schedules=1#schedules"><?php echo $project_name  ?> (<?php echo $project_code  ?>)</a></h3>
				<div class = "error"><?php echo $error_message ?></div>
				<form id = "shift_schedule" action = "shift_multiple_tasks.php" method = "POST">
				Select tasks to shift below, and enter in a positive or negative number of days to shift the schedule, then click "go".<br>
				<b>Days to shift:</b> <input type = "text" size = "1" name = "days" class = "required number"> 
				<input type = "submit" value = "go"><br><br>
				<a href = "#" id = "selectall">Select/Deselect All</a><br>
				<?php echo $task_table ?>
				<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
				<input type = "hidden" name = "schedule_id" value = "<?php echo $schedule_id ?>">
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