<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/functions.php";
include "functions/queries.php";

if (!empty($_GET["s"])){
	$pif_approval_status_id = $_GET["s"];
}else{
	$pif_approval_status_id = 1;
}

if (!empty($_GET["sb"])){
	$sortby = $_GET["sb"];
}else{
	$sortby = "p.pif_code";
}

if (!empty($_GET["ascdesc"])){
	$ascdesc = $_GET["ascdesc"];
}else{
	$ascdesc = "asc";
}
$arr_pif_export = array();
$arr_headers = array("Rank", "PIF Code", "PIF Project Name", "Version", "Created", "Submitted By", "Marketing Owner", "Status", "AOP Type", "Project");

array_push($arr_pif_export, $arr_headers);
$arr_pifs = get_pifs_with_sort($company_id, $pif_approval_status_id, $sortby, $ascdesc);

if (!empty($arr_pifs)){
	foreach ($arr_pifs as $pif_row){
		$pif_id = $pif_row["pif_id"];
		$pif_code = $pif_row["pif_code"];
		$pif_project_name = $pif_row["pif_project_name"];
		$status = $pif_row["pif_approval_status_name"];
		$version = $pif_row["version"];
		$created_date = $pif_row["created_date"];
		$project_code = "n/a";
		$orig_pif_id = $pif_row["orig_pif_id"];
		$pif_rank = $pif_row["pif_rank"];
		$submitted_by = $pif_row["requester_first_name"] . " " . $pif_row["requester_last_name"];
		$marketing_owner_last_name = $pif_row["marketing_owner_last_name"];
		$aop_activity_type_name = $pif_row["aop_activity_type_name"];
		$project_id = $pif_row["project_id"];
		$project_code = "";
		if(!empty($project_id)){
			$project_code = get_project_code($project_id);
		}
			
		$arr_current_variables = array($pif_rank, $pif_code, $pif_project_name, $version, $created_date, $submitted_by, $marketing_owner_last_name, $status, $aop_activity_type_name, $project_code );
		array_push($arr_pif_export, $arr_current_variables);
	}
}
download_send_headers("PIF_List_" . date("Y-m-d") . ".csv");
echo array2csv2($arr_pif_export);
die();





?>