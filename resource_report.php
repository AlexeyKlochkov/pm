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

$user_group_id = "";
if (!empty($_GET["user_group_id"])){
	$user_group_id = $_GET["user_group_id"];
}

$run_report = 0;
if (!empty($_GET["run_report"])){
	$run_report = $_GET["run_report"];
}

$active = "";
$active_selected = "";
$inactive_selected = "";
$both_selected = "";
if (!empty($_GET["active"])){
	$active = $_GET["active"];
}
if ($active == 1){
	$active_selected = "selected";
}

if ($active == 2){
	$inactive_selected = "selected";
}

if ($active == 3){
	$both_selected = "selected";
}

$field_name = "employee_id";
$select_label = "All";
$selected_id = 0;
$required = 0;
$user_select = get_user_select($company_id, $field_name, $select_label, $employee_id, $required);
$user_select =  str_replace("<select", "<select id = \"user\"", $user_select );

$role_select = get_role_select($company_id, $role_id);
$role_select = str_replace("class = \"required\"", "id=\"role\"", $role_select);
$role_select = str_replace("Please Select", "All", $role_select);

$user_group_select = get_user_group_select($company_id, $user_group_id);

$choose_dates_checked = "checked";
$all_checked = "";

$today = date("m/d/Y"); 
$start_date = $today;
$end_date = $today;
$orig_end_date = $today;
$orig_start_date = $today;
if (!empty($role_id)){
	//role trumps everything. 
	$arr_employees = get_employees_by_role_and_active($role_id, $active);

}elseif(!empty($user_group_id)){
	$arr_employees = get_user_group_members_and_active($user_group_id, $active);
}else{
	
	$arr_employees = get_users_for_resource_report_and_active($company_id, $employee_id, $active);
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
//print "start date: " . $start_date . "<br>";
//print "end date: " . $end_date . "<br>";

$resource_table = "<div class = \"scroll\"><table class = \"budget\">";
if ($run_report == 1){
	$arr_dates = get_date_array($start_date, $end_date);
	//print_r($arr_dates);

	$resource_table .= "<tr><th>Employee</th><th>Role</th>";

	foreach ($arr_dates as $current_date){
		//print $current_date;
		$weekday =  date('l', strtotime( $current_date));
		$resource_table .= "<th>" . $weekday . "<br>" . $current_date . "</th>";

	}
	$resource_table .= "</th>";

	if (!empty($arr_employees)){
	foreach ($arr_employees as $current_employee){

		//$arr_user_info = get_user_info($current_employee);
		//print_r($arr_user_info);
		$employee_user_id = $current_employee["user_id"];
		$user_first_name = $current_employee["first_name"];
		$user_last_name = $current_employee["last_name"];
		$user_role_abbrev = $current_employee["role_abbrev"];
		$user_name = $user_first_name . " " . $user_last_name;
		$resource_table .= "<tr><td nowrap>" . $user_name . "</td><td>" . $user_role_abbrev  . "</td>";
		
		foreach ($arr_dates as $current_date){
			//print $current_date;
			$orig_date = $current_date;
			$td_class = "";
			$hours_worked = get_user_hours_worked_by_day_no_complete($employee_user_id, $current_date);
			if ($hours_worked > 8){
				$td_class = "red_td";
			}
			$resource_table .= "<td class = \"" . $td_class . "\" align=\"right\"><a href = \"resource_report.php?show_user=1&run_report=1&uid=" . $employee_user_id . "&employee_id=" . $employee_id . "&role_id=" . $role_id . "&user_group_id=" . $user_group_id."&start_date=" . $start_date . "&end_date=" . $orig_end_date . "&ustart=" . $orig_date . "&uend=" . $orig_date . "\">" . $hours_worked . "</a></td>";

		}
		$resource_table .= "</tr>" ;

	}
	}else{
		$resource_table .= "<tr><td colspan = \"2\">No employees for this query.</td></tr>";
	}
}
$resource_table .= "</table></div>";

$show_user = 0;
if (!empty($_GET["show_user"])){
	$show_user = $_GET["show_user"];
}

$task_user = "";
if (!empty($_GET["uid"])){
	$task_user = $_GET["uid"];
}

$use_dates = 1;
if (!empty($_GET["all_or_dates"])){
	if($_GET["all_or_dates"]=="all"){
		$use_dates = 0;
		$show_user = 1;
		$resource_table = "";
		$choose_dates_checked = "";
		$all_checked = "checked";
	}
}

$task_table = "";
if ($show_user == 1){
	if (!empty($_GET["ustart"])){
		$start_date = $_GET["ustart"];
	}
	if (!empty($_GET["uend"])){
		$end_date = $_GET["uend"];
	}
	if(empty($task_user)){
		$task_user = $employee_id;
	}
	
	$arr_user_tasks = get_tasks_for_user($company_id, $task_user, $start_date, $end_date, $use_dates);
	//print_r ($arr_user_tasks);
	$total_hours = 0;
	$task_table = "<table class = \"budget\"><tr><th>Employee</th><th>Role</th><th>Project</th><th>Schedule</th><th>Task</th><th>Start</th><th>End</th><th>Daily Hours</th></tr>";
	if (!empty($arr_user_tasks)){
		foreach ($arr_user_tasks as $task_row){
		//$user_id = $task_row["user_id"];
		$user_first_name = $task_row["first_name"];
		$user_last_name = $task_row["last_name"];
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
		$task_table .= "<td nowrap>" . $user_first_name . " " . $user_last_name . "</td>";
		$task_table .= "<td>" . $user_role_abbrev . "</td>";
		$task_table .= "<td><a href = \"manage_project.php?p=". $project_id . "\">" . $project_name . "</a></td>";
		$task_table .= "<td><a href = \"manage_schedules.php?p=". $project_id . "\">" . $schedule_name . "</a></td>";
		$task_table .= "<td><a href = \"manage_tasks.php?s=". $schedule_id . "\">" . $task_name . "</td>";
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
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />
<title>Resource Report</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $( "#add_time" ).validate({
	  rules: {
		start_date: {
			required: true
		},
		end_date: {
			required: true
		}

	  }
	});
	$( ".datepicker" ).datepicker();
	
	
	
	$("#role").change(function() {
		if ($('#role').val() != ''){
			$("#user").val(0);
			$("#user_group_id").val(0);
		}
	});

	$("#user").change(function() {
		if ($('#user').val() != ''){
			$("#role").val(0);
			$("#user_group_id").val(0);
		}
	});	
	$("#user_group_id").change(function() {
		if ($('#user_group_id').val() != ''){
			$("#role").val(0);
			$("#user").val(0);
		}
	});	
	
$("resource").validate({
    rules: {
        end_date: { greaterThan: "#start_date" }
    }
});	

var all_or_dates = $('input[name="all_or_dates"]:checked', '#resource').val();
if(all_or_dates=="all"){
	$(".datepicker").attr('disabled', true);
	$("#role").val(0);
	$("#role").attr('disabled', true);
	$("#user_group_id").val(0);
	$("#user_group_id").attr('disabled', true);
}else{
	$(".datepicker").attr('disabled', false);
	$("#user_group_id").attr('disabled', false);
	$("#role").attr('disabled', false);
}

	
$('#resource input').on('change', function() {
   var all_or_dates = $('input[name="all_or_dates"]:checked', '#resource').val();
	if(all_or_dates=="all"){
		$(".datepicker").attr('disabled', true);
		$("#role").val(0);
		$("#role").attr('disabled', true);
		$("#user_group_id").val(0);
		$("#user_group_id").attr('disabled', true);
	}else{
		$(".datepicker").attr('disabled', false);
		$("#user_group_id").attr('disabled', false);
		$("#role").attr('disabled', false);
	}
});

$( "#resource" ).submit(function( event ) {
 var all_or_dates = $('input[name="all_or_dates"]:checked', '#resource').val();
  if(all_or_dates=="all"){
	if($("#user").val()==''){
		event.preventDefault();
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
				<h1>Resource Report</h1>
				<table width = "100%" class = "small_link">
					<tr>
						<td align = "left">
							<div class = "error"><?php echo $error_message ?></div>
						</td>
					</tr>
				</table>
				
				<form action = "resource_report.php" method = "GET" id="resource">
				<table width = "50%" class = "form_table" border = "0">
					<tr>
						<td>
							Employee:<br><?php echo $user_select ?><br>
							Role:<br><?php echo $role_select ?><br>
							Group:<br><?php echo $user_group_select ?>
						</td>
						<td valign="top">
							<input type = "radio" name = "all_or_dates" class = "all_or_dates" value = "choose_dates" <?php echo $choose_dates_checked ?>> Choose Dates<br>
							<input type = "radio" name = "all_or_dates" class = "all_or_dates" value = "all" <?php echo $all_checked ?>> All active tasks<br>
							
							User Status:<br>
							<select name = "active">
								<option value = "1" <?php echo $active_selected ?>>Active</option>
								<option value = "2" <?php echo $inactive_selected ?>>Inactive</option>
								<option value = "3" <?php echo $both_selected ?>>Both</option>
							</select>
						</td>
						<td  valign="top">
							Start Date:<br><input type = "text" name = "start_date" id="start_date" class="datepicker" size = "8"value = "<?php echo $orig_start_date ?>"><br>
							End Date:<br><input type = "text" name = "end_date" id="end_date" class="datepicker" size = "8"value = "<?php echo $orig_end_date ?>">
						</td>
						<td  valign="top">
							<input type = "hidden" name = "run_report" value = "1"><input type = "submit" value = "go">
						</td>
					</tr>
				</table>
				</form>
				<?php echo $task_table ?>
				<?php echo $resource_table  ?>
				<br>
				
				<br>
				

			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>

</body>
</html>