<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$wif_type_name = $_POST["wif_type_name"];
$wif_type_abbrev = $_POST["wif_type_abbrev"];
$wif_type_description = $_POST["wif_type_description"];
$display_order = $_POST["display_order"];
$is_web_request = $_POST["is_web_request"];
$asset_type_id = $_POST["asset_type_id"];

$new_wif_type_id = insert_wif_type($company_id, $wif_type_name, $wif_type_abbrev, $wif_type_description, $display_order, $is_web_request, $asset_type_id);

//print $audit_id;

if ($new_wif_type_id <> 0){
	$location = "Location: new_wif_type.php?e=2";
}else{
	$location = "Location: new_wif_type.php?e=1";
}

header($location) ;


?>