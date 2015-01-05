<?php 
require_once "loggedin.php";
require_once "functions/dbconn.php";
require_once "functions/queries.php";
require_once "functions/functions.php";
//general user stuff

$day_count_tasks = "";
if (!empty($_GET["dct"])){
	$day_count_tasks = $_GET["dct"];
}

$day_count_approvals = "";
if (!empty($_GET["dca"])){
	$day_count_approvals = $_GET["dca"];
}
$approval_message = "";
$task_message = "";
if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 2){
		$approval_message = "Email sent.";
	}
	if ($error_num == 3){
		$approval_message = "Error sending email.";
	}
	if ($error_num == 4){
		$task_message = "Progress updated.";
	}
	if ($error_num == 5){
		$task_message = "Error updating progress.";
	}
}

$today = date("m/d/Y"); 


$project_manager_id = $user_id;

//$project_manager_id = 26;
$day_count = 0;
$task_table = "<table width = \"100%\" class = \"stats_table\"><tr><th>Project</th><th>Schedule</th><th>Task</th><th>Assigned to</th><th>Due</th><th>Progress</th></tr>";
$arr_tasks = get_open_tasks_for_pm($user_id, $day_count_tasks);
if (!empty($arr_tasks)){
	foreach ($arr_tasks as $task_row){
		$project_id = $task_row["project_id"];
		$project_code = $task_row["project_code"];
		$project_name = $task_row["project_name"];
		$schedule_name = $task_row["schedule_name"];
		$schedule_task_id = $task_row["schedule_task_id"];
		$schedule_id = $task_row["schedule_id"];
		$end_date = convert_mysql_to_datepicker($task_row["end_date"]);
		$progress = $task_row["progress"];
		$progress = get_percentage_select("progress", $task_row["progress"]);
		$task_name = $task_row["task_name"];
		$assignee_list = get_assignee_initials($schedule_task_id);
		$task_table .= "<tr><td><a href = \"manage_project.php?p=" . $project_id . "\">" . $project_name . " (" . $project_code . ")</a></td><td><a href = \"manage_schedules.php?p=" . $project_id . "\">" . $schedule_name . "</a></td><td nowrap><a href = \"manage_tasks.php?s=" . $schedule_id . "\">" . $task_name . "</a></td><td>" . $assignee_list . "</td><td>" . $end_date . "</td><form action = \"update_progress.php\" method = \"POST\"><td align=\"right\">" . $progress . "<input type = \"submit\" value = \"update\"></td><input type = \"hidden\" name = \"stid\" value = \"" . $schedule_task_id . "\"><input type = \"hidden\" name = \"dct\" value = \"" . $day_count_tasks . "\"><input type = \"hidden\" name = \"dca\" value = \"" . $day_count_approvals . "\"></form></tr>";

	}
}else{
	$task_table .= "<tr><td colspan = \"4\">No current tasks</td></tr>";
}

$task_table .= "</table>";


$arr_projects = get_projects_query($company_id, "", "", "", "", "", $project_manager_id, 1);

//print_r($arr_projects);

$project_table = "<table width = \"100%\" class = \"stats_table\"><tr><th>#</th><th>Project Code</th><th>Project Name</th><th>AOP Line of Business</th><th>Product</th><th>Status</th><th>IPM</th><th>Total Budget</th><th>Total Spend</th></tr>";
if (!empty($arr_projects)){
	foreach ($arr_projects as $project_row){
			$project_id = $project_row["project_id"];
			$project_code = $project_row["project_code"];
			$project_name = $project_row["project_name"];
			$campaign_code = $project_row["campaign_code"];
			$product_name = $project_row["product_name"];
			$audience_name = $project_row["audience_name"];
			$project_status = $project_row["project_status_name"];
			$project_manager = $project_row["last_name"];
			$media_budget = $project_row["media_budget"];
			$production_budget = $project_row["production_budget"];
			$total_budget = ($media_budget + $production_budget);
			$total_project_spend = get_spend_amount_by_project($project_id);
			$project_table .= "<tr><td>" . $project_id . "</td>";
			$project_table .= "<td><a href = \"manage_project.php?p=" . $project_id . "\">" . $project_code . "</a></td>";
			$project_table .= "<td><a href = \"manage_project.php?p=" . $project_id . "\">" . $project_name . "</a></td>";
			$project_table .= "<td>" . $campaign_code . "</td>";
			
			$project_table .= "<td>" . $product_name . "</td>";
			//$project_table .= "<td>" . $audience_name . "</td>";
			$project_table .= "<td>" . $project_status . "</td>";
			$project_table .= "<td>" . $project_manager . "</td>";
			$project_table .= "<td align = \"right\">" . add_commas($total_budget) . "</td>";
			$project_table .= "<td align = \"right\">" . add_commas($total_project_spend) . "</td>";
			$project_table .= "</tr>";
	}
}else{
	$project_table .= "<tr><td>No results for this query</td></tr>";

}
$project_table .= "</table>";

$file_select_divs = "";
$pm_approval_table = "<table width = \"100%\" class = \"stats_table\"><tr><th>Project</th><th>Schedule</th><th>Task</th><th>Assigned to</th><th>Due</th><th>Approval</th></tr>";
$arr_tasks = get_approvals_for_pm($user_id, $day_count_approvals);
if (!empty($arr_tasks)){
	foreach ($arr_tasks as $task_row){
		$project_id = $task_row["project_id"];
		$project_code = $task_row["project_code"];
		$project_name = $task_row["project_name"];
		$schedule_id = $task_row["schedule_id"];
		$schedule_name = $task_row["schedule_name"];
		$schedule_task_id = $task_row["schedule_task_id"];
		$progress = $task_row["progress"];
		$task_name = $task_row["task_name"];
		$is_approved =  $task_row["is_approved"];
		$end_date =  convert_mysql_to_datepicker($task_row["end_date"]);
		$assignee_list = get_assignee_initials($schedule_task_id);
		$file_select_list = get_document_select_by_project($project_id);
		$file_select_divs .= "<div id = \"file_select_" . $schedule_task_id . "\">" . $file_select_list . "</div>\n";
		
		$send_string = "(<a href=\"#\" onclick=\"openpopup2('popup2','" . $schedule_name . "','" . $task_name . "','" . $assignee_list . "','" . $schedule_task_id . "')\">send</a>)";
		if($assignee_list=="Nobody Assigned."){
			$send_string = "(<a href = \"manage_tasks.php?p=" . $project_id . "&s=" . $schedule_id . "\">assign</a>)";
		}
		
		
		$pm_approval_table .= "<tr><td><a href = \"manage_project.php?p=" . $project_id . "\">" . $project_name . " (" . $project_code . ")</a></td><td><a href = \"manage_schedules.php?p=" . $project_id . "\">" . $schedule_name . "</a></td><td><a href = \"manage_tasks.php?s=" . $schedule_id . "\">" . $task_name . "</a></td><td align=\"right\">" . $assignee_list  . "</td><td>" . $end_date . "</td><td>" . $send_string . "</td></tr>";
	}
}else{
	$pm_approval_table .= "<tr><td colspan = \"5\">No current approvals</td></tr>";
}
$pm_approval_table .= "</table>";

$date_range_select_tasks = get_date_range_select($company_id, $day_count_tasks, "dct");
$date_range_select_approvals = get_date_range_select($company_id,  $day_count_approvals, "dca");
$area1_content = $task_table;
$area2_content = $pm_approval_table;
$area3_content = $project_table;



?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Home</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script> 
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
	

  });

  </script>
<script language="javascript"> 
function openpopup(id,schedule_task_id,user_id,project_name,task_name,schedule_name,user_name){ 
      //Calculate Page width and height 
      var pageWidth = window.innerWidth; 
      var pageHeight = window.innerHeight; 
      if (typeof pageWidth != "number"){ 
      if (document.compatMode == "CSS1Compat"){ 
            pageWidth = document.documentElement.clientWidth; 
            pageHeight = document.documentElement.clientHeight; 
      } else { 
            pageWidth = document.body.clientWidth; 
            pageHeight = document.body.clientHeight; 
      } 
      }  
      //Make the background div tag visible... 
      var divbg = document.getElementById('bg'); 
      divbg.style.visibility = "visible"; 
        
      var divobj = document.getElementById(id); 
      divobj.style.visibility = "visible"; 
      if (navigator.appName=="Microsoft Internet Explorer") 
      computedStyle = divobj.currentStyle; 
      else computedStyle = document.defaultView.getComputedStyle(divobj, null); 
      //Get Div width and height from StyleSheet 
      var divWidth = computedStyle.width.replace('px', ''); 
      var divHeight = computedStyle.height.replace('px', ''); 
      var divLeft = (pageWidth - divWidth) / 2; 
      var divTop = (pageHeight - divHeight) / 2; 
      //Set Left and top coordinates for the div tag 
      divobj.style.left = divLeft + "px"; 
      divobj.style.top = divTop + "px"; 
      //Put a Close button for closing the popped up Div tag 
      if(divobj.innerHTML.indexOf("closepopup('" + id +"')") < 0 ) 
      divobj.innerHTML = "<a href=\"#\" onclick=\"closepopup('" + id +"')\"><span class=\"close_button\">X</span></a>" + divobj.innerHTML; 
	  document.getElementById('stid').value=schedule_task_id;
	  document.getElementById('pname').innerHTML=project_name;
	  document.getElementById('tname').innerHTML=task_name;
	  //document.getElementById('wuid').innerHTML=user_id;
	  document.getElementById('sname').innerHTML=schedule_name;
	  document.getElementById('ename').innerHTML=user_name;
}


function openpopup2(id,schedule_name,task_name,user_initials,schedule_task_id){ 
      //Calculate Page width and height 
      var pageWidth = window.innerWidth; 
      var pageHeight = window.innerHeight; 
      if (typeof pageWidth != "number"){ 
      if (document.compatMode == "CSS1Compat"){ 
            pageWidth = document.documentElement.clientWidth; 
            pageHeight = document.documentElement.clientHeight; 
      } else { 
            pageWidth = document.body.clientWidth; 
            pageHeight = document.body.clientHeight; 
      } 
      }  
      //Make the background div tag visible... 
      var divbg = document.getElementById('bg'); 
      divbg.style.visibility = "visible"; 
        
      var divobj = document.getElementById(id); 
      divobj.style.visibility = "visible"; 
      if (navigator.appName=="Microsoft Internet Explorer") 
      computedStyle = divobj.currentStyle; 
      else computedStyle = document.defaultView.getComputedStyle(divobj, null); 
      //Get Div width and height from StyleSheet 
      var divWidth = computedStyle.width.replace('px', ''); 
      var divHeight = computedStyle.height.replace('px', ''); 
      var divLeft = (pageWidth - divWidth) / 2; 
      var divTop = (pageHeight - divHeight) / 2; 
      //Set Left and top coordinates for the div tag 
      divobj.style.left = divLeft + "px"; 
      divobj.style.top = divTop + "px"; 
      //Put a Close button for closing the popped up Div tag 
      if(divobj.innerHTML.indexOf("closepopup('" + id +"')") < 0 ) 
      divobj.innerHTML = "<a href=\"#\" onclick=\"closepopup('" + id +"')\"><span class=\"close_button\">X</span></a>" + divobj.innerHTML; 
	  document.getElementById('schedule_name').innerHTML=schedule_name;
	  document.getElementById('task_name').innerHTML=task_name;
	  document.getElementById('user_initials').value=user_initials;
	  document.getElementById('schedule_task_id').value=schedule_task_id;
	  document.getElementById('file_pulldown').innerHTML=document.getElementById('file_select_' + schedule_task_id).innerHTML;
} 


function closepopup(id){ 
      var divbg = document.getElementById('bg'); 
      divbg.style.visibility = "hidden"; 
      var divobj = document.getElementById(id); 
      divobj.style.visibility = "hidden"; 
} 


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
				<table width = "100%" border = "0" class = "home_grid">
					<tr>
						<td width = "60%" valign = "top"><!--top left TD--> 
							<form action = "index.php" method = "GET"><b>Open Tasks</b>&nbsp;&nbsp;<?php echo $date_range_select_tasks ?><input type = "hidden" name = "dca" value = "<?php echo $day_count_approvals ?>"></form><div class = "error"><?php echo $task_message ?></div>
							<div id = "area1">
							<?php echo $area1_content ?>
							</div>
						</td>
						<td valign = "top">
							<form action = "index.php" method = "GET"><b>Pending Approvals</b>&nbsp;&nbsp;<?php echo $date_range_select_approvals ?><input type = "hidden" name = "dct" value = "<?php echo $day_count_tasks ?>"></form><div class = "error"><?php echo $approval_message ?></div>
							<div id = "area2">
								<?php echo $area2_content ?>
							</div>
						</td>
					</tr>
					<tr>
						<td width = "100%" valign = "top" height="100" colspan="2">
							<table width = "100%" border = "0">
								<tr>
									<td><b>Projects/Budgets</b>
									</td>
									<td align="right">
									<a href = "projects.php?project_manager_id=<?php echo $user_id?>">New Project</a>&nbsp;&nbsp;
									</td>
								</tr>
							</table>
							
							<div id = "area3">
								<?php echo $area3_content ?>
							</div>
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

<div id="popup1" class="popup">
	<form id = "add_time" action = "add_schedule_task_time.php" method = "POST" class = "budget">
		<table border = "0">
			<tr>
				<td>
					Project:
				</td>
				<td>
					<div id = "pname">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>
					Schedule:
				</td>
				<td>
					<div id = "sname">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>
					employee:
				</td>
				<td>
					<div id = "ename">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>
					Task:
				</td>
				<td>
					<div id = "tname">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>
					Enter Time:
				</td>
				<td nowrap>
					h:<input class="required" type = "text" name = "hours" size = "1" value="0"> m:<input class="required" type = "text" name = "minutes" size = "1" value="0">
				</td>
			</tr>
			<tr>
				<td>
					Day:
				</td>
				<td>
					<input type = "text" name = "day" class="datepicker" size = "8" value = "<?php echo $today ?>">
				</td>
			</tr>
			<tr>
				<td>
					Notes:
				</td>
				<td>
					<input type = "text" name = "notes" size = "15">
				</td>
			</tr>
			<tr>
				<td colspan = "2">
					<input id = "stid" type = "hidden" name = "schedule_task_id" value = "">
					<input id = "wuid" type = "hidden" name = "worker_user_id" value = "<?php echo $user_id ?>">
					<input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
					<input type = "hidden" name = "page" value = "index">
					<input type = "submit" value = "add time">
				</td>
			</tr>
		</table>
	</form>
<script type="text/javascript">
$( "#datepicker" ).datepicker();
</script>
</div>
<div id="popup2" class="popup">
	<form id = "add_file" action = "send_approval_email.php" method = "POST" class="budget">
		<table border = "0">
			<tr>
				<td>
					Project:
				</td>
				<td>
					<?php echo $project_code . " - " . $project_name ?>
				</td>
			</tr>
			<tr>
				<td>
					Schedule:
				</td>
				<td>
					<div id = "schedule_name">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>Task:</td>
				<td>
					<div id = "task_name">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>Approve Specific Document?</td>
				<td>
					<div id = "file_pulldown"></div>
				</td>
			</tr>
			<tr>
				<td>Comment:</td>
				<td>
					<textarea name = "comment" cols = "40">Your approval is required for this task.</textarea>
				</td>
			</tr>
			<tr>
				<td colspan = "2">
					<input id = "user_initials" type = "hidden" name = "user_initials" value = "">
					<input type = "hidden" name = "pg" value = "index">
					<input type = "hidden" name = "dct" value = "<?php echo $day_count_tasks ?>">
					<input type = "hidden" name = "dca" value = "<?php echo $day_count_approvals ?>">
					<input id = "schedule_task_id" type = "hidden" name = "schedule_task_id" value = "">
					<input type = "submit" value = "Send">
				</td>
			</tr>
		</table>
	</form>
</div>


<div id="bg" class="popup_bg"></div> 
<div id = "hidden_select_divs" style = "display: none;">
<?php echo $file_select_divs?>
</div>
</body>
</html>