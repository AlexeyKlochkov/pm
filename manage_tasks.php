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
		$error_message = "Tasks updated.";
	}
		if ($error_num == 3){
		$error_message = "Assignees updated.";
	}
}
$schedule_id = 0;
if (!empty($_GET["s"])){
	$schedule_id = $_GET["s"];
}
$show_users = "hide";
if (!empty($_GET["showusers"])){
	if ($_GET["showusers"] == 1){
		$show_users = "show";
	}
}

$arr_schedule_info = get_schedule_info($schedule_id);

if (empty($arr_schedule_info)){
	$location = "Location: loggedout.php";
	header($location) ;
}


$schedule_name = $arr_schedule_info[0]["schedule_name"];
$project_id = $arr_schedule_info[0]["project_id"];
$project_name = $arr_schedule_info[0]["project_name"];
$project_code = $arr_schedule_info[0]["project_code"];
$task_select = get_task_select($company_id, 0);
$manager_select = get_project_user_select($project_id, "task_manager_id", "None", 0, 0);
$assignee1_select = get_project_user_select($project_id, "assignee1", "Please Select", 0, 1);
$assignee2_select = get_project_user_select($project_id, "assignee2", "None", 0, 0);
$percentage_select = get_percentage_select("progress", 0);
$project_start_date = date("m/d/Y"); 
if(!empty($arr_schedule_info[0]["start_date"])){
	if ( $arr_schedule_info[0]["start_date"] == "1969-12-31"){
		$project_start_date = date("m/d/Y"); 
	}elseif ( $arr_schedule_info[0]["start_date"] == "0000-00-00"){
		$project_start_date = date("m/d/Y"); 
	
	}else{
		$project_start_date = convert_mysql_to_datepicker($arr_schedule_info[0]["start_date"]);
	}
}
//print $project_start_date;
$div_list = "";
$task_table = "<form action = \"update_tasks.php\" method = \"POST\"><table width = \"100%\" class = \"manage_task_table\"><tr><th>Order</th><th>Task</th><th>Manager</th><th>Start/End</th><th>Hours/Mins</th><th>Pred</th><th>Progress</th><th>Complete</th><th>Assignee(s)</th><th colspan = \"3\">&nbsp;</th></tr>";
$task_table .= "<tr><td align=\"right\" colspan = \"9\"><input type = \"submit\" value = \"Update Tasks\"></td><td colspan = \"2\">&nbsp;</td><td colspan = \"1\"><div class = \"error\">DEL</div></td></tr>";
$arr_schedule_tasks = get_schedule_tasks($schedule_id);
$n=0;
//print_r($arr_schedule_tasks);
$num_tasks = 0;
$schedule_task_id_list = "";
if (!empty($arr_schedule_tasks)){
	$num_tasks = count($arr_schedule_tasks);
	foreach ($arr_schedule_tasks as $task_row){
		$task_down_arrow = "";
		$task_up_arrow = "";
		$display_order = $task_row["display_order"];
		$schedule_task_id = $task_row["schedule_task_id"];
		$task_name = $task_row["task_name"];
		$task_id = $task_row["task_id"];
		$manager_name = $task_row["initials"];
		$manager_id = $task_row["user_id"];
		$start_date = translate_mysql_todatepicker($task_row["start_date"]);
		$end_date = translate_mysql_todatepicker($task_row["end_date"]);
		$estimated_hours = $task_row["estimated_hours"];
		$is_approval = $task_row["is_approval"];
		$predecessor = $task_row["predecessor"];
		$default_role_id = $task_row["role_id"];
		$default_role_name = $task_row["role_name"];
		$assignee_list = get_assignee_initials($schedule_task_id);
		if ($is_approval == 1){
			$assignee_form = get_assignee_form_radio($project_id, $schedule_task_id, $schedule_id);
		}else{
			$assignee_form = get_assignee_form($project_id, $schedule_task_id, $schedule_id);
		}
		
		$complete= $task_row["complete"];
		$complete_checked = "";
		if ($complete == 1){
			$complete_checked = "checked";
		}
		list($hours, $minutes, $seconds) = explode(":", $estimated_hours);
		$progress = $task_row["progress"];
		
		if (!empty($arr_schedule_tasks[$n+1]["display_order"])){
			$next_task_order = $arr_schedule_tasks[$n+1]["display_order"];
		}else{
			$next_task_order = "0";
		}
		
		if ($display_order <> 1){
				$swap1 = $display_order;
				$swap2 = $display_order - 1;
				$task_up_arrow = "<a href = \"move_schedule_task.php?s=" . $schedule_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_up.png\" border=\"0\"></a>";
			}
		if ($display_order < $next_task_order){
			$swap1 = $display_order;
			$swap2 = $display_order + 1;
			$task_down_arrow = "<a href = \"move_schedule_task.php?s=" . $schedule_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_down.png\" border=\"0\"></a>";
		}
		
		
		$curent_task_select = get_task_select($company_id, $task_id);
		$curent_task_select = str_replace("name = \"task_id\"", "name = \"" . $schedule_task_id . "-task_id\"", $curent_task_select);
		
		$current_manager_select = get_project_user_select($project_id, $schedule_task_id ."-task_manager_id", "None", $manager_id , 0);
		
		$current_percentage_select = get_percentage_select("progress", $progress);
		$current_percentage_select = str_replace("name = \"progress\"", "name = \"" . $schedule_task_id . "-progress\"", $current_percentage_select);
		
		$task_table .= "<tr>";
		$task_table .= "<td valign=\"top\">" . $display_order . "</td>";
		$task_table .= "<td valign=\"top\">" . $curent_task_select . "<br>&nbsp;(" . $default_role_name . ")</td>";
		$task_table .= "<td valign=\"top\">" . $current_manager_select . "</td>";
		$task_table .= "<td nowrap align=\"right\" valign=\"top\">s:<input class = \"datepicker\" type = \"text\" name = \"" . $schedule_task_id . "-start_date\" value =\"" . $start_date  . "\" size = \"6\"><br>";
		$task_table .= "		 e:<input class = \"datepicker\" type = \"text\" name = \"" . $schedule_task_id . "-end_date\" value =\"" . $end_date  . "\" size = \"6\"></td>";
		$task_table .= "<td nowrap align=\"right\" valign=\"top\">h:<input type = \"text\" name = \"" . $schedule_task_id . "-hours\" value =\"" . $hours  . "\" size = \"2\"><br>";
		$task_table .= "       m:<input type = \"text\" name = \"" . $schedule_task_id . "-minutes\" value =\"" . $minutes  . "\" size = \"2\"></td>";
		$task_table .= "<td nowrap valign=\"top\"><input type = \"text\" name = \"" . $schedule_task_id . "-predecessor\" value =\"" . $predecessor  . "\" size = \"2\"></td>";
		$task_table .= "<td nowrap valign=\"top\">" . $current_percentage_select  . "%</td>";
		$task_table .= "<td nowrap valign=\"top\"><input type = \"checkbox\" " . $complete_checked . " name = \"" . $schedule_task_id . "-complete\" value = \"1\"></td>";
		$task_table .= "<td valign=\"top\">" . $assignee_list  . "<br><a href=\"#\" onclick=\"openpopup('popup" . $schedule_task_id . "')\">assign</a><br><a href=\"#\" class = \"show_avail\" param=\"?employee_id=&role_id=" . $default_role_id . "&start_date=" . $start_date . "&end_date=" . $end_date . "&run_report=1&schedule_id=" . $schedule_id . "&project_id=" . $project_id . "&assignee_list=" . $assignee_list . "&task_name=" . $task_name . "&schedule_task_id=" . $schedule_task_id . "&is_approval=" . $is_approval . "\">check avail</a> </td>";
		//$task_table .= "<td>" . $task_up_arrow  . "</td>";
		//$task_table .= "<td>" . $task_down_arrow  . "</td>";
		$task_table .= "<td colspan = \"2\"><a href=\"#\" onclick=\"openpopup2('popup2','" . $schedule_task_id . "','" . $schedule_id . "','" .  $task_name . "','" . $display_order . "')\">move</a></td>";
		//$task_table .= "<td><a href = \"del_schedule_task.php?stid=" . $schedule_task_id  . "&s=" . $schedule_id . "&d=" . $display_order . "\" onclick=\"return confirm('Are you sure want to delete this task?');\">del</a></td>";
		$task_table .= "<td><input type = \"checkbox\" name=\"del-" . $schedule_task_id . "-" . $display_order . "\"></td>\n";
		$task_table .= "</tr>";
		
		$schedule_task_id_list .= $schedule_task_id . "-";
		$div_list .= "<div id=\"popup" . $schedule_task_id . "\" class=\"popup\">" . $assignee_form . "</div>\n";
		$n++;
	}
	$schedule_task_id_list = substr($schedule_task_id_list, 0, -1);
	$task_table .= "<tr><td align=\"right\" colspan = \"11\"><input type = \"hidden\" name = \"schedule_id\" value = \"" . $schedule_id ."\"><input type = \"hidden\" name = \"schedule_task_id_list\" value = \"" . $schedule_task_id_list ."\"><input type = \"submit\" value = \"Update Tasks\"></td><td colspan = \"3\">&nbsp;</td></tr>";
	
	
	}else{
$task_table .= "<tr><td colspan = \"11\">No tasks</td></tr>";

}

$task_table .= "</table></form>";


$schedule_template_select = get_schedule_template_select($company_id, 0);

$task_num_select = "";

if(!empty($arr_schedule_tasks)){
	$task_count = count($arr_schedule_tasks);
	//print $task_count;
	$task_num_select .= "<select name = \"after_task\" id = \"after_task_select\">\n";
	for ($i=1; $i<=$task_count; $i++)
	  {
		$task_num_select .= "<option value = \"" . $i . "\">" . $i . "</option>\n";
	  }
	$task_num_select .= "</select>";
}

$task_num_select1 = str_replace("after_task_select", "after_task_select_1", $task_num_select) ;

$task_num_select_with_task_name = "";

$n=0;
if(!empty($arr_schedule_tasks)){
	$task_count = count($arr_schedule_tasks);
	//print $task_count;
	$task_num_select_with_task_name .= "<select name = \"after_task\">\n";
	$task_num_select_with_task_name .= "<option value = \"0\">0 - Beginning</option>\n";
	while ($n<$task_count)
	  {
		$current_task_name = $arr_schedule_tasks[$n]["task_name"];
		$task_num_select_with_task_name .= "<option value = \"" . ($n+1) . "\">" . ($n+1) . " - " . $current_task_name . "</option>\n";
		$n++;
	  }
	$task_num_select_with_task_name .= "</select>";

}



$people_table = "<div id = \"people_container\"><a href = \"#\" id = \"project_people_click\">Project People</a><div id = \"project_people\" style = \"display: none\"><table width = \"250\" class = \"people\">";
$arr_people = get_project_people($project_id);
//print_r($arr_people);
if (!empty($arr_people)){
	foreach ($arr_people as $people_row){
		$project_user_id = $people_row["project_user_id"];
		$first_name =  $people_row["first_name"];
		$last_name =  $people_row["last_name"];
		$role_abbrev = $people_row["role_abbrev"];
		$people_table .= "<tr><td>" . $first_name . " " . $last_name . " (" . $role_abbrev . ")</td><td><a href = \"del_project_person.php?page=manage_tasks&puid=" . $project_user_id . "&s=" . $schedule_id . "\">del</a></td></tr>";

	}
}

$arr_users = get_users_for_project2($project_id);
//print_r($arr_users);
$current_role_abbrev = "";
$user_table = "<table width = \"250\" class = \"people\">";
if (!empty($arr_users)){
	foreach ($arr_users as $user_row){
		$role_abbrev = $user_row["role_abbrev"];
		$role_name = $user_row["role_name"];
		$user_id = $user_row["user_id"];
		$first_name = $user_row["first_name"];
		$last_name = $user_row["last_name"];
		
		//if ($current_role_abbrev <> $role_abbrev){
		//	$user_table .= "<tr><td colspan = \"2\"><b>" . $role_name . "</b></td></tr>";
		//}
		$user_table .= "<tr><td>" . $first_name . " " . $last_name . " (" . $role_abbrev . ")</td><form action = \"add_project_person.php\" method = \"post\"><td><input type = \"hidden\" name = \"user_id\" value = \"" . $user_id . "\"><input type = \"hidden\" name = \"page\" value = \"manage_tasks\"><input type = \"hidden\" name = \"schedule_id\" value = \"" . $schedule_id . "\"><input type = \"hidden\" name = \"project_id\" value = \"" . $project_id . "\"><input type = \"submit\" value = \"add\"></td></form></tr>";
		$current_role_abbrev = $role_abbrev;
	}
}

$user_table .= "</table>";

$people_table .= "<tr><td colspan = \"2\"><a href=\"#\" id=\"add_people_click\">Add People</a><br><div id = \"add_people\" style = \"display:none;\">" . $user_table . "</div></td></tr>";



$people_table .= "</table></div></div>";

?>

<html>
<head>

<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Manage Tasks</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){

  
    $( "#new_task_form" ).validate({
	  rules: {
		start_date: {
			required: true
		},
		end_date: {
			required: true
		}

	  }
	});
	$( "#add_tasks_from_template" ).validate({
	  rules: {
		start_date: {
			required: true
		}

	  }
	});
	$('#project_people').<?php echo $show_users ?>();
	<?php
	if($show_users=="show"){
	?>
	$('#add_people_click').text("close");
	$("#add_people").show();
	<?php
	}
	?>


	// jQuery functions go here.
	$('#add_people_click').click(function() {
		$('#add_people').toggle();
		var add_people_text = $('#add_people_click').text();
		if($("#add_people").is(":visible")){
			//alert("visible");
			$('#add_people_click').text("close");
		}else{
			$('#add_people_click').text("Add People");
			//alert("hidden");
		}
		return false;
	});
	
	$( ".datepicker" ).datepicker();
	
$(".show_avail").click(function () {
	//alert("foo");
	$("#availability_frame").show();
	$("#close").show();
    
	var parameters = "";
	parameters += $( this ).attr('param');
	url_to_load = "resource_frame.php" + parameters;
	//alert(url_to_load);
	$("#availability_frame").attr("src", url_to_load);
	
});	
$("#close_button").click(function () {
	//alert("foo");
	$("#availability_frame").hide();
	$("#close").hide();
});	

$('#project_people_click').click(function() {
	
	$('#project_people').toggle();
	});
	
	$( "#after_task_select" ).change(function() {
	  //alert( "Handler for .change() called." );
	  $('#task_loc_after_task').prop('checked',true);
		return false;
	});
	$( "#after_task_select_1" ).change(function() {
	  //alert( "Handler for .change() called again." );
	   $('#task_loc_after_task_1').prop('checked',true);
	});
  });

  </script>

<script language="javascript"> 
function openpopup(id){ 
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
}

function openpopup2(id,schedule_task_id,schedule_id, task_name, orig_display_order){ 
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
	  document.getElementById('tname').innerHTML=task_name;
	  document.getElementById('sid').value=schedule_id;
	   document.getElementById('orig_display_order').value=orig_display_order;
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
				<table width = "100%" border = "0">
					<tr>
						<td width = "50%" valign="top">
				<h1>Manage Tasks</h1>
				<h3>Schedule: <a href = "manage_schedules.php?p=<?php echo $project_id ?>"><?php echo $schedule_name  ?></a><br>Project: <a href = "manage_project.php?p=<?php echo $project_id ?>&show_schedules=1#schedules"><?php echo $project_name  ?> (<?php echo $project_code  ?>)</a></h3>
						</td>
						<td align = "right">
							<form action = "insert_project_people.php" method = "POST">
								<input type = "hidden" name = "schedule_id" value = "<?php echo $schedule_id?>">
								<input type = "hidden" name = "project_id" value = "<?php echo $project_id?>">
								<input type = "submit" value = "Add People to Schedule">
							</form>
							<?php echo $people_table ?>
						</td>
					</tr>
				</table>
				<div id="mydiv">
					<iframe id="availability_frame" src="" width="100%" height="400" border = "0" style="display: none">
					</iframe>
				</div>
				<table border="0" width = "90%">
					<tr>
						<td align="right">
							<div id = "close" style="display: none">
								<button id="close_button">close</button>
							</div>
						</td>
					</tr>
				</table>
				<div class = "error"><?php echo $error_message ?></div>
				<?php echo $task_table ?>
				<form id = "new_task_form" action = "add_schedule_task.php" method = "POST">
					Add Task:
					<table class = "form_table">
						<tr>
							<th>Task:</th>
							<th>Start/End</th>
							<th>Est. Time</th>
							<th>Pred</th>
							<th>Task Manager</th>
							<th>Progress (%)</th>
							<th>Assignee</th>
							<th>Task Location</th>
						</tr>
							
							<td valign="top"><?php echo $task_select  ?></td>
							<td valign="top" nowrap>s: <input type = "text" name = "start_date" class="datepicker" size = "8" id = "start_date"><br>
							e: <input type = "text" name = "end_date" class="datepicker" size = "8" id = "end_date"></td>
							<td nowrap valign="top" align="right">h:<input type = "text" name = "hours" size = "1"><br>m:<input type = "text" name = "minutes" size = "1"></td>
							<td valign="top"><input type = "text" name = "predecessor" class = "required number"  value = "0" size = "1"></td>
							<td valign="top"><?php echo $manager_select ?></td>
							<td valign="top"><?php echo $percentage_select ?></td>
							<td valign="top"><?php echo $assignee1_select ?></td>
							<td valign="top" nowrap>
							<input type = "radio" name = "task_location" value = "beginning">At the beginning<br>
							<input type = "radio" name = "task_location" value = "end" checked>At the end<br>
							<input type = "radio" name = "task_location" value = "after_task" id = "task_loc_after_task">After task <?php echo $task_num_select  ?></td>
							<td>
								<input type = "hidden" name = "assignee2" value = "">
								<input type = "hidden" name = "num_tasks" value = "<?php echo $num_tasks ?>">
								<input type = "hidden" name = "schedule_id" value = "<?php echo $schedule_id ?>">
								<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
								<input type = "submit" value = "Add Task">
							</td>
						</tr>	
					</table>
				
				</form>

				<form id = "add_tasks_from_template" action = "add_tasks_from_template.php" method = "POST">
					Add Tasks from Template:
					<table class = "form_table">
						<tr>
							<td valign="top"><b>Template:</b><br><?php echo $schedule_template_select ?></td>
							<td valign="top"><b>Start Date:</b><br><input type = "text" name = "start_date" class="datepicker" size = "8" value = "<?php echo $project_start_date ?>"></td>
							<td valign="top"><b>Add tasks:</b><br>
							<input type = "radio" name = "task_location" value = "beginning">At the beginning<br>
							<input type = "radio" name = "task_location" value = "end" checked>At the end<br>
							<input type = "radio" name = "task_location" value = "after_task" id = "task_loc_after_task_1">After task <?php echo $task_num_select1  ?></td>
							<td valign="top">
							<input type = "hidden" name = "schedule_id" value = "<?php echo $schedule_id ?>">
							<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
							<input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
							<input type = "submit" value = "Go">
							</td>
						</tr>
					</table>
				</form>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php echo $div_list?>
		<div id="bg" class="popup_bg"></div> 
		<?php 
		include "footer.php";
		?> 

	</div>

</div>

<div id="popup2" class="popup">
	<form id = "move_task" action = "move_schedule_task_by_number.php" method = "POST">
		<table border = "0" class = "budget">
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
					Move after task: 
				</td>
				<td nowrap>
					<?php echo $task_num_select_with_task_name  ?>
				</td>
			</tr>
			<tr>
				<td colspan = "2">
					<input id = "stid" type = "hidden" name = "schedule_task_id" value = "">
					<input id = "sid" type = "hidden" name = "schedule_id" value = "">
					<input id = "orig_display_order" type = "hidden" name = "orig_display_order" value = "">
					<input type = "submit" value = "move task">
				</td>
			</tr>
		</table>
	</form>

</div>

</body>
</html>