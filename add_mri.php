<?php
/**
 * Created by PhpStorm.
 * User: aklochko
 * Date: 3/3/15
 * Time: 2:39 PM
 */
include_once "functions/dbconn.php";
$requesterName=$_POST["requester_name"];
$requesterMail=$_POST["requester_mail"];
$requesterPhone=$_POST["requester_phone"];
$requestType=$_POST["request_type"];
if (isset($_POST["report_type"]) && ($_POST["report_type"]!="")) {
    $reportType = $_POST["report_type"];
}
else $reportType=null;
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
if (isset($_POST["isBm"])){
    $isBm=$_POST["isBm"];
}else $isBm=0;
function getMriCode($requestTypeId){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT short_name from MRI_request_type WHERE id=:requestTypeId");
    $stmt->bindValue(':requestTypeId', $requestTypeId,PDO::PARAM_STR);
    $stmt->bindColumn("short_name",$shortName,PDO::PARAM_STR);
    try{
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_BOUND)) {
            $result=$shortName;
        } else
            $result=false;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        $result=false;
    }
    return $result;
}
function updateCode($id,$code){
    $link = dbConn();
    $handle = $link->prepare("UPDATE MRI_common set code=:code WHERE id=:id");
    $handle->bindValue(":code", $code, PDO::PARAM_STR);
    $handle->bindValue(":id", $id, PDO::PARAM_INT);
    try {
        $handle->execute();
        return true;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function sendMail($to){
    $subject='New MRI submission';
    $message = "Test mri";
    $headers =  'From:noreply@apollo.edu';
    mail($to,$subject,$message,$headers);
}
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO MRI_common (isBM,requester_name,requester_mail,requester_phone,request_type_id,report_type_id,state_id,title,
                                    due_date,codes,id,pic_name,delivery_date,spec_claims,sources,info,request_description,spec_questions,status_id) VALUES (
                                    :isBm,:requesterName,:requesterMail,:requesterPhone,:requestType,:reportType,:state,:title,:dueDate,:codes,:lob,
                                    :PIC,:deliveryDate,:claims,:sources,:info,:research,:questions,1
                                    )");
    $stmt->bindValue(':isBm', $isBm,PDO::PARAM_INT);
    $stmt->bindValue(':requesterName', $requesterName,PDO::PARAM_STR);
    $stmt->bindValue(':requesterMail', $requesterMail,PDO::PARAM_STR);
    $stmt->bindValue(':requesterPhone', $requesterPhone,PDO::PARAM_STR);
    $stmt->bindValue(':requestType', $requestType,PDO::PARAM_INT);
    $stmt->bindValue(':reportType', $reportType,PDO::PARAM_INT);
    $stmt->bindValue(':state', $state,PDO::PARAM_INT);
    $stmt->bindValue(':title', $title,PDO::PARAM_STR);
    $stmt->bindValue(':dueDate', $dueDate,PDO::PARAM_STR);
    $stmt->bindValue(':codes', $codes,PDO::PARAM_STR);
    $stmt->bindValue(':lob', $lob,PDO::PARAM_INT);
    $stmt->bindValue(':PIC', $PIC,PDO::PARAM_STR);
    $stmt->bindValue(':deliveryDate', $deliveryDate,PDO::PARAM_STR);
    $stmt->bindValue(':claims', $claims,PDO::PARAM_STR);
    $stmt->bindValue(':info', $info,PDO::PARAM_STR);
    $stmt->bindValue(':research', $research,PDO::PARAM_STR);
    $stmt->bindValue(':questions', $questions,PDO::PARAM_STR);
    $stmt->bindValue(':sources', $sources,PDO::PARAM_STR);
    try{
        $stmt->execute();
        $lastId = $dbConnection->lastInsertId('id');
        $result=$lastId;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        $result=false;
    }
if ($result) {
    $mriCode=getMriCode($requestType)."-".$result;
    updateCode($result,$mriCode);
    $location = "Location: mri_ty.php?e=2";
}
function smtpmailer($to, $subject, $body, $altbody) {
    require_once("phpmail/class.phpmailer.php");
    global $error;
    $mail = new PHPMailer();  // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
    //$mail->SMTPAuth = true;  // authentication enabled
    //$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = 'mailrelay.apollogrp.edu';
    $mail->Port = 25;
    $mail->SetFrom("mktgintake@apollogrp.edu", "Marketing Intake");
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = $altbody;
    $mail->AddAddress($to);
    $mail->IsHTML(true);
    if(!$mail->Send()) {
        $error = 'Mail error: '.$mail->ErrorInfo;
        //print $error;
        return 0;
    } else {
        $error = 'Message sent!';
        return 1;
    }
}
$subject="MRI submission";
$body="<h2>Thank you for your submission!</h2>";
$altbody="";
if ($isBm){
    $addr='http://'.$_SERVER["SERVER_NAME"].'/pm/manage_mri.php?id='.$lastId;
    $body.="<p>You can view/edit your requests here:<a href=$addr>Link</a></p>";
}
smtpmailer($requesterMail,$subject,$body,$altbody);

header ($location);
