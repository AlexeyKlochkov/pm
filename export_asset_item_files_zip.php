<?php
include "loggedin.php";
include "functions/functions.php";

$zip_file_list = $_POST["zip_file_list"];
$fileNames = explode(",", $zip_file_list);
//$fileNames=array('arrow_down.png', 'arrow_up.png');
//print_r($fileNames);

$zip_file_name="project_files/Asset_Item_Export_" . date("Y-m-d") . ".zip";

$file_path=dirname(__FILE__).'/';

zipFilesDownload($fileNames,$zip_file_name);
?>