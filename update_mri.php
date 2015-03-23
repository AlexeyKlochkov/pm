<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$requesterName=$_POST["requester_name"];
$requesterMail=$_POST["requester_mail"];
$requesterPhone=$_POST["requester_phone"];
if (isset($_POST["title"]) && ($_POST["title"]!="")){
    $title=$_POST["title"];
}
else $title=null;
if (isset($_POST["state"]) && ($_POST["state"]!="")){
    $state=$_POST["state"];
}
else $state=null;
if (isset($_POST["codes"]) && ($_POST["codes"]!="")){
    $codes=$_POST["codes"];
}
else $codes=null;
if (isset($_POST["due_date"]) && ($_POST["due_date"]!="")){
    $dueDate= date('Y-m-d', strtotime($_POST["due_date"]));
}
else $dueDate=null;
if (isset($_POST["lob"]) && ($_POST["lob"]!="")){
    $lob=$_POST["lob"];
}
else $lob=null;
if (isset($_POST["delivery_date"]) && ($_POST["delivery_date"]!="")){
    $deliveryDate= date('Y-m-d', strtotime($_POST["delivery_date"]));
}
else $deliveryDate=null;
if (isset($_POST["PIC"]) && ($_POST["PIC"]!="")){
    $PIC=$_POST["PIC"];
}
else $PIC=null;
if (isset($_POST["claims"]) && ($_POST["claims"]!="")){
    $claims=$_POST["claims"];
}
else $claims=null;
if (isset($_POST["sources"]) && ($_POST["sources"]!="")){
    $sources=$_POST["sources"];
}
else $sources=null;
if (isset($_POST["research"]) && ($_POST["research"]!="")){
    $research=$_POST["research"];
}
else $research=null;
if (isset($_POST["questions"]) && ($_POST["questions"]!="")){
    $questions=$_POST["questions"];
}
else $questions=null;
if (isset($_POST["info"]) && ($_POST["info"]!="")){
    $info=$_POST["info"];
}
else $info=null;
if (isset($_POST["status"]) && ($_POST["status"]!="")){
    $status=$_POST["status"];
}
else $status=null;

$mriId=$_POST["mri_id"];
$dbConnection = dbConn();
$stmt = $dbConnection->prepare("UPDATE MRI_common SET requester_name=:requesterName,requester_mail=:requesterMail,requester_phone=:requesterPhone,
                                    state_id=:state,title=:title,due_date=:dueDate,codes=:codes,lob_id=:lob,pic_name=:PIC,delivery_date=:deliveryDate,
                                    spec_claims=:claims,sources=:sources,info=:info,request_description=:research,spec_questions=:questions,status_id=:status
                                     WHERE id=:id");
$stmt->bindValue(':id', $mriId,PDO::PARAM_INT);
$stmt->bindValue(':requesterName', $requesterName,PDO::PARAM_STR);
$stmt->bindValue(':requesterMail', $requesterMail,PDO::PARAM_STR);
$stmt->bindValue(':requesterPhone', $requesterPhone,PDO::PARAM_STR);
$stmt->bindValue(':state', $state,PDO::PARAM_INT);
$stmt->bindValue(':title', $title,PDO::PARAM_STR);
$stmt->bindValue(':dueDate', $dueDate,PDO::PARAM_STR);
$stmt->bindValue(':codes', $codes,PDO::PARAM_STR);
$stmt->bindValue(':lob', $lob,PDO::PARAM_INT);
$stmt->bindValue(':PIC', $PIC,PDO::PARAM_STR);
$stmt->bindValue(':status', $status,PDO::PARAM_STR);
$stmt->bindValue(':deliveryDate', $deliveryDate,PDO::PARAM_STR);
$stmt->bindValue(':claims', $claims,PDO::PARAM_STR);
$stmt->bindValue(':info', $info,PDO::PARAM_STR);
$stmt->bindValue(':research', $research,PDO::PARAM_STR);
$stmt->bindValue(':questions', $questions,PDO::PARAM_STR);
$stmt->bindValue(':sources', $sources,PDO::PARAM_STR);
try{
    $stmt->execute();
    $result=true;
}catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    $result=false;
}
if ($result) {
    $location = "Location: edit_mri.php?e=1&id=" . $mriId ;
}
header ($location);