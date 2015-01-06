<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$created_by = $_POST["created_by"];
$user_group_name = $_POST["user_group_name"];

$new_user_group_id = insert_user_group($company_id, $user_group_name, $created_by);

if ($new_user_group_id <> 0){
	$location = "Location: new_user_group.php?e=2";
}else{
	$location = "Location: new_user_group.php?e=1";
}

header($location) ;
