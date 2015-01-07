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
		$error_message = "Approval Updated.";
	}
}
$kickout=0;
if (!empty($_POST["stid"])){
	$schedule_task_id = $_POST["stid"];
	$posted_company_id = $_POST["company_id"];
	$page = $_POST["page"];
}else{
	if (!empty($_GET["stid"])){
		$schedule_task_id = $_GET["stid"];
		$page = "task_approval";
	}else{
		$kickout=1;
	}
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
$approved_by = $arr_project[0]["approved_by"];
$approval_date = $arr_project[0]["approval_date"];
$approver_name = $arr_project[0]["a_fname"] . " " . $arr_project[0]["a_lname"];
$is_approved = $arr_project[0]["is_approved"];
$approval_notes = $arr_project[0]["approval_notes"];
$campaign_company_id = $arr_project[0]["company_id"];
$approval_file_id = $arr_project[0]["approval_file_id"];
$directory = "project_files/" . $project_code . "/";
$approve_text =  "I (<b>" . $user_full_name . "</b>) approve <b>" . $task_name . "</b>.";
$unapprove_text = "This task is <b>NOT approved</b>.";
$approval_type_text = "Please indicate whether you approve or do not approve task <b>" . $task_name . "</b>.";

if(!empty($approval_file_id)){
	$arr_approval_file = get_project_file_info($approval_file_id);
	$approval_file_name = $arr_approval_file[0]["project_file_name"];
	$approval_file_notes = $arr_approval_file[0]["file_notes"];
	$approval_file_type = $arr_approval_file[0]["file_type"];
	$approve_text =  "I (<b>" . $user_full_name . "</b>) approve file <b>" . $approval_file_name . "</b>.";
	$unapprove_text = "File <b>" . $approval_file_name . "</b> is NOT approved.";
	$approval_type_text = "<b>This approval is specifically associated with this file:<br><a href = \"" . $directory . $approval_file_name . "\" target = \"_blank\">" . $approval_file_type . " - " . $approval_file_notes . " (" . $approval_file_name . ")</a></b>";
}

if ($company_id <> $campaign_company_id ){
	$kickout = 1;
}

if ($kickout ==1){
	$location = "Location: loggedout.php?e=1";
	header($location) ;
}

$header_table = "<table class = \"budget\">";
$header_table .= "<tr><th align=\"left\">Project:</th><td><a href = \"manage_project.php?p=" . $project_id . "\" target=\"_blank\">" . $project_name . "</a></td></tr>";
$header_table .= "<tr><th align=\"left\">Project Manager:</th><td>" . $project_manager_name . "</td></tr>";
$header_table .= "<tr><th align=\"left\">Schedule:</th><td><a href = \"manage_project.php?p=" . $project_id . "&show_schedules=1#schedules\" target=\"_blank\">" . $schedule_name . "</a></td></tr>";
$header_table .= "<tr><th align=\"left\">Task:</th><td>" . $task_name . "</td></tr>";
$header_table .= "<tr><th align=\"left\">Task Manager:</th><td>" . $task_manager_name . "</td></tr>";
$header_table .= "<tr><th align=\"left\">Approver:</th><td>" . $user_full_name  . "</td></tr>";
$header_table .= "</table>";
$project_file_table = "<table class = \"budget\">";
$file_count = 0;
$arr_files = get_project_files_by_type($project_id, "CB", 1);
if (!empty($arr_files)){
		$project_file_table .= "<tr><th align=\"left\" colspan = \"2\" style = \"background-color: 6741A6;2013-07-10\">Creative Brief</th></tr>";
		$project_file_table .= "<tr><th>File</th><th>Notes</th></tr>";
		foreach ($arr_files as $file_row){
			$file_name = $file_row["project_file_name"];
			$file_notes = $file_row["file_notes"];
			$file_location = $directory. $file_name;
			$project_file_table .= "<tr><td><a href = \"" . $file_location . "\" target=\"_blank\">" . $file_name . "</td><td>" . $file_notes . "</td></tr>";
			$file_count ++;
		}
}


$arr_files = get_project_files_by_type($project_id, "Final", 1);
if (!empty($arr_files)){
		$project_file_table .= "<tr><th align=\"left\" colspan = \"2\" style = \"background-color: 6741A6;2013-07-10\">Final Files</th></tr>";
		$project_file_table .= "<tr><th>File</th><th>Notes</th></tr>";
		foreach ($arr_files as $file_row){
			$file_name = $file_row["project_file_name"];
			$file_notes = $file_row["file_notes"];
			$file_location = $directory. $file_name;
			$project_file_table .= "<tr><td><a href = \"" . $file_location . "\" target=\"_blank\">" . $file_name . "</td><td>" . $file_notes . "</td></tr>";
			$file_count ++;
		}
}

$arr_files = get_project_files_by_type($project_id, "Legal", 1);
if (!empty($arr_files)){
		$project_file_table .= "<tr><th align=\"left\" colspan = \"2\" style = \"background-color: 6741A6;2013-07-10\">Legal Files</th></tr>";
		$project_file_table .= "<tr><th>File</th><th>Notes</th></tr>";
		foreach ($arr_files as $file_row){
			$file_name = $file_row["project_file_name"];
			$file_notes = $file_row["file_notes"];
			$file_location = $directory. $file_name;
			$project_file_table .= "<tr><td><a href = \"" . $file_location . "\" target=\"_blank\">" . $file_name . "</td><td>" . $file_notes . "</td></tr>";
			$file_count ++;
		}
}

$arr_files = get_project_files_by_type($project_id, "Studio", 1);
if (!empty($arr_files)){
		$project_file_table .= "<tr><th align=\"left\" colspan = \"2\" style = \"background-color: 6741A6;2013-07-10\">Studio Files</th></tr>";
		$project_file_table .= "<tr><th>File</th><th>Notes</th></tr>";
		foreach ($arr_files as $file_row){
			$file_name = $file_row["project_file_name"];
			$file_notes = $file_row["file_notes"];
			$file_location = $directory. $file_name;
			$project_file_table .= "<tr><td><a href = \"" . $file_location . "\" target=\"_blank\">" . $file_name . "</td><td>" . $file_notes . "</td></tr>";
			$file_count ++;
		}
}

$arr_files = get_project_files_by_type($project_id, "Rounds", 1);
if (!empty($arr_files)){
		$project_file_table .= "<tr><th align=\"left\" colspan = \"2\" style = \"background-color: 6741A6;\">Creative Round Files</th></tr>";
		$project_file_table .= "<tr><th>File</th><th>Notes</th></tr>";
		foreach ($arr_files as $file_row){
			$file_name = $file_row["project_file_name"];
			$file_notes = $file_row["file_notes"];
			$current_file_type = $file_row["file_type"];
			$file_location = $directory. $file_name;
			$project_file_table .= "<tr><td>" . $current_file_type . " - <a href = \"" . $file_location . "\" target=\"_blank\">" . $file_name . "</td><td>" . $file_notes . "</td></tr>";
			$file_count ++;
		}
}

if ($file_count == 0){
	$project_file_table .= "<tr><th colspan = \"2\">No project files.</th></tr>";
}
$project_file_table .= "</table>";
$not_approved_checked = "";
if ($is_approved ==2){
	$not_approved_checked = "checked";
}
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />
<title>Task Approval</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
  $(document).ready(function(){
	  $( "#task_approval" ).validate({
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
				<h1>Task Approval</h1>
				<div class = "error"><?php echo $error_message ?></div>
				<table border = "0">
					<tr>
						<td valign="top">
							<?php echo $header_table ?>
						</td>
						<td valign="top">
							<form action = "approve_task.php" id = "task_approval" method = "POST">
							<table class = "budget">
								<tr>
									<th colspan = "2">
									Task Approval
									</th>
								</tr>
<?php 
if ($is_approved<>1){
?>
								<tr>
									<td>
									<?php echo $approval_type_text ?>
									</td>
								</tr>
								<tr>
									<td>
										<input class = "required" type = "radio" name = "is_approved" value = "1"><?php echo $approve_text ?><br>
										<input <?php echo $not_approved_checked ?> class = "required" type = "radio" name = "is_approved" value = "2"><?php echo $unapprove_text ?></b>.
										
									</td>
								</tr>
								<tr>
									<td>
										<b>Approval notes</b>:<br>
										<textarea name = "approval_notes" rows="4" cols="40" maxlength="500"><?php echo $approval_notes ?></textarea>
									</td>
								</tr>
								<tr>
									<td colspan = "2">
									<input type = "hidden" name = "schedule_task_id" value = "<?php echo $schedule_task_id ?>">
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
									<input type = "hidden" name = "page" value = "<?php echo $page ?>">
									<input type = "hidden" name = "approval_file_id" value = "<?php echo $approval_file_id ?>">
										<input type = "submit" value = "Submit">
									</td>
								</tr>
<?php
}else{
?>
								<tr>
									<td colspan = "2">
										<b>Task was approved on <?php echo $approval_date ?> by <?php echo $approver_name ?></b>
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
				<h3>Project Files</h3>
				<?php echo $project_file_table  ?>
			
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>