<?php
/**
 * Created by PhpStorm.
 * User: aklochko
 * Date: 12/30/14
 * Time: 10:50 AM
 */
ini_set('display_errors',"off");
include_once "dbconn.php";
include_once "queries.php";

include "functions.php";


function get_all_projects($endDate){
    $link=dbConn();
    $handle=$link->prepare("SELECT project_id,start_date,end_date FROM project WHERE end_date>=:endDate");
    $handle->bindValue(":endDate",convert_datepicker_date($endDate),PDO::PARAM_STR);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function get_assets_count($project_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT COUNT(*) as count FROM asset as a WHERE a.project_id=:project_id");
    $handle->bindValue(":project_id",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
$arr_prod_status = array();
$arr_headers = array("Project id", "Project code", "Line of business", "Project name", "Project requester", "Start date", "End date","Project status name", "AOP activity type name", "Num assets");
array_push($arr_prod_status, $arr_headers);
if (isset($_GET["date"])){
    $projectEndDate=$_GET["date"];}
else $projectEndDate="08/31/2014";
$projects=get_all_projects($projectEndDate);
foreach ($projects as $project) {
    $asset_num = get_assets_count($project["project_id"]);
    $arr_project = get_project_info($project["project_id"]);
    if (!empty($arr_project)) {
            $arr_current_variables = array($arr_project[0]["project_id"], $arr_project[0]["project_code"], $arr_project[0]["business_unit_name"], $arr_project[0]["project_name"], $arr_project[0]["project_requester"],
                                           $project["start_date"], $project["end_date"], $arr_project[0]["project_status_name"], $arr_project[0]["aop_activity_type_name"], $asset_num[0]["count"]);
            array_push($arr_prod_status, $arr_current_variables);
    }
}
download_send_headers("Projects report" . date("Y-m-d") . ".csv");
echo array2csv2($arr_prod_status);
