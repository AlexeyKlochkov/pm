<?php
/**
 * Created by PhpStorm.
 * User: aklochko
 * Date: 3/25/15
 * Time: 2:11 PM
 */
include "functions/dbconn.php";
include "functions/queries.php";
session_start();
$error=0;
$release=Array();
$i=0;
foreach ($_POST["release"] as $key=>$value){
    $release[$i]["name"]=$value;
    $i++;
}
$i=0;
foreach ($_POST["date"] as $key=>$value){
    $release[$i]["date"]=date('Y-m-d', strtotime($value));
    $i++;
}
if (isset($_POST["executive"]) && ($_POST["executive"]=='on') ){
 $executive=date('Y-m-d', strtotime($_POST["executive_date"]));
}
else $executive=null;
if (isset($_POST["dean"]) && ($_POST["dean"]=='on') ){
    $dean=date('Y-m-d', strtotime($_POST["dean_date"]));
} else $dean=null;
if (isset($_POST["cabinet"]) && ($_POST["cabinet"]=='on') ){
    $cabinet=date('Y-m-d', strtotime($_POST["cabinet_date"]));
}else $cabinet=null;
if (isset($_POST["svp"]) && ($_POST["svp"]=='on') ){
    $svp=date('Y-m-d', strtotime($_POST["svp_date"]));
}else $svp=null;
$sme=Array();
$i=0;
foreach ($_POST["sme"] as $key=>$value){
    $sme[$i]["name"]=$value;
    $i++;
}
$i=0;
foreach ($_POST["sme_date"] as $key=>$value){
    $sme[$i]["date"]=date('Y-m-d', strtotime($value));
    $i++;
}
if (isset($_POST["sme_radio"]) && ($_POST["sme_radio"]=="1")){
 $isSME=1;
}
else $isSME=0;
if (isset($_POST["ald_radio"]) && ($_POST["ald_radio"]=="1")){
    $isALD=1;
}
else $isALD=0;
$name=$_POST["name"];
$finalDate=date('Y-m-d', strtotime($_POST["final_date"]));
$project_id=$_POST["project_id"];
$data = array();


function addDPCFile($name,$dpcId){
    $link=dbConn();
    $handle=$link->prepare("INSERT INTO dpc_file (name,dpc_id) VALUES (:name,:dpc_id)
                            ON DUPLICATE KEY UPDATE name=:name1");
    $handle->bindValue(":dpc_id",$dpcId,PDO::PARAM_INT);
    $handle->bindValue(":name",$name,PDO::PARAM_STR);
    $handle->bindValue(":name1",$name,PDO::PARAM_STR);
    try{
        $handle->execute();
        $fileId = $link->lastInsertId();
        return ($fileId);
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function addDPC($projectId,$name,$date,$is_sme,$is_ald){
    $link=dbConn();
    $handle=$link->prepare("INSERT INTO dpc_common (project_id,name,date,is_sme,is_ald) VALUES (:project_id,:name,:date,:is_sme,:is_ald)
                            ON DUPLICATE KEY UPDATE name=:name1,date=:date1,is_sme=:is_sme1,is_ald=:is_ald1");
    $handle->bindValue(":project_id",$projectId,PDO::PARAM_INT);
    $handle->bindValue(":name",$name,PDO::PARAM_STR);
    $handle->bindValue(":date",$date,PDO::PARAM_STR);
    $handle->bindValue(":is_sme",$is_sme,PDO::PARAM_INT);
    $handle->bindValue(":is_ald",$is_ald,PDO::PARAM_INT);
    $handle->bindValue(":name1",$name,PDO::PARAM_STR);
    $handle->bindValue(":date1",$date,PDO::PARAM_STR);
    $handle->bindValue(":is_sme1",$is_sme,PDO::PARAM_INT);
    $handle->bindValue(":is_ald1",$is_ald,PDO::PARAM_INT);
    try{
        $handle->execute();
        $dpcId = $link->lastInsertId();
        return ($dpcId);
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function addRelease($id,$dpcId,$text,$date){
    $link=dbConn();
    $handle=$link->prepare("INSERT INTO dpc_releases (id,dpc_id,text,date) VALUES (:id,:dpcId,:text,:date)
                            ON DUPLICATE KEY UPDATE date=:date1,text=:text1");
    $handle->bindValue(":id",$id,PDO::PARAM_INT);
    $handle->bindValue(":date",$date,PDO::PARAM_STR);
    $handle->bindValue(":dpcId",$dpcId,PDO::PARAM_INT);
    $handle->bindValue(":text",$text,PDO::PARAM_STR);
    $handle->bindValue(":date1",$date,PDO::PARAM_STR);
    $handle->bindValue(":text1",$text,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function addSME($id,$dpcId,$text,$date){
    $link=dbConn();
    $handle=$link->prepare("INSERT INTO dpc_sme (id,dpc_id,text,date) VALUES (:id,:dpcId,:text,:date)
                            ON DUPLICATE KEY UPDATE date=:date1,text=:text1");
    $handle->bindValue(":id",$id,PDO::PARAM_INT);
    $handle->bindValue(":date",$date,PDO::PARAM_STR);
    $handle->bindValue(":dpcId",$dpcId,PDO::PARAM_INT);
    $handle->bindValue(":text",$text,PDO::PARAM_STR);
    $handle->bindValue(":date1",$date,PDO::PARAM_STR);
    $handle->bindValue(":text1",$text,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function getDPCId($projectId){
    $link=dbConn();
    $handle=$link->prepare("SELECT id FROM dpc_common WHERE project_id=:projectId");
    $handle->bindValue(":projectId",$projectId,PDO::PARAM_INT);
    $handle->bindColumn("id",$id,PDO::PARAM_INT);
    try{
        $handle->execute();
        if ($handle->fetch(PDO::FETCH_BOUND)) {
            return ($id);
        }
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }

}

function addApprovals($dpc_id,$executive,$dean,$cabinet,$svp){
    $link=dbConn();
    $handle=$link->prepare("INSERT INTO dpc_approvals (dpc_id,executive,dean,cabinet,svp) VALUES (:dpc_id,:executive,:dean,:cabinet,:svp)
                            ON DUPLICATE KEY UPDATE executive=:executive1,dean=:dean1,cabinet=:cabinet1,svp=:svp1");
    $handle->bindValue(":dpc_id",$dpc_id,PDO::PARAM_INT);
    $handle->bindValue(":executive",$executive,PDO::PARAM_STR);
    $handle->bindValue(":dean",$dean,PDO::PARAM_STR);
    $handle->bindValue(":cabinet",$cabinet,PDO::PARAM_STR);
    $handle->bindValue(":svp",$svp,PDO::PARAM_STR);
    $handle->bindValue(":executive1",$executive,PDO::PARAM_STR);
    $handle->bindValue(":dean1",$dean,PDO::PARAM_STR);
    $handle->bindValue(":cabinet1",$cabinet,PDO::PARAM_STR);
    $handle->bindValue(":svp1",$svp,PDO::PARAM_STR);
    try{
        $handle->execute();
        $dpcId = $link->lastInsertId();
        return ($dpcId);
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}

addDPC($project_id,$name,$finalDate,$isSME,$isALD);
$dpcId=getDPCId($project_id);
$error = false;
    $files = array();
    $uploaddir = 'dpc_files/'.$project_id."/";
if (!file_exists($uploaddir)) {
    mkdir($uploaddir);
}
    foreach($_FILES as $file)
    {
        if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
        {
            $files[] = $uploaddir .$file['name'];
            addDPCFile($file['name'],$dpcId);
        }
        else
        {
            $error = true;
        }
    }
foreach ($release as $key=>$value){
    addRelease(($dpcId*10+$key),$dpcId,$value["name"],$value["date"]);
}
foreach ($sme as $key=>$value){
    addSME(($dpcId*10+$key),$dpcId,$value["name"],$value["date"]);
}
addApprovals($dpcId,$executive,$dean,$cabinet,$svp);
$location="Location: manage_project.php?p=".$project_id;
//header($location);