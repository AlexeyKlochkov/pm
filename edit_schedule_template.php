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
		$error_message = "An error occurred.";
	}
		if ($error_num == 2){
		$error_message = "";
	}
}

$schedule_template_id = 0;
if (!empty($_GET["stid"])){
	$schedule_template_id = $_GET["stid"];
}

$arr_schedule_template_tasks = get_schedule_template_tasks($schedule_template_id);
//print_r($arr_schedule_templates);
$n=0;
$schedule_template_task_table = "<table width = \"300\" class = \"budget\"><tr><th>Order</th><th>Task</th><th>Manager Role</th><th>Assignee Role</th><th>Start Day</th><th>End Day</th><th>Total Time</th><th>Pred</th><th colspan = \"4\">&nbsp;</th></tr>";
if (!empty($arr_schedule_template_tasks)){
	foreach ($arr_schedule_template_tasks as $schedule_template_tasks_row){
		$stt_up_arrow = "";
		$stt_down_arrow = "";
		$schedule_template_tasks_id = $schedule_template_tasks_row["schedule_template_tasks_id"];
		$task_id = $schedule_template_tasks_row["task_id"];
		$task_name = $schedule_template_tasks_row["task_name"];
		$assignee_role_id = $schedule_template_tasks_row["assignee_role_id"];
		$manager_role_id = $schedule_template_tasks_row["manager_role_id"];
		$manager_role_name = $schedule_template_tasks_row["manager_role_name"];
		$assignee_role_name = $schedule_template_tasks_row["assignee_role_name"];
		$start_day = $schedule_template_tasks_row["start_day"];
		$end_day = $schedule_template_tasks_row["end_day"];
		$total_time = $schedule_template_tasks_row["total_time"];
		$total_time = substr($total_time, 0,-3);
		$display_order = $schedule_template_tasks_row["display_order"];
		$predecessor = $schedule_template_tasks_row["predecessor"];
		
		if (!empty($arr_schedule_template_tasks[$n+1]["display_order"])){
			$next_task_order = $arr_schedule_template_tasks[$n+1]["display_order"];
		}else{
			$next_task_order = "0";
		}
		
		if ($display_order <> 1){
			$swap1 = $display_order;
			$swap2 = $display_order - 1;
			$stt_up_arrow = "<a href = \"move_schedule_template_task.php?stid=" . $schedule_template_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_up.png\" border=\"0\"></a>";
		}
		if ($display_order < $next_task_order){
			$swap1 = $display_order;
			$swap2 = $display_order + 1;
			$stt_down_arrow = "<a href = \"move_schedule_template_task.php?stid=" . $schedule_template_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_down.png\" border=\"0\"></a>";
		}
		
		
		$schedule_template_task_table .= "<tr><td>" . $display_order . "</td><td nowrap>" . $task_name . "</td><td nowrap>" . $manager_role_name . "</td><td nowrap>" . $assignee_role_name . "</td><td>" . $start_day . "</td><td>" . $end_day . "</td><td>" . $total_time . "</td><td>" . $predecessor . "</td><td>" . $stt_down_arrow . "</td><td>" . $stt_up_arrow . "</td><td><a href = \"del_schedule_template_task.php?sttid=" . $schedule_template_tasks_id . "&stid=" . $schedule_template_id . "&d=" . $display_order . "\">del</a></td><td><a href = \"edit_schedule_template_task.php?sttid=" . $schedule_template_tasks_id . "&stid=" . $schedule_template_id . "\">edit</a></tr>";
		$n++;
	}
}
$schedule_template_task_table .= "</table>";

$task_select = get_task_select($company_id, 0);
$role_select = get_role_select($company_id, 0);

$manager_role_select =  str_replace("role_id", "manager_role_id", $role_select );
$assignee_role_select =  str_replace("role_id", "assignee_role_id", $role_select );

$arr_schedule_template_info = get_schedule_task_template_info($schedule_template_id);
$schedule_template_name = $arr_schedule_template_info[0]["schedule_template_name"];

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Schedule Templates</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#schedule_template_task" ).validate({
  rules: {
    start_day: {
      number: true
    },
	end_day: {
      number: true
    },
	hours: {
      number: true
    },
	minutes: {
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
				<h1>Edit Schedule Template Tasks</h1>
				<h3>Schedule Template: <?php echo $schedule_template_name ?></h3>
				<a href = "new_schedule_template.php">Manage schedule templates</a>
				<div class = "error"><?php echo $error_message ?></div>
					<?php echo $schedule_template_task_table?>
					<table border = "0" width = "100%">
						<tr>
							<td valign="top">
							New task:<form id = "schedule_template_task" action = "add_schedule_template_task.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>
										Task:<br><?php echo $task_select ?>
									</td>
									<td>
										Manager Role:<br><?php echo $manager_role_select ?>
									</td>
									<td>
										Assignee Role:<br><?php echo $assignee_role_select ?>
									</td>
									<td>
										Start Day:<br><input type = "text" class = "required" name = "start_day" size = "4">
									</td>
									<td>
										End Day:<br><input type = "text" class = "required" name = "end_day" size = "4">
									</td>
									<td>
										Total Hours:<br><input type = "text" class = "required number" name = "hours" size = "4">
									</td>
									<td>
										Total Minutes:<br><input type = "text" class = "required number" name = "minutes" size = "4">
									</td>
									<td>
										Predecessor:<br><input type = "text" class = "number" name = "predecessor" size = "4">
									</td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "schedule_template_id" value = "<?php echo $schedule_template_id ?>">
									<input type = "submit" value = "Add Template Task"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
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