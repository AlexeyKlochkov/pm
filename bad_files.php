<?php
include_once "functions/dbconn.php";
include_once "functions/queries.php";
function getBad($project_id){

    $link=dbConn();
    $handle=$link->prepare("SELECT p.project_name,f.project_file_name,p.project_id from project as p, project_file as f WHERE p.project_id=f.project_id AND p.active=1 AND p.project_id=:projectId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
$res=getBad($_GET["project_id"]);
echo "List of missing files in this project:<br>";
foreach ($res as $file){
$file_name=$file["project_file_name"];
$arr_project = get_project_info($file["project_id"]);
$project_code = $arr_project[0]["project_code"];
$directory = "project_files/" . $project_code . "/";
$file_location = $directory. $file_name;
if (!file_exists($file_location)) {
    echo $file_name."<br>";
}
}
echo "<h2>You can add multiple files!</h2>";
?>
<form method="post" action="bad_file.php" enctype="multipart/form-data">
    <input type="file" name="file[]" id="file" multiple>
    <input type="hidden" name="project_id" value="<?php echo$_GET["project_id"];?>">
    <input type="submit">
</form>


