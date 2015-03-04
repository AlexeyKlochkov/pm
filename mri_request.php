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
</head>
<?php
include_once "functions/dbconn.php";
function getRequestTypes(){
    $link=dbConn();
    $result="<option id='0' disabled selected></option>";
    $handle=$link->prepare("SELECT * FROM MRI_request_type");
    $handle->bindColumn("id",$id,PDO::PARAM_INT);
    $handle->bindColumn("short_name",$short,PDO::PARAM_STR);
    $handle->bindColumn("name",$name,PDO::PARAM_STR);
    try{
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            $result.="<option id='$id' title='$short' label='$name' value='$id'>".$name."</option>";
        }
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}

function getReportTypes(){
    $link=dbConn();
    $result="<option id='0' disabled selected></option>";
    $handle=$link->prepare("SELECT * FROM MRI_report_type");
    $handle->bindColumn("id",$id,PDO::PARAM_INT);
    $handle->bindColumn("short_name",$short,PDO::PARAM_STR);
    $handle->bindColumn("name",$name,PDO::PARAM_STR);
    try{
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            $result.="<option id='$id' title='$short' label='$name' value='$id'>".$name."</option>";
        }
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}

function getStates(){
    $link = dbConn();
    $result="<option id='0' disabled selected></option>";
    $handle = $link->prepare("select * from state order by state_name asc");
    $handle->bindColumn("id",$id,PDO::PARAM_INT);
    $handle->bindColumn("state_name",$name,PDO::PARAM_STR);
    $handle->bindColumn("state_abbrev",$abbrev,PDO::PARAM_STR);
    try {
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            $result.="<option id='$id'>".$name."(".$abbrev.")</option>";
        }
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function getSchools(){
    $link = dbConn();
    $result="<option id='0' disabled selected></option>";
    $handle = $link->prepare("select * from business_unit where active=1 order by business_unit_name asc");
    $handle->bindColumn("id",$id,PDO::PARAM_INT);
    $handle->bindColumn("business_unit_name",$name,PDO::PARAM_STR);
    $handle->bindColumn("business_unit_abbrev",$abbrev,PDO::PARAM_STR);
    try {
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            $result.="<option id='$id'>".$name."(".$abbrev.")</option>";
        }
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
?>
<script type="text/javascript">
    $(document).ready(function(){
        $( ".datepicker" ).datepicker();
        $("#request_type").change(function(){
            var id = $(this).children(":selected").attr("title");
            $('div[title=request]').hide();
            $('#report_type').prop('selectedIndex',0);
            $('#' + id + '1').find("input[type=text], textarea").val("");
            $('#' + id + '2').find("input[type=text], textarea").val("");
            $('#' + id + '3').find("input[type=text], textarea").val("");
            $('#' + id + '1').show();
            $('#' + id + '2').show();
            $('#' + id + '3').show();
            if (id=="AHR"){
                $('#Subs1').show();
                $('#Subs3').show();
            }
        });
        $("#report_type").change(function(){
           var id = $(this).children(":selected").attr("title");
            var iid = $(this).children(":selected").attr("id");
            $('#'+id+'1').find("input[type=text], textarea").val("");
            $('#'+id+'2').find("input[type=text], textarea").val("");
            $('#'+id+'1').show();
            $('#'+id+'2').show();
            if (iid=='2'){
                $('#state').prop('selectedIndex',0);
                $('#state').hide();
                $('#state1').prop('selectedIndex',0);
                $('#state1').hide();
            }
            else {
                $('#state').show();
                $('#state1').show();
            }
        });
    });
</script>
<body style="background-color: #EFEFEF">
<div id = "page">
    <div id = "main">
        <div id = "logo">
            <img src = "logo.png">
        </div>
        <div id="container">
            <div id="mainContent">
                <h1>Marketing Research Intake</h1>
                <form class="form-horizontal" role="form" action = "add_mri.php" method = "POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="requester_name">Name:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="requester_name" name="requester_name" placeholder="Enter name" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="requester_mail">Email:</label>
                        <div class="col-sm-4">
                            <input type="email" class="form-control" id="requester_mail" name="requester_mail" placeholder="Enter email" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="requester_phone">Phone:</label>
                        <div class="col-sm-4">
                            <input type="phone" class="form-control" id="requester_phone" name="requester_phone" placeholder="Enter phone" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="request_type">Please choose type of request:</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="request_type" name="request_type" required="required">
                                <?php
                                echo getRequestTypes();
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;" id="UPLC1" title="request">
                        <label class="control-label col-sm-2" for="report_type">Type of report:</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="report_type" name="report_type">
                                <?php
                                echo getReportTypes();
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;" id="SSMS1" title="request">
                        <label class="control-label col-sm-2" for="title">Title of program:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="title" id="title" placeholder="Enter title">
                        </div>
                        <label class="control-label col-sm-2" for="state" id="state">State:</label>
                        <div class="col-sm-2" id="state">
                            <select class="form-control" id="state1" name="state">
                                <?php
                                echo getStates();
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;" id="SSMS2" title="request">
                        <label class="control-label col-sm-2" for="codes">SIP/SOC codes:</label>
                        <div class="col-sm-4">
                            <textarea id="codes" name="codes" rows="6" cols="60"></textarea>
                        </div>
                        <label class="control-label col-sm-2" for="due_date">Due date:</label>
                        <div class="col-sm-2">
                            <input  class = "datepicker" type = "text" name = "due_date" value ="" readonly="readonly" required>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;" id="Subs1" title="request">
                        <label class="control-label col-sm-2" for="lob">LOB:</label>
                        <div class="col-sm-4" >
                            <select class="form-control" name="lob">
                                <?php
                                echo getSchools();
                                ?>
                            </select>
                        </div>
                        <label class="control-label col-sm-3" for="delivery_date">Desired delivery date:</label>
                        <div class="col-sm-3">
                            <input  class = "datepicker" type = "text" name = "delivery_date" value ="" readonly="readonly" required>
                        </div>

                        <br/><br/><br/>
                        <label class="control-label col-sm-2" for="PIC">Project/Campaign name:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="PIC" name="PIC">
                        </div>
                    </div>
                    <div class="form-group" style="display: none;" id="Subs2" title="request">
                        <label class="control-label col-sm-2" for="claims">List specific claims:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="claims" name="claims">
                        </div>
                        <label class="control-label col-sm-2" for="sources">Sources, if available:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="sources" name="sources">
                        </div>
                    </div>
                    <div class="form-group" style="display: none;" id="AHR1" title="request">
                        <label class="control-label col-sm-2" for="research">Research request description:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="research" name="research">
                        </div>
                        <label class="control-label col-sm-2" for="questions">Specific questions:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="questions" name="questions">
                        </div>
                    </div>
                    <div class="form-group" style="display: none;" id="Subs3" title="request">
                        <label class="control-label col-sm-2" for="info">Additional information:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="info" name="info">
                        </div>
                    </div>
                    <input type="text" style="display:none;" value="<?php echo $_POST["isBM"]?>" name="isBm">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>