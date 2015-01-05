<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$field_id = $_POST["field_id"];
$field_choice_name = $_POST["field_choice_name"];

$max_field_choice_display = get_max_field_choice_display($field_id);
//print_r($max_field_choice_display);
$current_max  = $max_field_choice_display [0]["max_display"];
if (empty($max_field_choice_display [0]["max_display"])){
	$display_order = 1;

}else{
	$display_order = $max_field_choice_display [0]["max_display"] + 1;
}

$add_field_choice_success = insert_field_choice($field_id, $field_choice_name, $display_order);

//print $audit_id;

if ($add_field_choice_success == 1){
	$location = "Location: edit_field.php?f=" . $field_id;
}else{
	$location = "Location: edit_field.php?e=1&f=" . $field_id;
}
