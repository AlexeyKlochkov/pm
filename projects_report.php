<?php
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";
//print $company_id;

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
if (isset($_GET["date"])){
$projectEndDate=$_GET["date"];}
else $projectEndDate="08/31/2014";

$prod_status_table = "<table class = \"stats_table\" width = \"100%\"><tr><th>Project id</th><th>Project Code</th><th>Line of business</th><th>Project name</th><th>Project requester</th><th>Start date</th><th>End date</th><th>Project status</th><th>AOP activity</th><th>Number of assets</th></tr>";
$projects=get_all_projects($projectEndDate);
foreach ($projects as $project) {
    $asset_num = get_assets_count($project["project_id"]);
    $arr_project = get_project_info($project["project_id"]);
    if (!empty($arr_project)) {
            $arr_current_variables = array($arr_project[0]["project_id"], $arr_project[0]["project_code"], $arr_project[0]["business_unit_name"], $arr_project[0]["project_name"], $arr_project[0]["project_requester"], $project["start_date"], $project["end_date"], $arr_project[0]["project_status_name"], $arr_project[0]["aop_activity_type_name"], $asset_num[0]["count"]);
            $prod_status_table .= "<tr>";
            $prod_status_table .= "<td>" . $arr_project[0]["project_id"] . "</td>";
            $prod_status_table .= "<td>" . $arr_project[0]["project_code"] . "</a></td>";
            $prod_status_table .= "<td>" . $arr_project[0]["business_unit_name"] . "</td>";
            $prod_status_table .= "<td>" . $arr_project[0]["project_name"] . "</td>";
            $prod_status_table .= "<td>" . $arr_project[0]["project_requester"]. "</td>";
            $prod_status_table .= "<td>" . $project["start_date"] . "</td>";
            $prod_status_table .= "<td>" . $project["end_date"] . "</td>";
            $prod_status_table .= "<td>" . $arr_project[0]["project_status_name"] . "</td>";
            $prod_status_table .= "<td>" . $arr_project[0]["aop_activity_type_name"] . "</td>";
            $prod_status_table .= "<td>" . $asset_num[0]["count"] . "</td>";
            $prod_status_table .= "</tr>";
    }
}
$prod_status_table .= "</table>";
?>
<html>
<head>
    <link href='style.css' rel='stylesheet' type='text/css' />
    <title>Production Status Report</title>
    <link href='style.css' rel='stylesheet' type='text/css' />
    <link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />
    <title>Resource Report</title>
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $(".datepicker").datepicker();
            $("#date").change(function(){
                window.location.replace("projects_report.php?date="+this.value);
            });
        });
        </script>
            </head>
<body>
<div id = "page">
    <div id = "main">
        <div id = "logo">
            <img src = "logo.png">
        </div>

        <?php
        include "nav1.php";
        ?>
        <!--container div tag-->
        <div id="container">
            <div id="mainContent"> <!--mainContent div tag-->
                <h1>Projects Report</h1>
                <form action = 'functions/report.php?date=<?php echo $projectEndDate;?>' method="POST">
                    <input type = "submit" value = "export">
                </form>
                Start Date:<br><input type = "text" name = "date" id="date" class="datepicker" size = "14" value = "<?php echo $projectEndDate ?>"><br>
                <?php echo $prod_status_table ?>
            </div> <!--end mainContent div tag-->
        </div>
        <?php
        include "footer.php";
        ?>
    </div>
</div>
</body>
</html>