<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$phase_name = $_POST["phase_name"];

$new_phase_id = insert_phase($company_id, $phase_name);
if ($new_phase_id <> 0){
	$location = "Location: new_phase.php?e=2";
}else{
	$location = "Location: new_phase.php?e=1";
}

header($location) ;