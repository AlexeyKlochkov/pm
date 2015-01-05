<?php
include "loggedin.php";
include "functions/functions.php";
$data_array = $_POST["data_array"];
//print $data_array;
download_send_headers("data_export_" . date("Y-m-d") . ".csv");
echo array2csv($data_array);
die();

?>