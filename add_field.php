<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$field_name = $_POST["field_name"];
$display_type = $_POST["display_type"];

$new_field_id = insert_field($company_id, $field_name, $display_type);

//print $audit_id;

if ($new_field_id <> 0){
	$location = "Location: edit_field.php?f=" . $new_field_id;
}else{
	$location = "Location: new_field.php?e=1";
}

header($location) ;