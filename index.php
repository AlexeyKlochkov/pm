<?php
set_include_path("/Applications/XAMPP/xamppfiles/htdocs/streamline/app/");
require_once "loggedin.php";
require_once "functions/dbconn.php";
require_once "functions/queries.php";
require_once "functions/functions.php";
//general user stuff


$today = date("m/d/Y");

if ($_SESSION["user_level"] == 10){
	require_once "home_user.php";
}

if ($_SESSION["user_level"] == 20){
	require_once "home_user.php";
}

if ($_SESSION["user_level"] > 20){
	require_once "home_admin.php";
}

