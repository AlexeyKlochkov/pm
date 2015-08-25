<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/functions.php";
include "functions/queries.php";

$active = 1;
if (!empty($_POST["active"])){
    $active = $_POST["active"];
}

$project_id = "";
$selected_project_id = "";
if (!empty($_POST["project_id"])){
    $project_id = $_POST["project_id"];
    $selected_project_id = $project_id;
}

$campaign_id = "";
if (!empty($_POST["campaign_id"])){
    $campaign_id = $_POST["campaign_id"];
}

$product_id = "";
if (!empty($_POST["product_id"])){
    $product_id = $_POST["product_id"];
}

$audience_id = "";
if (!empty($_POST["audience_id"])){
    $audience_id = $_POST["audience_id"];
}

$project_status_id = "";
if (!empty($_POST["project_status_id"])){
    $project_status_id = $_POST["project_status_id"];
}

$project_manager_id = "";
if (!empty($_POST["project_manager_id"])){
    $project_manager_id = $_POST["project_manager_id"];
}

$arr_all_projects = array();
$arr_headers = array("#", "Project Code", "Project Name", "AOP Line of Business", "Product", "Status", "IPM","Brand manager");


array_push($arr_all_projects, $arr_headers);
$arr_projects = get_projects_query($company_id, $project_id, $campaign_id, $product_id, $audience_id, $project_status_id, $project_manager_id, $active);

if (!empty($arr_projects)){
    foreach ($arr_projects as $project_row){

        $project_id = $project_row["project_id"];
        $project_code = $project_row["project_code"];
        $project_name = $project_row["project_name"];
        $campaign_code = $project_row["campaign_code"];
        $product_name = $project_row["product_name"];
        $audience_name = $project_row["audience_name"];
        $project_status = $project_row["project_status_name"];
        if ($project_row["project_status_id"]==10) {
            $project_status.="(".date("m-d-Y",strtotime($project_row["modified_date"])).")";
        }
        $project_manager = $project_row["last_name"];
        $arr_project1 = get_project_info($project_id);
        if (!empty($arr_project1[0])){
            $business_unit_owner_first_name = $arr_project1[0]["business_unit_owner_first_name"];
            $business_unit_owner_last_name = $arr_project1[0]["business_unit_owner_last_name"];
            $brand_manager_name = $business_unit_owner_first_name . " " . $business_unit_owner_last_name;
        }
        else $brand_manager_name = "";
        $arr_current_variables = array($project_id , $project_code, $project_name, $campaign_code, $product_name, $project_status, $project_manager,$brand_manager_name );
        array_push($arr_all_projects, $arr_current_variables);
    }
}
download_send_headers("Project_Report_" . date("Y-m-d") . ".csv");
echo array2csv2($arr_all_projects);
die();
