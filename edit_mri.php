<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
//print $company_id;
$error_message = "";
function getRequestTypes($selected){
    $link=dbConn();

    $handle=$link->prepare("SELECT * FROM MRI_request_type WHERE id=:id");
    $handle->bindValue(":id",$selected,PDO::PARAM_INT);
    $handle->bindColumn("short_name",$short,PDO::PARAM_STR);
    $handle->bindColumn("name",$name,PDO::PARAM_STR);
    try{
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            $result = $name;
        }
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function getReportTypes($selected){
    $link=dbConn();
    $handle=$link->prepare("SELECT * FROM MRI_report_type WHERE id=:id");
    $handle->bindValue(":id",$selected,PDO::PARAM_INT);
    $handle->bindColumn("short_name",$short,PDO::PARAM_STR);
    $handle->bindColumn("name",$name,PDO::PARAM_STR);
    try{
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            $result=$name;
        }
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}

function getStates($selected){
    $link = dbConn();
    $result="<select class = \"required\" name = \"report_type\"><option value = \"\" disabled>Please select</option>";
    $handle = $link->prepare("select * from state order by state_name asc");
    $handle->bindColumn("state_id",$id,PDO::PARAM_INT);
    $handle->bindColumn("state_name",$name,PDO::PARAM_STR);
    $handle->bindColumn("state_abbrev",$abbrev,PDO::PARAM_STR);
    try {
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            if ($id==$selected){
                $result.="<option id='$id' title='$name' label='$name' value='$id' selected>".$name."</option>";
            }
            else {
                $result.="<option id='$id' title='$name' label='$name' value='$id'>".$name."</option>";
            }
        }
        $result.="</select>";
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function getSchools($selected){
    $link = dbConn();
    $result="<select class = \"required\" name = \"lob\"><option value = \"\" disabled>Please select</option>";
    $handle = $link->prepare("select * from business_unit where active=1 order by business_unit_name asc");
    $handle->bindColumn("business_unit_id",$id,PDO::PARAM_INT);
    $handle->bindColumn("business_unit_name",$name,PDO::PARAM_STR);
    $handle->bindColumn("business_unit_abbrev",$abbrev,PDO::PARAM_STR);
    try {
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            if ($id==$selected){
                $result.="<option id='$id' title='$name' label='$name' value='$id' selected>".$name."</option>";
            }
            else {
                $result.="<option id='$id' title='$name' label='$name' value='$id'>".$name."</option>";
            }
        }
        $result.="</select>";
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function getStatuses($selected){
    $dbConnection = dbConn();
    $result="<select class = \"required\" name = \"status\"><option value = \"\" disabled>Please select</option>";
    $stmt = $dbConnection->prepare("SELECT * FROM MRI_statuses ");
    $stmt->bindColumn('id', $id,PDO::PARAM_INT);
    $stmt->bindColumn('name', $name,PDO::PARAM_STR);
    try {
        $stmt->execute();
        while ($stmt->fetch(PDO::FETCH_BOUND)) {

            if ($id==$selected){
                $result.="<option id='$id' title='$name' label='$name' value='$id' selected>".$name."</option>";
            }
            else {
                $result.="<option id='$id' title='$name' label='$name' value='$id'>".$name."</option>";
            }
        }
        $result.="</select>";
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
if (!empty($_GET["e"])){
    $error_num = $_GET["e"];
    if ($error_num == 1){
        $error_message = "MRI updated.";
    }
    if ($error_num == 2){
        $error_message = "Update error.";
    }
}
if (!empty($_GET["id"])){
    $project_id = $_GET["id"];
}else{
    $project_id = 0;
}
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT * FROM MRI_common WHERE id=:id");
    $stmt->bindValue('id', $project_id,PDO::PARAM_INT);
    $stmt->bindColumn('isBM', $isBm);
    $stmt->bindColumn('status_id', $statusId);
    $stmt->bindColumn('code', $code);
    $stmt->bindColumn('requester_name', $requesterName);
    $stmt->bindColumn('requester_mail', $requesterMail);
    $stmt->bindColumn('requester_phone', $requesterPhone);
    $stmt->bindColumn('request_type_id', $requestTypeId);
    $stmt->bindColumn('report_type_id', $reportTypeId);
    $stmt->bindColumn('state_id', $stateId);
    $stmt->bindColumn('title', $title);
    $stmt->bindColumn('due_date', $dueDate);
    $stmt->bindColumn('codes', $codes);
    $stmt->bindColumn('lob_id', $lobId);
    $stmt->bindColumn('pic_name', $PIC);
    $stmt->bindColumn('delivery_date', $deliveryDate);
    $stmt->bindColumn('spec_claims', $claims);
    $stmt->bindColumn('info', $info);
    $stmt->bindColumn('request_description', $description);
    $stmt->bindColumn('spec_questions', $questions);
    $stmt->bindColumn('sources', $sources);
    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_BOUND);

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        $result = false;
    }
if (!is_null($reportTypeId)) {
    $reportType=getReportTypes($reportTypeId);
}
if (!is_null($stateId)) {
    $states=getStates($stateId);
}
if (!is_null($lobId)) {
    $lobs=getSchools($lobId);
}
if (!is_null($dueDate)) {
    $due_date = translate_mysql_todatepicker($dueDate);
}
if (!is_null($deliveryDate)) {
    $delivery_date = translate_mysql_todatepicker($deliveryDate);
}
$requestType=getRequestTypes($requestTypeId);
$statuses=getStatuses($statusId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Marketing Research Intake</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='style.css' rel='stylesheet' type='text/css' />
    <link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script src="js/trumbowyg/dist/trumbowyg.min.js"></script>

    <link rel="stylesheet" href="js/trumbowyg/dist/ui/trumbowyg.min.css">
</head>
    <script>
        $(document).ready(function(){
            $("[id='wysiwyg']").trumbowyg({
            });
            $("#project_form").validate();
            $( ".datepicker" ).datepicker();
        });

    </script>
<body style="background-color: #EFEFEF">
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

            <div id="mainContent"  width = "40%"> <!--mainContent div tag-->
                <h1>Edit <a href = "manage_mri.php?id=<?php echo $project_id  ?>">Project <?php echo $code ?></a></h1>

                <div class = "error"><?php echo $error_message ?></div>
                <form id = "project_form" action = "update_mri.php" method = "POST">
                    <table class = "table table-hover">
                        <tr>
                            <td>Request type:</td>
                            <td><input style="width:40%;" type = "text" value = "<?php echo $requestType ?>" disabled></td>
                        </tr>
                        <?php if (isset($lobs)):?>
                        <tr>
                            <td>Line of Business:</td>
                            <td><?php echo $lobs ?></td>
                        </tr>
                        <?php endif;?>
                        <?php if (isset($reportType)):?>
                        <tr>
                            <td>Report type:</td>
                            <td><input style="width:40%;" type = "text" value = "<?php echo $reportType ?>" disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if (!is_null($title)):?>
                        <tr>
                            <td>Title:</td>
                            <td><input style="width:40%;" type = "text" name = "title" value = "<?php echo $title ?>"></td>
                        </tr>
                        <?php endif;?>
                        <tr>
                            <td>Status</td>
                            <td><?php echo $statuses ?></td>
                        </tr>
                        <?php if (!is_null($PIC)):?>
                        <tr>
                            <td>Project/Campaign name:</td>
                            <td><input style="width:40%;" type = "text" name = "PIC" value = "<?php echo $PIC ?>"></td>
                        </tr>
                        <?php endif;?>
                        <?php if (isset($delivery_date)):?>
                        <tr>
                            <td>Delivery Date</td>
                            <td><input type = "text" name = "delivery_date" class="required datepicker" value = "<?php echo $delivery_date ?>"></td>
                        </tr>
                        <?php endif;?>
                        <tr>
                            <td>MRI Requester Name:</td>
                            <td><input style="width:40%;" type = "text" name = "requester_name" value = "<?php echo $requesterName ?>"></td>
                        </tr>
                        <tr>
                            <td>MRI Requester Email:</td>
                            <td><input style="width:40%;" type = "text" name = "requester_mail" value = "<?php echo $requesterMail ?>"></td>
                        </tr>
                        <tr>
                            <td>MRI Requester Phone:</td>
                            <td><input style="width:40%;" type = "text" name = "requester_phone" value = "<?php echo $requesterPhone ?>"></td>
                        </tr>
                        <?php if (isset($due_date)):?>
                        <tr>
                            <td>Desired Due Date</td>
                            <td><input type = "text" name = "due_date" class="required datepicker" value = "<?php echo $due_date ?>"></td>
                        </tr>
                        <?endif;?>
                        <?php if (isset($states)):?>
                            <tr>
                                <td>State:</td>
                                <td><?php echo $states ?></td>
                            </tr>
                        <?php endif;?>
                        <?php if (!is_null($codes)):?>
                        <tr>
                            <td>CIP/SOC:</td>
                            <td><textarea  rows="8" cols="48" name = "codes"><?php echo $codes ?></textarea></td>
                        </tr>
                        <?php endif;?>

                        <?php if (!is_null($claims)):?>
                            <tr>
                                <td>Special claims:</td>
                                <td><textarea id="wysiwyg" name="claims" rows="8" cols="48"><?php echo $claims ?></textarea></td>
                            </tr>
                        <?php endif;?>
                        <?php if (!is_null($sources)):?>
                        <tr>
                            <td>Sources, if available:</td>
                            <td><textarea id="wysiwyg" name="sources" rows="8" cols="48"><?php echo $sources ?></textarea></td>
                        </tr>
                        <?php endif;?>
                        <?php if (!is_null($info)):?>
                            <tr>
                                <td>Additional information:</td>
                                <td><textarea id="wysiwyg" name="info" rows="8" cols="48"><?php echo $info ?></textarea></td>
                            </tr>
                        <?php endif;?>
                        <?php if (!is_null($description)):?>
                            <tr>
                                <td>Research request description:</td>
                                <td><textarea id="wysiwyg" name="research" rows="8" cols="48"><?php echo $description ?></textarea></td>
                            </tr>
                        <?php endif;?>
                        <?php if (!is_null($questions)):?>
                            <tr>
                                <td>Specific questions:</td>
                                <td><textarea id="wysiwyg" name="questions" rows="8" cols="48"><?php echo $questions ?></textarea></td>
                            </tr>
                        <?php endif;?>

                        <tr>
                            <td>
                                <input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
                                <input type = "hidden" name = "mri_id" value = "<?php echo $project_id ?>">
                                <input type = "submit" value = "Update Project"></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>

                </form>
            </div> <!--end mainContent div tag-->

        </div>
        <?php
        include "footer.php";
        ?>

    </div>

</div>
</body>
</html>