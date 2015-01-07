<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$product_id = $_POST["product_id"];
$product_name = $_POST["product_name"];
$active = $_POST["active"];
$update_success = update_product($product_id, $product_name, $active);
if ($update_success <> 0){
	$location = "Location: manage_products.php?e=3";
}else{
	$location = "Location: manage_products.php?e=4";
}
header($location) ;
