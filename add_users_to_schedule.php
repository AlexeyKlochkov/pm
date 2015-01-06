<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$schedule_task_id = $_POST["schedule_task_id"];
$schedule_id = $_POST["schedule_id"];
$orig_initials = $_POST["orig_initials"];
$project_id = $_POST["project_id"];
$employee_ids_in_view = $_POST["employee_ids_in_view"];
$arr_employee_ids_in_view = explode(",", $employee_ids_in_view);
$arr_current_initials = explode(", ", $orig_initials);
$arr_current_assigned_employee_ids = array();
$is_approval = 0;
$arr_current_employees_assigned = get_assignees_by_stid($schedule_task_id);
if (!empty($arr_current_employees_assigned)){
	foreach ($arr_current_employees_assigned as $current_employee){
		$employee_user_id = $current_employee["user_id"];
		array_push($arr_current_assigned_employee_ids, $employee_user_id);
	}
}

$arr_project_user_ids = array();
$arr_project_users = get_users_by_project($project_id);
if (!empty($arr_project_users)){
	foreach ($arr_project_users as $current_project_user){
		$project_user_id = $current_project_user["user_id"];
		array_push($arr_project_user_ids, $project_user_id);
	}
}

//print_r($arr_project_user_ids);
//Need to figure out how to delete anyone assigned to the task who is seen by the user but not checked (was initially checked)
//Orig Initials has people that are initially checked
//also need to add people to the project if they are not there already.

$arr_added_users = array();
foreach ($_POST as $key => $value){
    //echo "$key => $value";
	$arr_key = explode("-", $key);
	//print_r($arr_key);
	//isolate the users that are being added
	if ($arr_key[0] == "user_id"){
		
		if ($arr_key[1] == "R"){
			//handle radio button
			$is_approval = 1;
			$current_employee_id = $value;
		}else{
			//handle checkboxes
			$current_employee_id = $arr_key[1];
		}
		//print $current_employee_id;
		array_push($arr_added_users, $current_employee_id);
	}
} 

//if this IS an approval task, delete all users
if($is_approval == 1){
	//delete all users for this STID
	$delete_success = delete_schedule_task_assignees($schedule_task_id);
}

//if this is not an approval task:
//so now you have two arrays: 
//arr_current_assigned_employee_ids has people that are already assigned to the task.
//$arr_added_users has people that are are being added.
//so if people are being added, but they are already in the list, don't add them.
if (!empty($arr_added_users)){
	foreach ($arr_added_users as $added_user_id){
		
		if(!in_array($added_user_id, $arr_current_assigned_employee_ids)){
			//print "<br><br>adding user " . $added_user_id;
			$insert_success = insert_schedule_task_assignee($schedule_task_id, $added_user_id);
			//if this person is not in the project, add them.
			if(!in_array($added_user_id, $arr_project_user_ids)){
				$add_success = add_project_person($project_id, $added_user_id);
			}
			
			
		}
	}
}

//if people are already in the list, but are not in the added_users list, delete them.
//if people are already assigned to this task, and they are part of the group that's currently being looked at (list of checkboxes), and they are not checked, they need to be deleted.
//print_r ($arr_current_assigned_employee_ids);
if (!empty($arr_current_assigned_employee_ids)){
	foreach ($arr_current_assigned_employee_ids as $orig_user_id){
		if(!in_array($orig_user_id, $arr_added_users)){
			if(in_array($orig_user_id, $arr_employee_ids_in_view)){
			//print "<br><br>deleting user " . $orig_user_id;
			$delete_success = delete_one_schedule_task_assignee($schedule_task_id, $orig_user_id);
			
			}
		}
	}
}

?>

<html>
    <head>
        <script type="text/javascript">
        
            window.onload = function()
            {
                // Reload the parent window
                self.parent.location.reload(true);
            }
            
        </script>
    </head>
	Updating...
</html>
