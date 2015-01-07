<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$pif_id = $_POST["pif_id"];
$aop_activity_type_id = $_POST["aop_activity_type_id"];
$update_success = update_pif_aop_activity_id($pif_id, $aop_activity_type_id);
$location = "Location: pif_list.php";

header($location) ;
