<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$pif_id = $_POST["pif_id"];
$aop_activity_type_id = $_POST["aop_activity_type_id"];

$update_success = update_pif_aop_activity_id($pif_id, $aop_activity_type_id);


//if ($update_success <> 0){
//$location = "Location: pif_assign_aop.php?e=1&s=" . $pif_approval_status_id;
//}else{
//	$location = "Location: edit_user.php?e=2&u=" . $user_id;
//}
$location = "Location: pif_list.php";

header($location) ;

?>