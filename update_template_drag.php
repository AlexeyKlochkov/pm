<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

//$asset_id = $_POST["asset_id"];
$current_id = $_POST["id"];
$object_type = $_POST["object_type"];
$x_offset = $_POST["x_offset"];
$y_offset = $_POST["y_offset"];

if($object_type == "attaid"){
	$update_success = update_asset_type_template_attribute_position($current_id, $x_offset, $y_offset);
}
if($object_type == "attgid"){
	$update_success = update_asset_type_template_garnish_position($current_id, $x_offset, $y_offset);
}
