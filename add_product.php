<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$company_id = $_POST["company_id"];
$product_name = $_POST["product_name"];

$new_product_id = add_product($company_id, $product_name);

if ($new_product_id == 0){
	$location = "Location: manage_products.php?e=1";
}else{
	$location = "Location: manage_products.php?e=2";
}
header($location) ;
