<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_type_template_id = $_POST["asset_type_template_id"];
$garnish_type = $_POST["garnish_type"];

if (!empty($_POST["garnish_color"])){
	$garnish_color = $_POST["garnish_color"];
}else{
	$garnish_color = "";
}

if (!empty($_POST["garnish_text"])){
	$garnish_text = $_POST["garnish_text"];
}else{
	$garnish_text = "";
}

if (!empty($_POST["garnish_font_size"])){
	$garnish_font_size = $_POST["garnish_font_size"];
}else{
	$garnish_font_size = "";
}

if (!empty($_POST["garnish_height"])){
	$garnish_height = $_POST["garnish_height"];
}else{
	$garnish_height = "";
}

if (!empty($_POST["garnish_width"])){
	$garnish_width = $_POST["garnish_width"];
}else{
	$garnish_width = "";
}

$new_asset_type_template_garnish_id = insert_asset_type_template_garnish($asset_type_template_id, $garnish_type, $garnish_text, $garnish_color, $garnish_font_size, $garnish_height, $garnish_width, 0,0);

if ($new_asset_type_template_garnish_id <> 0){
	$location = "Location: edit_asset_type_template.php?asset_type_template_id=" . $asset_type_template_id ;
}else{
	$location = "Location: edit_asset_type_template.php?asset_type_template_id=" . $asset_type_template_id . "&e=1";
}
print $location . "<br>";
header($location);
