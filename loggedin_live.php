<?php
ini_set('display_errors','On');
session_start();
if (empty($_SESSION["user_id"])){
	require_once "functions/queries.php";
	require_once "functions/dbconn.php";
	$system_user_name = $_SERVER['PHP_AUTH_USER'];
	//print $system_user_name;
	$arr_user_info = get_user_info_by_system_user_name($system_user_name);
	if(empty($arr_user_info)){
		$location = "Location: loggedout.php";
		header($location) ;
	}else{
		$session_user_id = $arr_user_info[0]["user_id"];
		$session_user_full_name = $arr_user_info[0]["first_name"] . " " . $arr_user_info[0]["last_name"];
		$session_company_id =$arr_user_info[0]["company_id"];
		$_SESSION["user_id"] = $session_user_id;
		$_SESSION["company_id"] = $session_company_id;
		$_SESSION["user_full_name"] = $session_user_full_name;
		$_SESSION["user_level"] = $arr_user_info[0]["user_level"];
		$_SESSION["is_project_manager"] = $arr_user_info[0]["is_project_manager"];
	}
}
$user_id = $_SESSION["user_id"];
$company_id = $_SESSION["company_id"];
$user_full_name = $_SESSION["user_full_name"];


date_default_timezone_set('America/Los_Angeles');

//print_r($arr_user_info);
?>