<?php
/**
 * Created by PhpStorm.
 * User: aklochko
 * Date: 3/25/15
 * Time: 2:11 PM
 */
echo "<pre>";
var_dump($_POST);
$release=Array();
$i=0;
foreach ($_POST["release"] as $key=>$value){
    $release[$i]["name"]=$value;
    $i++;
}
$i=0;
foreach ($_POST["date"] as $key=>$value){
    $release[$i]["date"]=$value;
    $i++;
}
if (isset($_POST["executive"]) && ($_POST["executive"]=='on') ){
 $executive=$_POST["executive_date"];
}
if (isset($_POST["dean"]) && ($_POST["dean"]=='on') ){
    $dean=$_POST["dean_date"];
}
if (isset($_POST["cabinet"]) && ($_POST["cabinet"]=='on') ){
    $cabinet=$_POST["cabinet_date"];
}
if (isset($_POST["svp"]) && ($_POST["svp"]=='on') ){
    $svp=$_POST["svp_date"];
}
$sme=Array();
$i=0;
foreach ($_POST["sme"] as $key=>$value){
    $sme[$i]["sme"]=$value;
    $i++;
}
$i=0;
foreach ($_POST["sme_date"] as $key=>$value){
    $sme[$i]["date"]=$value;
    $i++;
}
if (isset($_POST["sme_radio"]) && ($_POST["sme_radio"]=="1")){
 $isSME=true;
}
else $isSME=false;
if (isset($_POST["ald_radio"]) && ($_POST["ald_radio"]=="1")){
    $isALD=true;
}
else $isALD=false;
$name=$_POST["name"];
$finalDate=$_POST["final_date"];
