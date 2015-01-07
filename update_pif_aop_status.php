<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

foreach($_POST as $key=>$value)
{
	$variable_name = $key;
	$variable_value = $value;
	$arr_variable_name = explode("_", $variable_name);
	$pif_id = $arr_variable_name[1];
	$update_success = update_pif_aop_activity_id($pif_id, $variable_value);
}
$pif_approval_status_id = $_POST["pif_approval_status_id"];
$location = "Location: pif_assign_aop.php?e=1&s=" . $pif_approval_status_id;

header($location) ;
