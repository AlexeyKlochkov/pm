<?php
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";


function get_all_assets(){
    $link=dbConn();
    $handle=$link->prepare("SELECT pif_id,pif_code,created_date FROM pif WHERE created_date > '2015-07-01 00:00:00'");
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
$prod_status_table = "<table class = \"stats_table\" width = \"100%\"><tr><th>Pif id</th><th>Pif Code</th><th>Created date</th></tr>";
$projects=get_all_assets();

foreach ($projects as $project) {

    $prod_status_table .= "<tr>";
    $prod_status_table .= "<td>" . $project["pif_id"] . "</td>";
    $prod_status_table .= "<td>" . $project["pif_code"] . "</a></td>";
    $prod_status_table .= "<td>" . $project["created_date"] . "</a></td>";
    $prod_status_table .= "</tr>";

}
$prod_status_table .= "</table>";

function get_all_assets1(){
    $link=dbConn();
    $handle=$link->prepare("SELECT project_id,project_code,created_date FROM project WHERE created_date > '2015-07-01 00:00:00'");
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
$prod_status_table1 = "<table class = \"stats_table\" width = \"100%\"><tr><th>Pif id</th><th>Pif Code</th><th>Created date</th></tr>";
$projects1=get_all_assets1();

foreach ($projects1 as $project) {

    $prod_status_table1 .= "<tr>";
    $prod_status_table1 .= "<td>" . $project["project_id"] . "</td>";
    $prod_status_table1 .= "<td>" . $project["project_code"] . "</a></td>";
    $prod_status_table1 .= "<td>" . $project["created_date"] . "</a></td>";
    $prod_status_table1 .= "</tr>";

}
$prod_status_table1 .= "</table>";

function get_all_assets2(){
    $link=dbConn();
    $handle=$link->prepare("SELECT wif_id,wif_code,request_date FROM wif WHERE request_date > '2015-07-01 00:00:00'");
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
$prod_status_table2 = "<table class = \"stats_table\" width = \"100%\"><tr><th>Pif id</th><th>Pif Code</th><th>Created date</th></tr>";
$projects2=get_all_assets2();

foreach ($projects2 as $project) {

    $prod_status_table2 .= "<tr>";
    $prod_status_table2 .= "<td>" . $project["wif_id"] . "</td>";
    $prod_status_table2 .= "<td>" . $project["wif_code"] . "</a></td>";
    $prod_status_table2 .= "<td>" . $project["request_date"] . "</a></td>";
    $prod_status_table2 .= "</tr>";

}
$prod_status_table2 .= "</table>";
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
                <h1>Projects</h1>
                <?php echo $prod_status_table1 ?>
                <h1>PIFs</h1>
                <?php echo $prod_status_table ?>
                <h1>WIFs</h1>
                <?php echo $prod_status_table2 ?>
            </div> <!--end mainContent div tag-->
        </div>
        <?php
        include "footer.php";
        ?>
    </div>
</div>
</body>
</html>

