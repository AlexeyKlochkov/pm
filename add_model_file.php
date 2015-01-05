<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

print_r($_POST);
$model_id = $_POST["model_id"];
print $model_id ;
$error = 0;
if(empty($_FILES)){
	$error = 1;
}
$max_file_size = 1000000;

if ($_FILES['file']['size'] > $max_file_size){
	//file too big
	$error = 2;
	//print "e=1 - too big";
}

if ($error == 0){	
	$model_directory = "images/models/";
	//move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
	$file_name = "m" . $model_id . ".jpg";
	$move_success = move_uploaded_file($_FILES["file"]["tmp_name"], $model_directory . $file_name);
}
$location = "Location: model.php?m=" . $model_id . "&e=" . $error;
header($location) ;
