<?php 
require_once "loggedin.php";
require_once "functions/dbconn.php";
require_once "functions/queries.php";
require_once "functions/functions.php";
//general user stuff



$arr_projects = get_projects_user_is_assigned_to($user_id);

//print_r($arr_projects);

$project_table = "<table width = \"100%\" class = \"stats_table\"><tr><th>Campaign</th><th>Code</th><th>Name</th><th>Status</th><th>IPM</th><th>ACD</th><th>&nbsp;</th></tr>";
if (!empty($arr_projects)){
	foreach ($arr_projects as $project_row){
			$project_id = $project_row["project_id"];
			$project_code = $project_row["project_code"];
			$project_name = $project_row["project_name"];
			$campaign_code = $project_row["campaign_code"];
			//$product_name = $project_row["product_name"];
			//$audience_name = $project_row["audience_name"];
			$project_status = $project_row["project_status_name"];
			$project_manager = $project_row["pm_lname"];
			$acd_lname = $project_row["acd_lname"];
			$project_table .= "<tr><td>" . $campaign_code . "</td>";
			$project_table .= "<td>" . $project_code . "</td>";
			$project_table .= "<td>" . $project_name . "</td>";
			//$project_table .= "<td>" . $product_name . "</td>";
			//$project_table .= "<td>" . $audience_name . "</td>";
			$project_table .= "<td>" . $project_status . "</td>";
			$project_table .= "<td>" . $project_manager . "</td>";
			$project_table .= "<td>" . $acd_lname . "</td>";
			$project_table .= "<td><a href =\"manage_project.php?p=" . $project_id . "\">View</a></td>";
			$project_table .= "</tr>";
	}
}else{
	$project_table .= "<tr><td>No current projects.</td></tr>";

}
$project_table .= "</table>";


$task_table3 = "<table width = \"100%\" class = \"stats_table\"><tr><th>Project</th><th>Schedule</th><th>Next Step</th><th>Progress</th><th>Approval</th></tr>";
$arr_tasks = get_approvals_for_user($user_id);
if (!empty($arr_tasks)){
	foreach ($arr_tasks as $task_row){
		$project_id = $task_row["project_id"];
		$project_code = $task_row["project_code"];
		$project_name = $task_row["project_name"];
		$schedule_name = $task_row["schedule_name"];
		$schedule_task_id = $task_row["schedule_task_id"];
		$progress = $task_row["progress"];
		$task_name = $task_row["task_name"];
		$is_approved =  $task_row["is_approved"];
		$task_table3 .= "<tr><td>" . $project_name . " (" . $project_code . ")</td><td>" . $schedule_name . "</td><td>" . $task_name . "</td><td align=\"right\">" . $progress . "</td><form action = \"task_approval.php\" method = \"POST\"><td><input type = \"submit\" value = \"approval form\"><input type = \"hidden\" name = \"stid\" value = \"" . $schedule_task_id . "\"><input type = \"hidden\" name = \"company_id\" value = \"" . $company_id . "\"><input type = \"hidden\" name = \"page\" value = \"index\"></td></form></tr>";
	}
}else{
	$task_table3 .= "<tr><td colspan = \"5\">No current approvals</td></tr>";
}
$task_table3 .= "</table>";	
$task_table2 = "<table width = \"100%\" class = \"stats_table\"><tr><th>Project</th><th>Next Step</th><th>Due Date</th><th>Progress</th><th colspan=\"2\">Time Worked</th></tr>";
$arr_tasks = get_tasks_with_no_time_for_user($user_id);
if (!empty($arr_tasks)){
	foreach ($arr_tasks as $task_row){
		$project_id = $task_row["project_id"];
		$project_code = $task_row["project_code"];
		$project_name = $task_row["project_name"];
		$project_name  = str_replace("'", "", $project_name);
		$schedule_name = $task_row["schedule_name"];
		$schedule_task_id = $task_row["schedule_task_id"];
		$progress = get_percentage_select("progress", $task_row["progress"]);
		$task_name = $task_row["task_name"];
		$task_name  = str_replace("'", "", $task_name);
		$end_date = convert_mysql_to_datepicker($task_row["end_date"]);
		$time_worked =  $task_row["time_worked"];
		if(!empty($time_worked)){
			$time_worked = round($time_worked, 2) . " hrs";
		}
		$popup_string = "<a href=\"#\" onclick=\"openpopup('popup1','" . $schedule_task_id . "','" . $user_id . "','" . $project_name . "','" . $task_name . "','" . $schedule_name . "','" . $user_full_name . "')\">add time</a>";
		$task_table2 .= "<tr><td><a href = \"manage_project.php?p=" . $project_id . "\" target=\"_blank\">" . $project_name . " (" . $project_code . ")</a></td><td>" . $task_name . "</td><td align=\"right\">" . $end_date . "</td><form action = \"update_progress.php\" method = \"POST\"><td align=\"right\">" . $progress . "<input type = \"submit\" value = \"update\"></td><input type = \"hidden\" name = \"stid\" value = \"" . $schedule_task_id . "\"></form><td align=\"right\">" . $time_worked . "</td><td nowrap>" . $popup_string  . "</td></tr>";
	}
}else{
	$task_table2 .= "<tr><td colspan = \"4\">No current tasks</td></tr>";
}
$task_table2 .= "</table>";


$area1_content = "<b>Current Projects</b>" . $project_table;
$area2_content = "<b>Pending Approvals</b>" . $task_table3;
$area3_content = "<b>Open Tasks</b>" . $task_table2;
//$area4_content = "<b>Pending Approvals</b>" . $task_table3;



$today = date("m/d/Y"); 
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
							
							<div id = "area1">
							<?php echo $area1_content ?>
							</div>
						</td>
						<td valign = "top">
							<div id = "area2">
								<?php echo $area2_content ?>
							</div>
						</td>
					</tr>
					<tr>
						<td width = "100%" valign = "top" height="100" colspan="2">
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
					<input type = "hidden" name = "campaign_id" value = "">
					<input type = "hidden" name = "project_id" value = "">
					<input type = "hidden" name = "task_id" value = "">
					<input type = "hidden" name = "project_manager_id" value = "">
					<input type = "hidden" name = "phase_id" value = "">
					<input type = "hidden" name = "archived" value = "">
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
<div id="bg" class="popup_bg"></div> 


</body>
</html>