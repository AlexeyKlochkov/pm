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
		$error_message = "Schedule template name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Schedule Template Added.";
	}
}


$arr_schedule_templates = get_schedule_templates($company_id, 1);
//print_r($arr_schedule_templates);
$schedule_template_table = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Current Schedule Templates</th></tr>";
if (!empty($arr_schedule_templates)){
	foreach ($arr_schedule_templates as $schedule_template_row){
		$schedule_template_id = $schedule_template_row["schedule_template_id"];
		$schedule_template_name = $schedule_template_row["schedule_template_name"];
		$schedule_template_table .= "<tr><td>" . $schedule_template_name . "</td><td><a href = \"edit_schedule_template.php?stid=" . $schedule_template_id . "\">tasks</a></td><td><a href = \"activate_schedule_template.php?a=2&stid=" . $schedule_template_id . "\">del</a></td></tr>";
	
	}
}
$schedule_template_table .= "</table>";


$arr_retired_schedule_templates = get_schedule_templates($company_id, 0);
$schedule_template_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"2\">Retired Schedule Templates</th></tr>";
if (!empty($arr_retired_schedule_templates)){
	foreach ($arr_retired_schedule_templates as $schedule_template_row){
		$schedule_template_id = $schedule_template_row["schedule_template_id"];
		$schedule_template_name = $schedule_template_row["schedule_template_name"];
		$schedule_template_table2 .= "<tr><td>" . $schedule_template_name . "</td><td><a href = \"activate_schedule_template.php?a=1&stid=" . $schedule_template_id . "\">activate</a></td></tr>";
	
	}
}
$schedule_template_table2 .= "</table>";

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
				<h1>Schedule Templates</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Schedule Template:<form id = "schedule_template_form" action = "add_schedule_template.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Schedule Template Name:</td>
									<td><input class = "required" type = "text" name = "schedule_template_name"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add Schedule Template"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $schedule_template_table ?>
							<br><br>
							<?php echo $schedule_template_table2 ?>
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