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
		$error_message = "Error: Schedules must have unique names for a given project.";
	}
		if ($error_num == 2){
		$error_message = "Phase added.";
	}
		if ($error_num == 3){
		$error_message = "An error occurred.";
	}
		if ($error_num == 4){
		$error_message = "Schedule updated.";
	}
		if ($error_num == 5){
		$error_message = "Phase cannot be deleted because it contains schedules. Please move the schedules to another phase and then delete this phase.";
	}
		if ($error_num == 6){
		$error_message = "Schedule added.";
	}
		if ($error_num == 7){
		$error_message = "Delete error.";
	}
}
$project_id = 0;
if (!empty($_GET["p"])){
	$project_id = $_GET["p"];
}

$arr_project_info = get_project_info($project_id);
$project_name = $arr_project_info[0]["project_name"];
$project_code = $arr_project_info[0]["project_code"];

$remaining_phase_select = get_remaining_phase_select($company_id, $project_id, 0);
$arr_phases = get_project_phases($project_id);
$count_arr_phases = count($arr_phases);
//print_r($arr_phases);
$display_order = 0;
$phase_table = "<table class = \"budget\" width = \"100%\"><tr><th colspan = \"5\">Manage Schedule Phases</th></tr>";

if (!empty($arr_phases)){
	foreach ($arr_phases as $phase_row){
		$phase_id = $phase_row["phase_id"];
		$phase_name = $phase_row["phase_name"];
		$project_phase_id = $phase_row["project_phase_id"];
		$display_order = $phase_row["display_order"];
		$down_arrow = "";
		$up_arrow = "";
		if ($display_order <> 1){
			$swap1 = $display_order;
			$swap2 = $display_order - 1;
			$up_arrow = "<a href = \"move_project_phase.php?p=" . $project_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_up.png\" border=\"0\"></a>";
		}
		
		if ($display_order <> $count_arr_phases){
			$swap1 = $display_order;
			$swap2 = $display_order + 1;
			$down_arrow = "<a href = \"move_project_phase.php?p=" . $project_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_down.png\" border=\"0\"></a>";
		}
		$phase_table .= "<tr><td>" . $display_order . "</td><td>" . $phase_name . "</td><td>" . $up_arrow . "</td><td>" . $down_arrow . "</td><td><a href = \"del_project_phase.php?p=" . $project_id . "&ppid=" . $project_phase_id . "&d=" . $display_order . "&ph=" . $phase_id . "\" onclick=\"return confirm('Are you sure want to delete " . $phase_name . "? This action will not delete schedules or tasks.');\">del</a></a></td></tr>";
		
	}
}else{
	$phase_table .= "<tr><td>No phases for this schedule.</td></tr>";
}

$phase_table .= "</table>";
$phase_table .= "<form action = \"add_phase_to_project.php\" method = \"POST\"><table class = \"budget\" width = \"100%\"><tr><th colspan = \"2\">Add Phase</th></tr>";
$phase_table .= "<tr><td>" . $remaining_phase_select . "</td><td><input type = \"hidden\" name = \"project_id\" value = \"" . $project_id . "\"><input type = \"hidden\" name = \"display_order\" value = \"" . ($display_order + 1) . "\"><input type = \"submit\" value = \"add\"></td></tr>"; 
$phase_table .= "</table></form>";

$phase_and_project_table = "<table class = \"budget\">";
$arr_projects_and_phases = get_project_phases_and_schedules($project_id);
//print_r($arr_projects_and_phases);
$current_phase = "";
$next_phase_id = "";
$i=0;
if (!empty($arr_projects_and_phases)){
	foreach ($arr_projects_and_phases as $schedule_row){
		$schedule_down_arrow = "";
		$schedule_up_arrow = "";
		$schedule_id = $schedule_row["schedule_id"];
		$schedule_name = $schedule_row["schedule_name"];
		$schedule_phase_order = $schedule_row["schedule_phase_order"];
		$phase_id = $schedule_row["phase_id"];
		$phase_name = $schedule_row["phase_name"];
		$asset_name = $schedule_row["asset_name"];
		//print $phase_name . "--" . $schedule_name;
		if (empty($phase_name)){
			$phase_name = "No phase";
		}
		if($current_phase <> $phase_id){
			//add phase header row
			$phase_and_project_table .= "<tr><th colspan = \"7\" align=\"left\">Phase: " . $phase_name . "</th></tr>";
		}
		
		if (!empty($asset_name)){
			$schedule_name .= "<br>Asset: " . $asset_name;
		}
		
		if (!empty($arr_projects_and_phases[$i+1]["schedule_phase_order"])){
			$next_phase_order = $arr_projects_and_phases[$i+1]["schedule_phase_order"];
		}else{
			$next_phase_order = "0";
		}
		//print $schedule_phase_order . "--" . $next_phase_order . "<br>";
		//manage arrows
		if ($schedule_phase_order <> 1){
				$swap1 = $schedule_phase_order;
				$swap2 = $schedule_phase_order - 1;
				$schedule_up_arrow = "<a href = \"move_project_schedule.php?p=" . $project_id . "&ph=" . $phase_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_up.png\" border=\"0\"></a>";
			}
		if ($schedule_phase_order < $next_phase_order){
			$swap1 = $schedule_phase_order;
			$swap2 = $schedule_phase_order + 1;
			$schedule_down_arrow = "<a href = \"move_project_schedule.php?p=" . $project_id . "&ph=" . $phase_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_down.png\" border=\"0\"></a>";
		}
		$phase_and_project_table .= "<tr><td width = \"30\" align=\"right\" valign=\"top\">" . $schedule_phase_order ."</td><td>Schedule: " . $schedule_name . "</td><td>" . $schedule_up_arrow . "</td><td>" . $schedule_down_arrow . "</td><td><a href = \"edit_schedule.php?p=" . $project_id . "&s=" . $schedule_id . "\">edit</a></td><td><a href = \"manage_tasks.php?s=" . $schedule_id . "\">tasks</a></td><td><a href = \"del_schedule.php?p=" . $project_id . "&s=" . $schedule_id . "\" onclick=\"return confirm('Are you sure want to delete this schedule? This action will delete the schedule and all related tasks.');\">del</a></td></tr>";
		$current_phase = $phase_id;

		$i++;
	}

}else{
	$phase_and_project_table .= "<tr><td colspan = \"4\" align=\"right\" valign=\"top\">No Schedules</td></tr>";
}



$phase_and_project_table .= "</table>";

$phase_select_project = get_phase_select_for_project($project_id, 0);
$asset_select = get_asset_select($project_id, 0);




?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Manage Schedules</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#schedule_form").validate();
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
				<h1>Manage Schedules</h1>
				<h3>Project <a href = "manage_project.php?p=<?php echo $project_id ?>&show_schedules=1#schedules"><?php echo $project_name ?> (<?php echo $project_code ?>)</a></h3>
				<div class = "error"><?php echo $error_message ?></div>
				<table border = "0" width = "100%" cellpadding = "10" >
					<tr>
						<td valign = "top">
							<?php echo $phase_and_project_table ?>
						</td>
						<td valign = "top" width = "30%">
							<?php echo $phase_table  ?>
						</td>
					</tr>
					<tr>
						<td colspan = "2">Add Schedule
							
							<form id = "schedule_form" action = "add_schedule.php" method = "POST">
								<table class = "form_table">
									<tr>
										<td>Schedule Name:</td>
										<td><input type = "text" class="required" name = "schedule_name"></td>
									</tr>
									<tr>
										<td>Phase: (not required)</td>
										<td><?php echo $phase_select_project ?></td>
									</tr>
									<tr>
										<td>Asset: (not required)</td>
										<td><?php echo $asset_select ?></td>
									</tr>
									<tr>
										<td valign="top">Schedule Description:</td>
										<td><textarea name = "schedule_description" style="width: 300px; height: 50px;"></textarea></td>
									</tr>
									<tr>
										<td>
										<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
										<input type = "submit" value = "Add Schedule"></td>
										<td>&nbsp;</td>
									</tr>
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