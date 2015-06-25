<?php
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";


function get_all_assets(){
    $link=dbConn();
    $handle=$link->prepare("select project_id,project_requester,project_code,project_name,start_date from project where project_id
                            in (select project_id from asset where asset_type_id in (13,54)) and active=1");
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}

$prod_status_table = "<table class = \"stats_table\" width = \"100%\"><tr><th>Project id</th><th>Project Code</th><th>Project name</th><th>Project requester</th><th>Desired in market date</th><th>Link</th></tr>";
$projects=get_all_assets();

foreach ($projects as $project) {
    $arr_assets = get_asset_info($project["project_id"]);
    $prod_status_table .= "<tr>";
    $prod_status_table .= "<td>" . $project["project_id"] . "</td>";
    $prod_status_table .= "<td>" . $project["project_code"] . "</a></td>";
    $prod_status_table .= "<td>" . $project["project_name"] . "</a></td>";
    $prod_status_table .= "<td>" . $project["project_requester"] . "</td>";
    $prod_status_table .= "<td>" . $arr_assets[0]["asset_start_date"] . "</td>";
    $prod_status_table .= "<td><a href='manage_project.php?p=".$project['project_id']."'>Link</a></td>";
    $prod_status_table .= "</tr>";

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
                window.location.replace("asset_report.php?date="+this.value);
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
                <h1>Phoenix.edu Assets Report</h1>
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

