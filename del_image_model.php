<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$image_model_id = $_GET["imid"];
$image_id = $_GET["i"];

$del_success = delete_image_model($image_model_id);


if ($del_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$location = "Location: image.php?i=" . $image_id;


header($location) ;

?>