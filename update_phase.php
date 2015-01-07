<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$phase_id = $_POST["phase_id"];
$phase_name = $_POST["phase_name"];
$active = $_POST["active"];
$update_success = update_phase($phase_id, $phase_name, $active);
if ($update_success <> 0){
	$location = "Location: edit_phase.php?e=2&p=" . $phase_id;
}else{
	$location = "Location: edit_phase.php?e=1&p=" . $phase_id;
}

header($location) ;
