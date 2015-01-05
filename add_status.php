<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$project_status_name = $_POST["project_status_name"];

$max_display_order =get_max_status($company_id, 1);
$new_display_order = $max_display_order + 1;

$insert_success = insert_status($company_id, $project_status_name, $new_display_order);

//print $audit_id;

if ($insert_success <> 0){
	$location = "Location: new_status.php?e=2";
}else{
	$location = "Location: new_status.php?e=1";
}

header($location) ;


?>