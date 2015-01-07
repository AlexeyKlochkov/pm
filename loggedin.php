<?php
ini_set('display_errors','On');
session_start();
//$server_name = "http://ac-00019162.apollogrp.edu";
$incoming_url = $_SERVER['REQUEST_URI'];
$full_url = /*$server_name .*/ $incoming_url;
//print $full_url;
//username 
if (empty($_SESSION["user_id"])){
	$location = "Location: loggedout.php";
	$_SESSION["redirect_url"] = $full_url;
	header($location) ;
}else{
	$user_id = $_SESSION["user_id"];
	$company_id = $_SESSION["company_id"];
	$user_full_name = $_SESSION["user_full_name"];
	date_default_timezone_set('America/Los_Angeles');
	
}
