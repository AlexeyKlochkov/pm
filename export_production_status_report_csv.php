<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/functions.php";
include "functions/queries.php";

$arr_prod_status = array();
$arr_headers = array("Project Code", "Project Name", "Product", "Status", "Project End Date", "IPM", "Notes");

array_push($arr_prod_status, $arr_headers);
$arr_prod_status_variables = get_production_status_report($company_id);

if (!empty($arr_prod_status_variables)){
	foreach ($arr_prod_status_variables as $prod_status_row){
			$project_id = $prod_status_row["project_id"];
			$project_name = $prod_status_row["project_name"];
			$project_code = $prod_status_row["project_code"];
			$product_name = $prod_status_row["product_name"];
			$pm_last_name = $prod_status_row["pm_last_name"];
			$end_date = $prod_status_row["end_date"];
			$project_status_name = $prod_status_row["project_status_name"];
		$arr_current_variables = array($project_code , $project_name, $product_name, $project_status_name, $end_date, $pm_last_name, "");
		array_push($arr_prod_status, $arr_current_variables);
	}
}
download_send_headers("Production_Status_Report" . date("Y-m-d") . ".csv");
echo array2csv2($arr_prod_status);
die();

?>