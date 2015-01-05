<?php 
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "pif_email_inc.php";

$pif_html = get_pif_email(71);

print $pif_html;
$send_success = smtpmailer('christina.carr@apollo.edu', 'PIF Received', $pif_html ,'');

?>