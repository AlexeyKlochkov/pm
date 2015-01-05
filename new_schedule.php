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
$project_id = 0;
if (!empty($_GET["p"])){
	$project_id = $_GET["p"];
}


$phase_select = get_phase_select($company_id, 0);
$asset_select = get_asset_select($project_id, 0);




?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Schedule</title>
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
				<h1>New Schedule</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				<form id = "schedule_form" action = "add_schedule.php" method = "POST">
					<table class = "form_table">
						<tr>
							<td>Schedule Name:</td>
							<td><input type = "text" class="required" name = "schedule_name"></td>
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
							<td><textarea name = "schedule_description" style="width: 500px; height: 150px;"></textarea></td>
						</tr>
						<tr>
							<td>
							<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
							<input type = "submit" value = "Add Schedule"></td>
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