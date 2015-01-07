<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$field_id = $_POST["field_id"];
$field_name = $_POST["field_name"];
$active = $_POST["active"];
$update_success = update_field($field_id, $field_name, $active);

if ($update_success <> 1){
	$location = "Location: edit_field.php?e=1&f=" . $field_id;
}else{
	$location = "Location: edit_field.php?e=2&f=" . $field_id;
}

header($location) ;
