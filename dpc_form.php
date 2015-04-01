<?php
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";
/**
 * Created by PhpStorm.
 * User: aklochko
 * Date: 3/24/15
 * Time: 1:53 PM
 */
$projectId=$_GET["id"];
function getDPC($projectId){
    $link=dbConn();
    $handle=$link->prepare("SELECT * FROM dpc_common WHERE project_id=:projectId");
    $handle->bindValue(":projectId",$projectId,PDO::PARAM_INT);
    $handle->bindColumn("id",$id,PDO::PARAM_INT);
    $handle->bindColumn("name",$name,PDO::PARAM_STR);
    $handle->bindColumn("date",$date,PDO::PARAM_STR);
    $handle->bindColumn("is_sme",$isSME,PDO::PARAM_INT);
    $handle->bindColumn("is_ald",$isALD,PDO::PARAM_INT);
    $handle->bindColumn("file",$file,PDO::PARAM_STR);
    $handle->bindColumn("status",$status,PDO::PARAM_INT);
    try{
        $handle->execute();
        if ($handle->fetch(PDO::FETCH_BOUND)) {
            $result["id"]=$id;
            $result["name"]=$name;
            if (!is_null($date))
                $result["date"]=date("m/d/Y" ,strtotime($date));
            else $result["date"]=null;
            $result["is_sme"]=$isSME;
            $result["is_ald"]=$isALD;
            $result["file"]=$file;
            $result["status"]=$status;
        }
        else $result=false;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function getReleases($id){
    $link=dbConn();
    $handle=$link->prepare("SELECT * FROM dpc_releases WHERE dpc_id=:dpc_id");
    $handle->bindValue(":dpc_id",$id,PDO::PARAM_INT);
    $handle->bindColumn("text",$text,PDO::PARAM_STR);
    $handle->bindColumn("date",$date,PDO::PARAM_STR);
    try{
        $handle->execute();
        $i=0;
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            $result[$i]["text"]=$text;
            if (!is_null($date))
                $result[$i]["date"]=date("m/d/Y" ,strtotime($date));
            else $result[$i]["date"]=null;
            $i++;
        }
        if ($i==0){
            $result=false;
        }
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function getSME($id){
    $link=dbConn();
    $handle=$link->prepare("SELECT * FROM dpc_sme WHERE dpc_id=:dpc_id");
    $handle->bindValue(":dpc_id",$id,PDO::PARAM_INT);
    $handle->bindColumn("text",$text,PDO::PARAM_STR);
    $handle->bindColumn("date",$date,PDO::PARAM_STR);
    try{
        $handle->execute();
        $i=0;
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            $result[$i]["text"]=$text;
            if (!is_null($date))
                $result[$i]["date"]=date("m/d/Y" ,strtotime($date));
            else $result[$i]["date"]=null;
            $i++;
        }
        if ($i=0){
            $result=false;
        }
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function getApprovals($id){
    $link=dbConn();
    $handle=$link->prepare("SELECT * FROM dpc_approvals WHERE dpc_id=:dpc_id");
    $handle->bindValue(":dpc_id",$id,PDO::PARAM_INT);
    $handle->bindColumn("executive",$executive,PDO::PARAM_STR);
    $handle->bindColumn("dean",$dean,PDO::PARAM_STR);
    $handle->bindColumn("cabinet",$cabinet,PDO::PARAM_STR);
    $handle->bindColumn("svp",$svp,PDO::PARAM_STR);
    try{
        $handle->execute();
        if ($handle->fetch(PDO::FETCH_BOUND)) {
            if (!is_null($executive))
                $result["executive"]=date("m/d/Y" ,strtotime($executive));
            else $result["executive"]=null;
            if (!is_null($dean))
             $result["dean"]=date("m/d/Y" ,strtotime($dean));
            else $result["dean"]=null;
            if (!is_null($cabinet))
                $result["cabinet"]=date("m/d/Y" ,strtotime($cabinet));
            else $result["cabinet"]=null;
            if (!is_null($svp))
                $result["svp"]=date("m/d/Y" ,strtotime($svp));
            else $result["svp"]=null;
        }
        else $result=false;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function getFile($id){
    $link=dbConn();
    $handle=$link->prepare("SELECT f.name,f.date,f.notes FROM dpc_file as f WHERE f.dpc_id=:dpc_id ");
    $handle->bindValue(":dpc_id",$id,PDO::PARAM_INT);
    $handle->bindColumn("notes",$notes,PDO::PARAM_STR);
    $handle->bindColumn("date",$date,PDO::PARAM_STR);
    $handle->bindColumn("name",$name,PDO::PARAM_STR);
    try{
        $handle->execute();
        if ($handle->fetch(PDO::FETCH_BOUND)) {
            $result["notes"]=$notes;
            if (!is_null($date))
                $result["date"]=date("m/d/Y" ,strtotime($date));
            else $result["date"]=null;
            $result["name"]=$name;
        }
        else $result=false;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }

}
$dpc=getDPC($projectId);
$disabled="";
if ($dpc) {
    $releases = getReleases($dpc["id"]);
    $sme = getSME($dpc["id"]);
    $file = getFile($dpc["id"]);
    $approvals=getApprovals($dpc["id"]);
    if (($dpc["status"]==1) && ($_SESSION["user_level"]<20)){
        $disabled="disabled";
    }
    else {
        $disabled="";
    }
}
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
</head>
<script type="text/javascript">
    var id=0;
    var jd=0;
    function addRelease(){
        id++;
        var child="<div class='form-group' id='release" + id  + "'><div class='col-sm-7'><input type='text' class='form-control' id='release' name='release[]' placeholder='Enter forms'></div><div class='col-sm-2'> <input class='datepicker' type='text' id='dpc-date-release"+id+"' name='date[]'></div><div class='col-sm-1'><span class='glyphicon glyphicon-calendar' id='dpc-date-release-span'></span> </div> <div class='col-sm-2'> <a class='add_release' href='javascript:addRelease();' id='" + id  + "'>add</a> <a class='delete_release' href='javascript:deleteRelease("+id+");' id='" + id  + "'>delete</a> </div> </div>";
        $('#release_parent').append(child);
        $('.datepicker').each(function(){
            $(this).datepicker();
        });
        return false;
    }
    function deleteRelease(curId){
        $("[id=release"+curId+"]").remove();
        return false;
    }
    function addSME(){
        jd++;
        var child="<div class='form-group' id='sme" + jd  + "'><div class='col-sm-7'><input type='text' class='form-control' id='sme' name='sme[]' placeholder='Enter SME'></div><div class='col-sm-2'> <input class='datepicker' type='text' id='sme-date"+jd+"' name='sme_date[]'></div><div class='col-sm-1'><span class='glyphicon glyphicon-calendar' id='dpc-date-release-span'></span> </div> <div class='col-sm-2'> <a class='add_release' href='javascript:addSME();' id='" + jd  + "'>add</a> <a class='delete_release' href='javascript:deleteSME("+jd+");' id='" + jd  + "'>delete</a> </div> </div>";
        $('#sme_parent').append(child);
        $('.datepicker').each(function(){
            $(this).datepicker();
        });
        return false;
    }
    function deleteSME(curId){
        $("[id=sme"+curId+"]").remove();
        return false;
    }
    $(document).ready(function() {
        $('.datepicker').each(function(){
            $(this).datepicker();
        });
        var flag=1;
        $('input:radio').change(function(){
           if ($(this).is(':checked') && $(this).val()=='0'){
               $('#svp_table').show();
           }
            else {
               $('input:radio').each(function(){
                   if ($(this).is(':checked') && $(this).val()=='0') {
                       flag=0;
                   }
               });
               if (flag == 1){
                   $('#svp_table').hide();
               }
           }
        });

    });
</script>
<body style="background-color: #EFEFEF">
<div id = "page">
    <div id = "main">
        <div id = "logo">
            <a href="index.php"><img src = "logo.png"></a>
        </div>
        <?php
        include "nav1.php";
        ?>
        <!--container div tag-->
        <div id="container">
            <div id="mainContent"  > <!--mainContent div tag-->
                <form id="dpc_from" method="post" action="add_dpc.php" enctype="multipart/form-data">
                    <h2 align="center">Digital Primary Control Form</h2>
                    <div class="dpc_form_text">
                        <p style="margin-top:30px;" id="text">
                            Primary control reviewers should reference this document throughout the life cycle of a project to track key requirement that
                            must be completed before approving marketing material for publishing.Please consult UOPX Standard Operating Procedure for full
                            process and requirements.Materials will not be released until the project managers (PM) have received this signed and dated Primary
                            Control Review, Approval Sheet and Checklist.
                        </p>
                        <p>
                            As per UOPX Marketing Standard Operating Procedure, this is the final approval required management releases material for publishing.
                        </p>
                        <p>
                            Each section requires a response(s) for this sheet to be complete.
                        </p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr class="info">
                                    <th>
                                        Consent/Release forms
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="release_parent">
                                        <div class="form-group">
                                            <label for="release" class="col-sm-7">Please list consent/release forms</label>
                                            <label for="date" class="col-sm-2">Date</label>
                                        </div>

                                        <?php
                                        if ($dpc && $releases) :
                                            foreach ($releases as $release):
                                                $i=0;
                                                ?>
                                                <div class="form-group" id="release<?php echo $i;?>">
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="release"
                                                               name="release[]" placeholder="Enter forms" value="<?php echo $release['text']?>" <?php echo $disabled?> >
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input class="datepicker" type="text" id="dpc-date-release"
                                                               name="date[]" value="<?php echo $release['date']?>" <?php echo $disabled?>>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <span class="glyphicon glyphicon-calendar"
                                                              id="dpc-date-release-span"></span>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <?php if (!$disabled):?>
                                                        <a class="add_release" href="javascript:addRelease();" id="<?php echo $i;?>">add</a>
                                                        <a class="delete_release" href="javascript:deleteRelease(<?php echo $i;?>);" id="<?php echo $i;?>">delete</a>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            <?php $i++; endforeach;
                                        else :?>
                                            <div class="form-group" id="release0">
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="release"
                                                           name="release[]" placeholder="Enter forms" <?php echo $disabled?> required="required">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input class="datepicker" type="text" id="dpc-date-release"
                                                           name="date[]" <?php echo $disabled?> required="required">
                                                </div>
                                                <div class="col-sm-1">
                                                        <span class="glyphicon glyphicon-calendar"
                                                              id="dpc-date-release-span"></span>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a class="add_release" href="javascript:addRelease();" id="0">add</a>
                                                    <a class="delete_release" href="javascript:deleteRelease(0);"
                                                       id="0">delete</a>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="width: 50%;">
                            <table class="table table-condensed" >
                                <thead>
                                <tr class="info">
                                    <th>
                                        Approvals (Check only what is necessary)
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td id="approval_parent">
                                        <div class="checkbox col-sm-5">
                                            <label>
                                                <input type="checkbox" id="executive" name="executive" <?php if ($dpc && $approvals && !is_null($approvals["executive"])) echo "checked";?> <?php echo $disabled?>>Executive approval
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input class="datepicker" type="text" id="executive-date" name="executive_date" <?php if ($dpc && $approvals && !is_null($approvals["executive"])) echo "value='".$approvals["executive"]."'";?> <?php echo $disabled?>>
                                        </div>
                                        <div class="col-sm-2">
                                            <span class="glyphicon glyphicon-calendar"  id="dpc-date-span"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="release_parent">
                                        <div class="checkbox col-sm-5">
                                            <label>
                                                <input type="checkbox" id="dean" name="dean" <?php if ($dpc && $approvals && !is_null($approvals["dean"])) echo "checked";?> <?php echo $disabled?>>Executive Dean approval
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input class="datepicker" type="text" id="dean-date" name="dean_date" <?php if ($dpc && $approvals && !is_null($approvals["dean"])) echo "value='".$approvals["dean"]."'";?> <?php echo $disabled?>>
                                        </div>
                                        <div class="col-sm-2">
                                            <span class="glyphicon glyphicon-calendar"  id="dpc-date-span"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="release_parent">
                                        <div class="checkbox col-sm-5">
                                            <label>
                                                <input type="checkbox" id="cabinet" name="cabinet" <?php if ($dpc && $approvals && !is_null($approvals["cabinet"])) echo "checked";?> <?php echo $disabled?>>Cabinet approval
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input class="datepicker" type="text" id="cabinet-date" name="cabinet_date" <?php if ($dpc && $approvals && !is_null($approvals["cabinet"])) echo "value='".$approvals["cabinet"]."'";?> <?php echo $disabled?>>
                                        </div>
                                        <div class="col-sm-2">
                                            <span class="glyphicon glyphicon-calendar"  id="dpc-date-span"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="release_parent">
                                        <div class="checkbox col-sm-5">
                                            <label>
                                                <input type="checkbox" id="svp" name="svp" <?php if ($dpc && $approvals && !is_null($approvals["svp"])) echo "checked";?> <?php echo $disabled?>>SVP approval (production > $1M)
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input class="datepicker" type="text" id="svp-date" name="svp_date" <?php if ($dpc && $approvals && !is_null($approvals["svp"])) echo "value='".$approvals["svp"]."'";?> <?php echo $disabled?>>
                                        </div>
                                        <div class="col-sm-2">
                                            <span class="glyphicon glyphicon-calendar"  id="dpc-date-span"></span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <table class="table table-condensed table-bordered">
                            <thead>
                            <tr class="info">
                                <th>
                                    <u>Subject Matter Expert (SME) Input and Approval</u>
                                    <p>If SME input and approval are required to develop you material, please list here:</p>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td id="sme_parent">
                                    <div class="form-group">
                                        <label for="sme" class="col-sm-7">SME Input</label>
                                        <label for="date" class="col-sm-2">Date</label>
                                    </div>
                                    <?php
                                    if ($dpc && $sme) :
                                    foreach ($sme as $value):
                                    $i=0;
                                    ?>
                                    <div class="form-group" id="sme<?php echo $i;?>">
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="sme" name="sme[]" placeholder="Enter SME" value="<?php echo $value["text"]?>" <?php echo $disabled?>>
                                        </div>
                                        <div class="col-sm-2">
                                            <input class="datepicker" type="text" id="sme-date" name="sme_date[]" value="<?php echo $value["date"]?>" <?php echo $disabled?>>
                                        </div>
                                        <div class="col-sm-1">
                                            <span class="glyphicon glyphicon-calendar" id="dpc-date-release-span" ></span>
                                        </div>
                                        <div class="col-sm-2">
                                        <?php if (!$disabled):?>
                                            <a class="add_release" href="javascript:addSME();" id="<?php echo $i;?>">add</a>
                                            <a class="delete_release" href="javascript:deleteSME(<?php echo $i;?>);" id="<?php echo $i;?>">delete</a>
                                        <?php endif;?>
                                        </div>
                                    </div>
                                        <?php $i++; endforeach;
                                    else :?>
                                    <div class="form-group" id="sme0">
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="sme" name="sme[]" placeholder="Enter SME" <?php echo $disabled?> required="required">
                                        </div>
                                        <div class="col-sm-2">
                                            <input class="datepicker" type="text" id="sme-date" name="sme_date[]" <?php echo $disabled?> required="required">
                                        </div>
                                        <div class="col-sm-1">
                                            <span class="glyphicon glyphicon-calendar" id="dpc-date-release-span" ></span>
                                        </div>
                                        <div class="col-sm-2">
                                        <?php if (!$disabled):?>
                                            <a class="add_release" href="javascript:addSME();" id="0">add</a>
                                            <a class="delete_release" href="javascript:deleteSME(0);" id="0">delete</a>
                                        <?php endif;?>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div>
                            <div class="radio">
                                <label class="radio-inline">
                                    <input type="radio" id="radio_yes" name="sme_radio" value="1" <?php if($dpc && $dpc["is_sme"]) echo "checked";?> <?php echo $disabled?> required="required">Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" id="radio_no" name="sme_radio" value="0" <?php if($dpc && !$dpc["is_sme"]) echo "checked";?> <?php echo $disabled?> required="required">No
                                </label>
                                <label>
                                    Verify that SME's feedback was included in marketing material
                                </label>
                            </div>
                            <div class="radio">
                                <label class="radio-inline">
                                    <input type="radio" id="radio_yes" name="ald_radio" value="1" <?php if($dpc && $dpc["is_ald"]) echo "checked";?> <?php echo $disabled?> required="required">Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" id="radio_no" name="ald_radio" value="0" <?php if($dpc && !$dpc["is_ald"]) echo "checked";?> <?php echo $disabled?> required="required">No
                                </label>
                                <label>
                                    Verify that ALD/AEC feedback was included in marketing material
                                </label>
                            </div>
                        </div>
                        <table class="table table-condensed table-bordered" id="svp_table" style="<?php if($dpc && ($dpc["is_ald"] && $dpc["is_sme"]))  echo "display:none;"?>">
                            <thead>
                            <tr class="info">
                                <th>
                                    <u>If no, please upload SVP exception</u>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td id="sme_parent">
                                    <div class="form-group">
                                        <label for="file" class="col-sm-5">File</label>
                                        <label for="upload_date" class="col-sm-3">Upload date</label>
                                        <label for="notes" class="col-sm-2">Notes</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-5">
                                           <?php if ($dpc && $file) echo "<a href='dpc_files/".$projectId."/".$file['name']."'>".$file['name']."</a>";?>
                                        </div>
                                        <div class="col-sm-3">
                                            <?php if ($dpc && $file) echo $file['date'];?>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" name="file_notes" value="<?php if ($dpc && $file) echo $file['notes'];?>" <?php echo $disabled?>>
                                        </div>
                                    </div>
                                    <input type="file" name="file" id="file" <?php echo $disabled?>>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <div style="width: 50%;">
                            <table class="table table-condensed" >
                                <thead>
                                <tr class="info">
                                    <th>
                                        Final approval verification
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td id="verification_parent">
                                        <div class="form-group">
                                        <div class="col-sm-9">
                                            <input type="text" id="name" name="name" placeholder="Type name (in place of signature)" style="width:100%" value="<?php if ($dpc) echo $dpc["name"];?>" <?php echo $disabled?> required="required">
                                        </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-sm-4">
                                            <input class="datepicker" type="text" id="final_date" name="final_date" value="<?php if ($dpc) echo $dpc["date"];?>" <?php echo $disabled?> required="required">
                                        </div>
                                        <div class="col-sm-2">
                                            <span class="glyphicon glyphicon-calendar"  id="dpc-date-span"></span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <p style="color:red;">
                                By signing this document, you certify that all releases/approvals/inputs have been received and assets are clear for release.
                            </p>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-default" <?php echo $disabled?>>Submit</button>
                            <a href="manage_project.php?p=<?php echo $projectId;?>"><button class="btn btn-primary">Back to project</button></a>
                        </div>
                    </div>
                    <input type="text" style="display:none;" name="project_id" value="<?php echo $projectId?>">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
