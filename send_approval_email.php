<?php
include "loggedin.php";
require_once "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

//$project_id = $_POST["project_id"];
$schedule_task_id = $_POST["schedule_task_id"];
$user_initials = $_POST["user_initials"];
$approval_project_file_id = $_POST["approval_project_file_id"];
$comment = $_POST["comment"];

$body_text = "Please click here to review/approve the task.";
print $user_initials;
if(!empty($comment)){
	$body_text = $comment;
}

if (!empty($_POST["pg"])){
	$page = $_POST["pg"];
}else{
	$page = "manage_project";
}

$day_count_tasks = "";
if (!empty($_POST["dct"])){
	$day_count_tasks = $_POST["dct"];
}

$day_count_approvals = "";
if (!empty($_POST["dca"])){
	$day_count_approvals = $_POST["dca"];
}

//if (!empty($approval_project_file_id)){
	//stick the approval file ID in schedule_task table
$insert_file_id_success = update_approval_file_id($schedule_task_id, $approval_project_file_id);
//}

$arr_user_info = get_user_info_by_initials($company_id, $user_initials);
$recipient_user_id = $arr_user_info[0]["user_id"];
$user_email = $arr_user_info[0]["email"];
$user_full_name = $arr_user_info[0]["first_name"] . " " . $arr_user_info[0]["last_name"];

$arr_project = get_project_info_by_schedule_task($schedule_task_id);
$project_id = $arr_project[0]["project_id"];
$project_name = $arr_project[0]["project_name"];
$project_code = $arr_project[0]["project_code"];
$project_manager_name = $arr_project[0]["pm_fname"] . " " . $arr_project[0]["pm_lname"];
$schedule_id = $arr_project[0]["schedule_id"];
$schedule_name = $arr_project[0]["schedule_name"];
$task_name = $arr_project[0]["task_name"];
$approved_by = $arr_project[0]["approved_by"];
$approval_date = $arr_project[0]["approval_date"];
$approver_name = $arr_project[0]["a_fname"] . " " . $arr_project[0]["a_lname"];



$altbody = "Hi " . $user_full_name . ",\n\n";
$altbody .= "Your approval is required for:\n";
$altbody .= "Project: " . $project_name . "(" . $project_code . ")\n";
$altbody .= "Project Manager: " . $project_manager_name . "\n";
$altbody .= "Task: " . $task_name . "\n";
$altbody .= $body_text . "\n";
$altbody .= " http://ac-00019162.apollogrp.edu/pm/task_approval.php?stid=" . $schedule_task_id;

$body = "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";
$body .= "Hi " . $user_full_name . ",<br><br>";
$body .= $body_text . ":<br>";
$body .= "<b>Project: " . $project_name . "(" . $project_code . ")<br>";
$body .= "Project Manager: " . $project_manager_name . "<br>";
$body .= "Task: " . $task_name . "</b><br><br>";
$body .= "Please <a href = \"http://ac-00019162.apollogrp.edu/pm/task_approval.php?stid=" . $schedule_task_id . "\">CLICK HERE</a> to review/approve the task.<br>";
$body .= "<br>Thanks you.</font>";

$to = $user_email;
//print $to;
$subject = "Approval needed for project " . $project_name . " (" . $project_code . ")";
$send_success = smtpmailer($to, $subject, $body, $altbody);
//print $body;
//update email send table
if ($send_success == 1){
	$str_send_success = "Success";
}else{
	$str_send_success = "Fail";
}
$sender_id = $user_id;
$update_success = insert_email_send("Approval", $project_id, $schedule_task_id, $recipient_user_id, $str_send_success, $sender_id);
//$update_success = 1;
//print $update_success;

if ($send_success <> 1){
	$location = "Location: " . $page . ".php?e=3&p=" . $project_id . "&dct=" . $day_count_tasks . "&dca=" . $day_count_approvals;
}else{
	$location = "Location: " . $page . ".php?e=2&p=" . $project_id . "&dct=" . $day_count_tasks . "&dca=" . $day_count_approvals;
}

header($location) ;
