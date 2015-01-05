<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$role_name = $_POST["role_name"];
$role_abbrev = $_POST["role_abbrev"];
$new_role_id = insert_role($company_id, $role_name, $role_abbrev);
if ($new_role_id <> 0){
	$location = "Location: new_role.php?e=2";
}else{
	$location = "Location: new_role.php?e=1";
}

header($location) ;
