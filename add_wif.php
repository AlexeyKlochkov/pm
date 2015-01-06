<?php

include "functions/queries.php";
include "functions/functions.php";
include "wif_email_inc.php";
date_default_timezone_set('America/Los_Angeles');
ini_set('user_agent', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.16) Gecko/2009121601 Ubuntu/9.04 (jaunty) Firefox/3.0.16');

$wif_name = $_POST["wif_name"];
$requester_name = $_POST["requester_name"];
$requester_email = $_POST["requester_email"];
$wif_type_id = $_POST["wif_type_id"];
$desired_delivery_date = $_POST["desired_delivery_date"];
$wif_description = $_POST["wif_description"];

$company_id = 2;

$i=0;
$desired_delivery_date = convert_datepicker_date($desired_delivery_date);
$new_wif_id = insert_wif($wif_name, $company_id, $requester_name, $requester_email, $wif_type_id, $desired_delivery_date, $wif_description);
$wif_code = "WIF-" . get_wif_type_abbrev($wif_type_id) . "-" . $new_wif_id;
$update_success = update_wif_code($new_wif_id, $wif_code);
$i=0;
if(count($_FILES['filesToUpload']['name'])) {
	mkdir("wif_files/" . $wif_code, 0777);
	foreach ($_FILES['filesToUpload']['name'] as $file) {
		$img = "wif_files/" . $wif_code . "/" .$file;
		//print $img;
		move_uploaded_file($_FILES["filesToUpload"]["tmp_name"][$i], $img);
		$wif_file_id = insert_wif_file($new_wif_id, $file);
		$i++;
	}
}

$wif_html = get_wif_email($new_wif_id);

$send_success = smtpmailer($requester_email, 'Thank you for your WIF submission', $wif_html ,'');

if (!empty($_POST["emergency_wif"])){
	//send emergency WIF email
	$emergency_wif_user_id = get_admin_value("user_id_for_wif_emergency_emails");
	$emergency_email_address = get_user_email_address($emergency_wif_user_id);
	$emergency_wif_subject = "EMERGENCY WIF!! " . $wif_name . " (" . $wif_code  . ")";
	$send_emergency_success = smtpmailer($emergency_email_address, $emergency_wif_subject, $wif_html ,'');
}


if ($new_wif_id == 0){
	
	$location = "Location: wif_ty.php?e=1";
}else{
	$location = "Location: wif_ty.php?e=2&wc=" . $wif_code;
}

header($location) ;
