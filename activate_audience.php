<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$audience_id = $_GET["aid"];
$active = $_GET["a"];

if($active == 2){
	$active = 0;
}

$active_success = activate_audience($audience_id, $active);

if ($active_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: new_audience.php?e=" . $error;
header($location) ;
