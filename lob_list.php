<?
include "loggedin.php";
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
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
</head>
<script type="text/javascript">
    $(document).ready(function () {
        $("#active").change(function(){
            var id=this.name;
            var val=this.checked?1:0;
            var params = {
                request:"Save active",
                id: id,
                active:val
            };
            $.ajax({
                url: 'lob_list_handler.php',
                global: false,
                type: "POST",
                data: params,
                dataType:"text",
                success: function (result) {

                }
            });
        });
        $("#mri").change(function(){
            var id=this.name;
            var val=this.checked?1:0;
            var params = {
                request:"Save MRI",
                id: id,
                active:val
            };
            $.ajax({
                url: 'lob_list_handler.php',
                global: false,
                type: "POST",
                data: params,
                dataType:"text",
                success: function (result) {

                }
            });
        });
        $("#addLOB").click(function(){

        });
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
            <div id="mainContent">
                <h1>Line of Business</h1>
                <button type="button" class="btn btn-primary" id="addLOB">Add Line of Business</button>
                <br>
                <br>
                <form class="form-horizontal" role="form" action = "lob_list.php" method = "POST" enctype="multipart/form-data">
                    <tr></tr>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Line of Business</th>
                            <th>Active</th>
                            <th>Is MRI</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        include_once "functions/dbconn.php";
                        $link = dbConn();
                        $handle = $link->prepare("select * from business_unit order by business_unit_name asc");
                        $handle->bindColumn("business_unit_id",$id,PDO::PARAM_INT);
                        $handle->bindColumn("business_unit_name",$name,PDO::PARAM_STR);
                        $handle->bindColumn("business_unit_abbrev",$abbrev,PDO::PARAM_STR);
                        $handle->bindColumn("active",$active,PDO::PARAM_INT);
                        $handle->bindColumn("is_mri",$isMRI,PDO::PARAM_INT);
                        try {
                        $handle->execute();
                        while ($handle->fetch(PDO::FETCH_BOUND)) {
                            if ($active) $isActiveChecked="checked"; else $isActiveChecked="";
                            if ($isMRI) $isMRIChecked="checked"; else $isMRIChecked="";
                        echo "<tr>
                            <td>$name</td>
                        <td><input id='active' type='checkbox' name=$id $isActiveChecked></td>
                        <td><input id='mri' type='checkbox' name=$id $isMRIChecked></td>
                        </tr>";
                        }
                        } catch (Exception $e) {
                        echo 'Caught exception: ', $e->getMessage(), "\n";
                        }?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>