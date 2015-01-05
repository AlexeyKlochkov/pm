<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

if(empty($_FILES)){
	//have to handle the max size issue here because the POST string disappears completely if the file is too big.
	$project_id = $_SESSION["project_id"];
	$location = "Location: manage_project.php?show_files=1&show" . $file_type. "=1&p=" . $project_id . "&fe=1#files";
	header($location) ;
	exit;
}

$project_id = $_POST["project_id"];
$file_notes = $_POST["file_notes"];
$file_type = $_POST["file_type"];
$asset_item_id = "";
if(!empty($_POST["asset_item_id"])){
	$asset_item_id = $_POST["asset_item_id"];
}

$file_network_folder = "";
if(!empty($_POST["file_network_folder"])){
	$file_network_folder = $_POST["file_network_folder"];
}
$project_code = get_project_code($project_id);
$filename = $_FILES["file"]["name"];
$max_file_size = 20000000;
$error = 0;

if(empty($filename)){
	$error = 5;
}

if ($_FILES['file']['size'] > $max_file_size){
	//file too big
	$error = 1;
}

if ($error == 0){
	$logo_directory = "project_files/" . $project_code . "/";
	if (!file_exists($logo_directory)) {
		//if (is_writable($_SERVER['DOCUMENT_ROOT'] . $logo_directory)){
		mkdir($logo_directory);
	//}else{
			//print "Folder " . $logo_directory . " is not writable.";
	//	$error = 2;
	}

}
if ($error == 0){	

	$insert_success = insert_project_file($project_id, $filename, $file_notes, $file_type, $asset_item_id, $file_network_folder);
	if ($insert_success == 1){
		move_uploaded_file($_FILES["file"]["tmp_name"], $logo_directory . $filename);
		if ($file_type[0] == "R"){
			$file_type = "CR";
		}
	}else{
		if($insert_success=="duplicate file"){
			$error = 3;
		}else{
			$error = 4;
		}
	}
	
}
$location = "Location: manage_project.php?show_files=1&show" . $file_type. "=1&p=" . $project_id . "&fe=" . $error . "#files";
header($location) ;
