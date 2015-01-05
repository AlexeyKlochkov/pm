<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$status_id = $_GET["s"];
$active = $_GET["a"];
$display_order = $_GET["d"];
$old_active = 0;
if($active == 2){
	$active = 0;
	$old_active = 1;
}

$max_display_order =get_max_status($company_id, $active);
$new_display_order = $max_display_order + 1;

$active_success = activate_status($status_id, $active, $new_display_order);

$move_status_success = move_project_statuses($company_id, $display_order, $old_active);

if ($active_success == 1){
	$error = 2;
}else{
	$error = 1;
}

$location = "Location: new_status.php?e=" . $error;
header($location) ;
