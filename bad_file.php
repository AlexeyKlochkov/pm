<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

if(empty($_FILES)){
    //have to handle the max size issue here because the POST string disappears completely if the file is too big.
    $project_id = $_SESSION["project_id"];
    $location = "Location: manage_project.php?show_files=1&show" . $file_type. "=1&p=" . $project_id . "&fe=1#files";
    header($location) ;
    exit;
}

$project_id = $_POST["project_id"];
$file_notes = $_POST["file_notes"];
$file_type = $_POST["file_type"];
$asset_item_id = "";
if(!empty($_POST["asset_item_id"])){
    $asset_item_id = $_POST["asset_item_id"];
}

$file_network_folder = "";
if(!empty($_POST["file_network_folder"])){
    $file_network_folder = $_POST["file_network_folder"];
}
$project_code = get_project_code($project_id);

$max_file_size = 20000000;
$error = 0;


if ($error == 0){
    $logo_directory = "project_files/" . $project_code . "/";
    if (!file_exists($logo_directory)) {
        mkdir($logo_directory);
    }
}
$i=0;

if ($error == 0){
    foreach ($_FILES['file']['name'] as $file) {
        $filename=$_FILES['file']['name'][$i];
        $res=move_uploaded_file($_FILES["file"]["tmp_name"][$i], $logo_directory . $filename);
        $i++;
    }
}
$location = "Location: manage_project.php?show_files=1&s&p=" . $project_id . "&fe=" . $error;
header($location) ;

