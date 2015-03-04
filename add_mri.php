<?php
/**
 * Created by PhpStorm.
 * User: aklochko
 * Date: 3/3/15
 * Time: 2:39 PM
 */

$requesterName=$_POST["requester_name"];
$requesterMail=$_POST["requester_mail"];
$requesterPhone=$_POST["requester_phone"];
$requestType=$_POST["request_type"];
$reportType=$_POST["report_type"];
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
    $dueDate=$_POST["due_date"];
}
else $dueDate=null;
if (isset($_POST["lob"]) && ($_POST["lob"]!="")){
    $lob=$_POST["lob"];
}
else $lob=null;
if (isset($_POST["delivery_date"]) && ($_POST["delivery_date"]!="")){
    $deliveryDate=$_POST["delivery_date"];
}
else $dueDate=null;
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
}

    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO MRI_common (isBM,requester_name,requester_mail,requester_phone,request_type_id,report_type_id,state_id,title,
                                    due_date,codes,lob_id,pic_name,delivery_date,spec_claims,sources,info,request_description,spec_questions) VALUES (
                                    :isBm,:requesterName,:requesterMail,:requesterPhone,:requestType,:reportType,:state,:title,:dueDate,:codes,:lob,
                                    :PIC,:deliveryDate,:claims,:sources,:info,:research,:questions
                                    )");
    $stmt->bindParam(':isBm', $isBm);
    $stmt->bindParam(':requesterName', $requesterName);
    $stmt->bindParam(':requesterMail', $requesterMail);
    $stmt->bindParam(':requesterPhone', $requesterPhone);
    $stmt->bindParam(':requestType', $requestType);
    $stmt->bindParam(':reportType', $reportType);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':dueDate', $dueDate);
    $stmt->bindParam(':codes', $codes);
    $stmt->bindParam(':lob', $lob);
    $stmt->bindParam(':PIC', $PIC);
    $stmt->bindParam(':deliveryDate', $deliveryDate);
    $stmt->bindParam(':claims', $claims);
    $stmt->bindParam(':info', $info);
    $stmt->bindParam(':research', $research);
    $stmt->bindParam(':questions', $questions);
    $stmt->bindParam(':sources', $sources);
    try{
        $stmt->execute();
        $result=1;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        $result=0;
}
