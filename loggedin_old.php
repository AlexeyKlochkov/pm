<?php
session_start();
$company_id = 2;
$user_id = 2;

ini_set('display_errors','On');
date_default_timezone_set('America/Los_Angeles');

$_SESSION["user_id"] = 2;
$_SESSION["company_id"] = 2;
$_SESSION["user_full_name"] = "Jimbo Jones";
$_SESSION["is_admin"] =0;
$_SESSION["is_project_manager"] = 1;

?>