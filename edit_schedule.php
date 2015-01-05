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
		$error_message = "Duplicate campaign for the chosen business unit and quarter.";
	}
}
$schedule_id = 0;
if (!empty($_GET["s"])){
	$schedule_id = $_GET["s"];
}
$project_id = 0;
if (!empty($_GET["p"])){
	$project_id = $_GET["p"];
}

$arr_schedule_info = get_schedule_info($schedule_id);
$schedule_name = $arr_schedule_info[0]["schedule_name"];
$asset_id = $arr_schedule_info[0]["asset_id"];
$phase_id = $arr_schedule_info[0]["phase_id"];
$schedule_description = $arr_schedule_info[0]["schedule_description"];
$schedule_phase_order = $arr_schedule_info[0]["schedule_phase_order"];
$phase_select = get_phase_select_for_project($project_id, $phase_id);
$asset_select = get_asset_select($project_id, $asset_id);




?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit Schedule</title>
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
				<h1>Edit Schedule</h1>
				<a href = "manage_schedules.php?p=<?php echo $project_id ?>">Manage Schedules</a><br><br>
				<div class = "error"><?php echo $error_message ?></div>
				<form id = "schedule_form" action = "update_schedule.php" method = "POST">
					<table class = "form_table">
						<tr>
							<td>Schedule Name:</td>
							<td><input type = "text" class="required" name = "schedule_name" value = "<?php echo $schedule_name ?>"></td>
						</tr>
						<tr>
							<td>Phase: (not required)</td>
							<td><?php echo $phase_select ?></td>
						</tr>
						<tr>
							<td>Asset: (not required)</td>
							<td><?php echo $asset_select ?></td>
						</tr>
						<tr>
							<td valign="top">Schedule Description:</td>
							<td><textarea name = "schedule_description" style="width: 500px; height: 150px;"><?php echo $schedule_description ?></textarea></td>
						</tr>
						<tr>
							<td>
							<input type = "hidden" name = "schedule_id" value = "<?php echo $schedule_id ?>">
							<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
							<input type = "hidden" name = "current_phase_id" value = "<?php echo $phase_id ?>">
							<input type = "hidden" name = "schedule_phase_order" value = "<?php echo $schedule_phase_order ?>">
							<input type = "submit" value = "Update Schedule"></td>
							<td>&nbsp;</td>
						</tr>
					</table>
				
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