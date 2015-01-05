<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$asset_item_id = $_GET["aiid"];
$project_id = $_GET["p"];
$asset_id = $_GET["a"];
$del_success = delete_asset_item($asset_item_id);

if ($del_success == 1){
	$error = 0;
}else{
	$error = 1;
}

$num_asset_items = get_asset_item_count($asset_id);
$update_quantity_success = update_asset_quantity($asset_id, $num_asset_items);

$location = "Location: manage_project.php?p=" . $project_id . "&showassets=1#asset_" . $asset_id;


header($location) ;

?>