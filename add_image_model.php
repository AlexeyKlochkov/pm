<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$image_id = $_POST["image_id"];
$model_id = $_POST["model_id"];

$new_image_model_id = insert_image_model($image_id, $model_id);
if ($new_image_model_id <> 0){
	$location = "Location: image.php?i=" . $image_id;
}else{
	$location = "Location: image.php?e=1&i=" . $image_id;
}
header($location) ;
