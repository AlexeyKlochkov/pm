<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/functions.php";
include "functions/queries.php";
$campaign_id = $_POST["campaign_id"];
$business_unit_id = $_POST["business_unit_id"];
$campaign_quarter = "";
$campaign_year = $_POST["year"];
$active_flag = $_POST["active"];
//print $data_array;

$arr_campaigns = get_campaign_query($company_id, $campaign_id, $business_unit_id, $campaign_quarter, $campaign_year, $active_flag);
//print_r($arr_campaigns);
download_send_headers("data_export_" . date("Y-m-d") . ".csv");
echo array2csv($arr_campaigns);
die();
