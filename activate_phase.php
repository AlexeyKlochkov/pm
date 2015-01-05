<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$phase_id = $_GET["p"];
$active = $_GET["a"];

if($active == 2){
	$active = 0;
}

$active_success = activate_phase($phase_id, $active);

if ($active_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: new_phase.php?e=" . $error;
header($location) ;
