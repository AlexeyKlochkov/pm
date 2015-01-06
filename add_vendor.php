<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$vendor_name = $_POST["vendor_name"];
$new_vendor_id = insert_vendor($company_id, $vendor_name);

if ($new_vendor_id <> 0){
	$location = "Location: new_vendor.php?e=2";
}else{
	$location = "Location: new_vendor.php?e=1";
}

header($location) ;
