<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$ascdesc = $_POST["ascdesc"];
$sortby = $_POST["sortby"];

$del_success = delete_pif_ranks($company_id);
//handle deletes
foreach($_POST as $key=>$value)
{
	$variable_name = $key;
	$variable_value = $value;
	$first_eight_characters = substr($variable_name, 0, 8);
	//print $first_three_characters . "<br>";
	if ($first_eight_characters == "pif_rank"){
		
		$arr_pif_id = explode("-", $variable_name);
		$pif_to_update = $arr_pif_id[1];
		//display order changes on multiple deletes so it's safest to grab it this way.
		//print $pif_to_update . "-" . $variable_value . "<br>";
		update_pif_rank($pif_to_update, $variable_value);
		
	}
	
}

//print $new_asset_id;
$update_success = 1;
if ($update_success <> 0){
	
	$location = "Location: pif_list.php?s=6&sb=" . $sortby . "&ascdesc=" . $ascdesc;
}else{
	$location = "Location: pif_list.php?s=6&sb=" . $sortby . "&ascdesc=" . $ascdesc;
}

header($location) ;


?>