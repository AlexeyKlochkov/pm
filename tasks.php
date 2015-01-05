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
$archived = "";
$active = 1;
if (!empty($_GET["archived"])){
	$archived = $_GET["archived"];
	$active = 0;
}
if ($active == 0){
	$active_checked = "checked";
	$active_flag = 0;
}else{
	$active_checked = "";
	$active_flag = 1;
}

$project_id = "";
$selected_project_id = "";
if (!empty($_GET["project_id"])){
	$project_id = $_GET["project_id"];
	$selected_project_id = $project_id;
}

$campaign_id = "";
if (!empty($_GET["campaign_id"])){
	$campaign_id = $_GET["campaign_id"];
}

$task_id = "";
if (!empty($_GET["task_id"])){
	$task_id = $_GET["task_id"];
}

$project_manager_id = "";
if (!empty($_GET["project_manager_id"])){
	$project_manager_id = $_GET["project_manager_id"];
}
//print $project_manager_id;
$phase_id = "";
if (!empty($_GET["phase_id"])){
	$phase_id = $_GET["phase_id"];
}

$task_id = "";
if (!empty($_GET["task_id"])){
	$task_id = $_GET["task_id"];
}

$arr_tasks = get_task_list($company_id, $project_id, $campaign_id, $phase_id, $project_manager_id, $task_id, $user_id, $active_flag);

//print_r($arr_projects);

$task_table = "<table width = \"100%\" class = \"stats_table\"><tr><th>Campaign</th><th>Project</th><th>IPM</th><th>Schedule </th><th>Phase</th><th>#</th><th>Task</th><th>Total Time</th><th>Add Time</th><th>Task<br>Complete?</th><th>&nbsp;</th></tr>";
if (!empty($arr_tasks)){
	foreach ($arr_tasks as $task_row){
		$schedule_task_id = $task_row["schedule_task_id"];
		$campaign_code = $task_row["campaign_code"];
		$project_code = $task_row["project_code"];
		$project_name = $task_row["project_name"];
		$schedule_name = $task_row["schedule_name"];
		$task_name = $task_row["task_name"];
		$task_display_order = $task_row["display_order"];
		$project_manager_initials = $task_row["initials"];
		$phase_name = $task_row["phase_name"];
		$current_phase_id = $task_row["phase_id"];
		$complete = $task_row["complete"];
		if ($complete == 1){	
			$complete_string = "yes";
		}else{
			$complete_string = "no";
		}
		$assignee_list = get_assignee_initials_for_popup($schedule_task_id, $project_name, $task_name, $schedule_name);
		$total_time_worked = get_total_time_by_schedule_task_id($schedule_task_id);
		$total_time_worked = substr($total_time_worked, 0, -3);
		$task_table .= "<td>" . $campaign_code . "</td>";
		$task_table .= "<td>" . $project_code . "</td>";
		$task_table .= "<td>" . $project_manager_initials . "</td>";
		$task_table .= "<td>" . $schedule_name . "</td>";
		$task_table .= "<td>" . $phase_name . "</td>";
		$task_table .= "<td>" . $task_display_order . "</td>";
		//$task_table .= "<td>" . $task_display_order . "(" . $schedule_task_id  . ")</td>";
		$task_table .= "<td>" . $task_name . "</td>";
		$task_table .= "<td align=\"right\">" . $total_time_worked . "</td>";
		$task_table .= "<td>" . $assignee_list . "</td>";
		$task_table .= "<td>" . $complete_string . "</td>";
		$task_table .= "</tr>";
	}
}else{
	$task_table .= "<tr><td>No results for this query</td></tr>";

}
$task_table .= "</table>";

$campaign_select = get_campaign_code_select($company_id, $campaign_id);
$campaign_select = str_replace("Please select", "All", $campaign_select );
$project_code_select = get_project_code_select($company_id, $selected_project_id);
$project_code_select = str_replace("Please select", "All", $project_code_select );
//$business_unit_select = get_business_unit_select($company_id, $business_unit_id);

$task_select =  get_task_select($company_id, $task_id);
$task_select = str_replace("Please Select", "All", $task_select );

$project_manager_select =  get_project_manager_select($company_id, $project_manager_id);
$project_manager_select = str_replace("Please select", "All", $project_manager_select );

$phase_select =  get_phase_select($company_id, $phase_id);
$phase_select = str_replace("Please select", "All", $phase_select );

$today = date("m/d/Y"); 
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Tasks</title>

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
	  document.getElementById('wuid').innerHTML=user_id;
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
				<h1>Tasks</h1>
				<table width = "100%" class = "small_link">
					<tr>
						<td align = "left">
							<div class = "error"><?php echo $error_message ?></div>
						</td>
					</tr>
				</table>
				<table class = "small_link" width = "90%">
					<tr><form id = "get_tasks" action = "tasks.php" method = "GET">
						<td>Campaign:<br><?php echo $campaign_select ?></td>
						<td>Project:<br><?php echo $project_code_select ?></td>
						<td>Task:<br><?php echo $task_select ?></td>
					</tr>
					<tr>
						<td>Project Manager:<br><?php echo $project_manager_select ?></td>
						<td>Phase:<br><?php echo $phase_select ?></td>
						<td>Project is Archived:<br><input <?php echo $active_checked ?> type = "checkbox" value = "1" name = "archived"></td>
						<td><input type = "submit" value = "go"></td>
						</form>
					</tr>
				</table>
				<?php echo $task_table  ?>

			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>

<div id="popup1" class="popup">
	<form id = "add_time" action = "add_schedule_task_time.php" method = "POST">
		<table border = "0" class = "budget">
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
					h:<input class="required" type = "text" name = "hours" size = "1"> m:<input class="required" type = "text" name = "minutes" size = "1">
				</td>
			</tr>
			<tr>
				<td>
					Day:
				</td>
				<td>
					<input type = "text" name = "day" class="datepicker" size = "8"value = "<?php echo $today ?>" style =  "position: relative; z-index: 100000;">
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
					<input id = "wuid" type = "hidden" name = "worker_user_id" value = "">
					<input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
					<input type = "hidden" name = "campaign_id" value = "<?php echo $campaign_id ?>">
					<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
					<input type = "hidden" name = "task_id" value = "<?php echo $task_id ?>">
					<input type = "hidden" name = "project_manager_id" value = "<?php echo $project_manager_id ?>">
					<input type = "hidden" name = "phase_id" value = "<?php echo $phase_id ?>">
					<input type = "hidden" name = "archived" value = "<?php echo $archived ?>">
					<input type = "hidden" name = "page" value = "tasks">
					<input type = "submit" value = "add time">
				</td>
			</tr>
		</table>
	</form>

</div>
<div id="bg" class="popup_bg"></div> 
</body>
</html>