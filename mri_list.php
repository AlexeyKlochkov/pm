<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
function getMriList(){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT * FROM MRI_common");
    $stmt->bindColumn('id', $id);
    $stmt->bindColumn('isBM', $isBm);
    $stmt->bindColumn('code', $code);
    $stmt->bindColumn('status_id', $status);
    $stmt->bindColumn('requester_name', $requesterName);
    $stmt->bindColumn('requester_mail', $requesterMail);
    $stmt->bindColumn('requester_phone', $requesterPhone);
    $stmt->bindColumn('requester_type_id', $requestTypeId);
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
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        $result = false;
    }
    return $result;
}
function getRequestType($id){
    $link=dbConn();
    $handle=$link->prepare("SELECT name FROM MRI_request_type WHERE id=:id");
    $handle->bindValue("id",$id,PDO::PARAM_INT);
    $handle->bindColumn("name",$name,PDO::PARAM_STR);
    try{
        $handle->execute();
        if ($handle->fetch(PDO::FETCH_BOUND)) {
            $result=$name;
        }
        else $result = false;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        $result = false;
    }
    return $result;
}
function getStatusName($id){
    $link=dbConn();
    $handle=$link->prepare("SELECT name FROM MRI_statuses WHERE id=:id");
    $handle->bindValue("id",$id,PDO::PARAM_INT);
    $handle->bindColumn("name",$name,PDO::PARAM_STR);
    try{
        $handle->execute();
        if ($handle->fetch(PDO::FETCH_BOUND)) {
            $result=$name;
        }
        else $result = false;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        $result = false;
    }
    return $result;
}
$mriList=getMriList();

$wif_table = "<table class = \"budget\"><tr><th colspan = \"10\">Current MRIs</th></tr>\n";
$wif_table .= "<tr><th>MRI Code</th><th>Type of request</th><th>Requested By</th><th>Status</th></tr>\n";
if (!empty($mriList)){
    foreach ($mriList as $mri){
        $requestType=getRequestType($mri["request_type_id"]);
        $status=getStatusName($mri["status_id"]);
        $wif_table .= "<tr><td><a href='manage_mri.php?id=".$mri['id']."'>" . $mri['code'] . "</td><td>" . $requestType. "</td><td>" . $mri['requester_name'] . "</td><td>" .$status . "</td></td>";
    }
}

$wif_table .= "</table>";
?>
<html>
<head>
    <link href='style.css' rel='stylesheet' type='text/css' />
    <link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

    <title>WIF List</title>
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script>
        $(document).ready(function(){

            $( "#wif_form" ).validate({
                rules: {
                    task_rate: {
                        required: false,
                        number: true
                    }
                }
            });

            $( "#wif_status_select" ).change(function() {
                if ($( "#wif_status_select" ).val()==2){
                    //alert( "Handler for .change() called." );
                    $( ".new_project" ).show();
                    $( ".aop_select" ).show();

                    $("#wif_sub").prop('value', 'Create Project');
                }else{
                    $( ".new_project" ).hide();
                    $( ".aop_select" ).show();
                    $("#wif_sub").prop('value', 'Update');
                }

            });

            $( "#wif_status_select_main" ).change(function() {
                $( "#wif_list_refresh" ).submit();
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
                <h1>Marketing Intake Research forms</h1>
                <table border = "0" width = "95%">
                    <tr>
                        <td valign="top">
                            <?php echo $wif_table ?>
                            <br><br>
                        </td>
                    </tr>
                </table>
            </div> <!--end mainContent div tag-->
        </div>
        <?php
        include "footer.php";
        ?>
    </div>
</div>
<script>
    $( "#update_wif_status" ).validate({
        rules: {
            task_rate: {
                required: false,
                number: true
            }

        }
    });
</script>
</body>
</html>