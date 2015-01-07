<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$wif_type_id = $_POST["wtid"];
$wif_type_name = $_POST["wif_type_name"];
$wif_type_abbrev = $_POST["wif_type_abbrev"];
$wif_type_description = $_POST["wif_type_description"];
$display_order = $_POST["display_order"];
$is_web_request = $_POST["is_web_request"];
$asset_type_id = $_POST["asset_type_id"];
$update_success = update_wif_type($wif_type_id, $wif_type_name, $wif_type_abbrev, $wif_type_description, $display_order, $is_web_request, $asset_type_id);
if ($update_success <> 0){
	$location = "Location: new_wif_type.php";
}else{
	$location = "Location: new_wif_type.php?e=1";
}
header($location) ;
