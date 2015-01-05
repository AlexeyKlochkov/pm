<?php
include_once "dbconn.php";
include_once "queries.php";
function getBad(){

    $link=dbConn();
    $handle=$link->prepare("SELECT p.project_name,f.project_file_name,p.project_id from project as p, project_file as f WHERE p.project_id=f.project_id AND p.active=1");
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
$res=getBad();
$i=0;
echo "<h1>Bad files table</h1>";
echo "<table border=1><tr><th>Project ID</th><th>Project manager</th><th>Project name</th><th>File name</th></tr>";
foreach ($res as $project){
    $file_name=$project["project_file_name"];
    $arr_project = get_project_info($project["project_id"]);
    $project_code = $arr_project[0]["project_code"];
    $directory = "../project_files/" . $project_code . "/";
    $file_location = $directory. $file_name;
    if (!file_exists($file_location)) {
        echo "<tr><td>".$project["project_id"]."</td><td>".$arr_project[0]["first_name"]." ".$arr_project[0]["last_name"]."</td><td>".$project["project_name"]."</td><td>".$project["project_file_name"]."</td>
             <td><a href='../bad_files.php?project_id=".$project["project_id"]."'>View</a></td></tr>";
        $i++;}
}
//echo "Count:".$i;
?>
