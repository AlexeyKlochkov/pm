<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
$asset_attribute_id = $_GET["aaid"];
$active = $_GET["a"];
$activate_success = activate_asset_attribute($asset_attribute_id,$active);
if ($activate_success == 1){
    $error = 0;
}else{
    $error = 1;
}
$location = "Location: new_asset_attribute.php";
header($location);