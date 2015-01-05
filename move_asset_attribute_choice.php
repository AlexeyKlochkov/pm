<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$aset_attribute_id = $_GET["aaid"];
$swap1 = $_GET["s1"];
$swap2 = $_GET["s2"];

//first set the first one to zero to avoid constraing
$update1 = update_asset_attribute_choice_order($aset_attribute_id, $swap1, 0);

//then set the second one to the first one
$update2 = update_asset_attribute_choice_order($aset_attribute_id, $swap2, $swap1);
//then set the first one (which is now zero) to the second one
$update3 = update_asset_attribute_choice_order($aset_attribute_id, 0, $swap2);

$location = "Location: new_asset_attribute.php?aaid=" . $aset_attribute_id;

header($location) ;

?>