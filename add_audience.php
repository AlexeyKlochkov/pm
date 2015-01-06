<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$audience_name = $_POST["audience_name"];
$new_audience_id = insert_audience($company_id, $audience_name);

if ($new_audience_id <> 0){
	$location = "Location: new_audience.php?e=2";
}else{
	$location = "Location: new_audience.php?e=1";
}
header($location) ;
