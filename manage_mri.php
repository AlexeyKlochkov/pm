<?php
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";
$campaign_id = 0;
$project_manager_id = 0;
$active_flag = 1;
$is_user = 0;

function getRequestType($selected){
    $link=dbConn();
    $handle=$link->prepare("SELECT name FROM MRI_request_type where id=:selected");
    $handle->bindValue(":selected",$selected,PDO::PARAM_INT);
    $handle->bindColumn("short_name",$short,PDO::PARAM_STR);
    $handle->bindColumn("name",$name,PDO::PARAM_STR);
    try{
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            $result = $name;
            return $result;
        }
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function getReportType($selected){
    $link=dbConn();
    $handle=$link->prepare("SELECT name FROM MRI_report_type WHERE id=:selected");
    $handle->bindValue(":selected",$selected,PDO::PARAM_INT);
    $handle->bindColumn("short_name",$short,PDO::PARAM_STR);
    $handle->bindColumn("name",$name,PDO::PARAM_STR);
    try{
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {

            $result = $name;
            return $result;
        }
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}

function getState($selected){
    $link = dbConn();

    $handle = $link->prepare("select * from state where state_id=:id");
    $handle->bindValue("id",$selected,PDO::PARAM_INT);
    $handle->bindColumn("state_name",$name,PDO::PARAM_STR);
    $handle->bindColumn("state_abbrev",$abbrev,PDO::PARAM_STR);
    try {
        $handle->execute();
        while ($handle->fetch(PDO::FETCH_BOUND)) {
            $result =$name;
            return $result;
        }
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function getStates($selected){
    $link = dbConn();
    $result="";
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

        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function getSchools($selected){
    $link = dbConn();
    $result="<select class = \"required\" name = \"report_type\"><option value = \"\">Please select</option>";
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
    $result="";
    $stmt = $dbConnection->prepare("SELECT * FROM MRI_statuses");
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
function getMri($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT * FROM MRI_common WHERE id=:id");
    $stmt->bindValue('id', $id,PDO::PARAM_INT);
    $stmt->bindColumn('isBM', $isBm);
    $stmt->bindColumn('code', $code);
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
$file_error = "";
$approval_message = "";
$fast_track_status_message = "";

if (!empty($_GET["e"])){
    $error_num = $_GET["e"];
    if ($error_num == 1){
        $file_error = "Duplicate file name exists for this project. Please rename the file and re-upload.";
    }
    if ($error_num == 2){
        $approval_message = "Email sent.";
    }
    if ($error_num == 3){
        $approval_message = "Error sending email.";
    }
    if ($error_num == 4){
        $fast_track_status_message = "Fast Track turned off.";
    }
    if ($error_num == 5){
        $fast_track_status_message = "Fast Track started.";
    }
    if ($error_num == 6){
        $asset_message = "Asset added.";
    }
    if ($error_num == 7){
        $asset_message = "An error occurred while adding an asset.";
    }
}

$file_error_num = "";
$file_error_message = "";
if (!empty($_GET["fe"])){
    $file_error_num = $_GET["fe"];

    if ($file_error_num == 1){
        $file_error_message = "&nbsp;&nbsp;Uploaded file is too big. 20MB max please.";
    }
    if ($file_error_num == 2){
        $file_error_message = "&nbsp;&nbsp;Upload folder is not writable.";
    }
    if ($file_error_num == 3){
        $file_error_message = "&nbsp;&nbsp;File name exists. Please re-name your file before uploading.";
    }
    if ($file_error_num == 4){
        $file_error_message = "&nbsp;&nbsp;Only one file per asset item allowed.";
    }
    if ($file_error_num == 5){
        $file_error_message = "&nbsp;&nbsp;Don't forget to select a file...";
    }
}

if (!empty($_GET["id"])){
    $project_id = $_GET["id"];
}else{
    $location = "Location: loggedout.php";
    header($location) ;
}


$arr_project = getMri($project_id);

if (empty($arr_project)){
    $location = "Location: loggedout.php";
    header($location) ;
}

$_SESSION["project_id"] = $project_id;
//print_r($arr_projects);



$show_files = "hide";
if (!empty($_GET["show_files"])){
    if ($_GET["show_files"] == 1){
        $show_files = "show";
    }
}

$show_schedules = "hide";
if (!empty($_GET["show_schedules"])){
    if ($_GET["show_schedules"] == 1){
        $show_schedules = "show";
    }
}


$show_legal = "hide";
if (!empty($_GET["showLegal"])){
    if ($_GET["showLegal"] == 1){
        $show_legal = "show";
    }
}

$show_studio = "hide";
if (!empty($_GET["showStudio"])){
    if ($_GET["showStudio"] == 1){
        $show_studio = "show";
    }
}

$show_financial = "hide";
if (!empty($_GET["showFinancial"])){
    if ($_GET["showFinancial"] == 1){
        $show_financial = "show";
    }
}

$show_final = "hide";
if (!empty($_GET["showFinal"])){
    if ($_GET["showFinal"] == 1){
        $show_final = "show";
    }
}

$show_cr = "hide";
if (!empty($_GET["showCR"])){
    if ($_GET["showCR"] == 1){
        $show_cr = "show";
    }
}

$show_cb = "hide";
if (!empty($_GET["showCB"])){
    if ($_GET["showCB"] == 1){
        $show_cb = "show";
    }
}

//print $show_users;



$current_year = date("Y");
$current_month = date("m");;
$spend_year_select = get_year_select_spend($current_year);
$spend_month_select = get_month_select($current_month);


$spend_table = "<table class=\"budget\"><tr><th>#</th><th>Type</th><th>Asset</th><th>Vendor</th><th>Notes</th><th>Invoice #</th><th>PO#</th><th>Cost Center</th><th>% Complete</th><th>Date</th><th>Amount</th><th>Remaining</th><th colspan = \"2\">&nbsp;</th></tr>";
$arr_spend = get_spend_by_project($project_id);

$media_spend_total = 0;
$production_spend_total = 0;
$other_spend_total = 0;

$vendor_other_id = get_vendor_other_id($company_id);

$i = 1;
if (!empty($arr_spend)){
    foreach ($arr_spend as $spend_row){
        $spend_id = $spend_row["spend_id"];

        $vendor_name = $spend_row["vendor_name"];
        $vendor_other = $spend_row["vendor_other"];
        $vendor_id = $spend_row["vendor_id"];
        if($vendor_id == $vendor_other_id){
            $vendor_name = $vendor_other;
        }

        $spend_type = $spend_row["spend_type"];
        $spend_notes = $spend_row["spend_notes"];
        $spend_amount = $spend_row["spend_amount"];
        $invoice_number = $spend_row["invoice_number"];
        $po_number = $spend_row["po_number"];
        $cost_expense_account = $spend_row["cost_expense_account"];
        $arr_spend_percent = get_max_spend_percent($spend_id);
        $percent_complete = "n/a";
        $spend_date = "n/a";
        if (!empty($arr_spend_percent)){
            $percent_complete = $arr_spend_percent[0]["spend_percent"] . "%";
            $spend_month_date = $arr_spend_percent[0]["spend_month"];
            $arr_spend_month_date = explode("-", $spend_month_date);
            $year = $arr_spend_month_date[0];
            $month_abbrev = get_month_abbrev($arr_spend_month_date[1]);
            $spend_date = $month_abbrev . "-" . $year;
            $percent_left = (100-$percent_complete)/100;
            $spend_balance = ($spend_amount*($percent_left));
            //$spend_balance = round($spend_balance,2);
            $spend_balance = number_format((float)$spend_balance, 2, '.', '');

        }else{
            $spend_balance = $spend_amount;

        }



        $spend_table .= "<tr><td>" . $i . "</td><td>" . $spend_type . "</td><td>" . $asset_name . "</td><td>" . $vendor_name . "</td><td>" . $spend_notes . "</td><td>" . $invoice_number . "</td><td>" . $po_number . "</td><td>" . $cost_expense_account . "</td><td align=\"right\">" . $percent_complete . "</td><td align=\"right\">" . $spend_date . "</td><td align=\"right\">" .  add_commas($spend_amount) . "</td><td align=\"right\">" .  add_commas($spend_balance) . "</td><td><a href = \"edit_spend.php?p=" . $project_id . "&s=" . $spend_id . "\">edit</a></td><td><a href = \"del_spend.php?p=" . $project_id . "&s=" . $spend_id . "\" onclick=\"return confirm('Are you sure want to delete this spend entry?');\">del</a></td></tr>";

        if ($spend_type == "Media"){
            $media_spend_total = $media_spend_total + $spend_amount;
        }
        if ($spend_type == "Production"){
            $production_spend_total = $production_spend_total + $spend_amount;
        }
        if ($spend_type == "Other"){
            $other_spend_total = $other_spend_total + $spend_amount;
        }

        $i++;
    }
}else{
    $spend_table .= "<tr><td colspan = \"13\">no spend</td></tr>";
}
;

$assignedUsers=Array();
function getStatusName($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT name FROM MRI_statuses WHERE id=:id");
    $stmt->bindValue('id', $id,PDO::PARAM_INT);
    $stmt->bindColumn('name', $name,PDO::PARAM_STR);
    try {
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_BOUND)) {
            $result=$name;
        } else
            $result=false;

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        $result = false;
    }
    return $result;
}

function getProductName($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT business_unit_name FROM business_unit WHERE business_unit_id=:id and is_mri=1");
    $stmt->bindValue('id', $id,PDO::PARAM_INT);
    $stmt->bindColumn('business_unit_name', $name,PDO::PARAM_STR);
    try {
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_BOUND)) {
            $result=$name;
        } else
            $result=false;

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        $result = false;
    }
    return $result;
}
$project_table = "<table class = \"table table-hover\">";
if (!empty($arr_project)){
    $requestType=getRequestType($arr_project[0]["request_type_id"]);
    $project_id = $arr_project[0]["id"];
    $project_code = $arr_project[0]["code"];
    $project_status = getStatusName($arr_project[0]["status_id"]);
    $project_requester = $arr_project[0]["requester_name"];
    $project_table .=" <tr><td class = \"left_header\">Request type:</td><td>".$requestType."</td></tr>";
    if (isset($arr_project[0]["lob_id"])) {
        $project_table .= "<tr><td class = \"left_header\">Line of Business:</td><td>" . getProductName($arr_project[0]["lob_id"]). "</td></tr>";
    }
    if (isset($arr_project[0]["report_type_id"])) {
        $project_table .= "<tr><td class = \"left_header\">Report type:</td><td>" . getReportType($arr_project[0]["report_type_id"]) . "</td></tr>";
    }
    $project_table .= "<tr><td class = \"left_header\">Status:</td><td title='$project_id' rel='status_id' >" . $project_status . "</td></tr>";
    if (isset($arr_project[0]["pic_name"])) {
        $project_table .= "<tr><td class = \"left_header\">Project/Campaign name:</td><td title='$project_id' rel='pic_name' >" . $arr_project[0]["pic_name"] . "</td></tr>";
    }
    if (isset($arr_project[0]["delivery_date"])) {
        $project_table .= "<tr><td class = \"left_header\">Desired delivery date:</td><td title='$project_id' rel='delivery_date' >" . translate_mysql_todatepicker($arr_project[0]["delivery_date"]) . "</td></tr>";
    }
    $project_table .= "<tr><td class = \"left_header\">Name of requester:</td><td title='$project_id' rel='requester_name' >" . $arr_project[0]["requester_name"] . "</td></tr>";
    $project_table .= "<tr><td class = \"left_header\">Requester email:</td><td title='$project_id' rel='requester_mail' >" . $arr_project[0]["requester_mail"] . "</td></tr>";
    $project_table .= "<tr><td class = \"left_header\">Requester phone:</td><td  title='$project_id' rel='requester_phone' >" . $arr_project[0]["requester_phone"] . "</td></tr>";

    if (isset($arr_project[0]["due_date"])) {
        $project_table .= "<tr><td class = \"left_header\">Desired delivery Date:</td><td title='$project_id' rel='due_date' >" . translate_mysql_todatepicker($arr_project[0]["due_date"]) . "</td></tr>";
    }
    if (isset($arr_project[0]["state_id"])) {
        $states=getStates($arr_project[0]["state_id"]);
        $project_table .= "<tr><td class = \"left_header\">State:</td><td title='$project_id' rel='state_id' >" . getState($arr_project[0]["state_id"]) . "</td></tr>";
    }
    if (isset($arr_project[0]["codes"])) {
        $project_table .= "<tr><td class = \"left_header\">CIP/SOC:</td><td title='$project_id' rel='codes' >" . $arr_project[0]["codes"] . "</td></tr>";
    }

    if (isset($arr_project[0]["spec_claims"])) {
        $project_table .= "<tr><td class = \"left_header\">List specific claims:</td><td title='$project_id' rel='spec_claims' >" . $arr_project[0]["spec_claims"] . "</td></tr>";
    }
    if (isset($arr_project[0]["sources"])) {
        $project_table .= "<tr><td class = \"left_header\">Sources, if available:</td><td title='$project_id' rel='sources' >" . $arr_project[0]["sources"] . "</td></tr>";
    }
    if (isset($arr_project[0]["info"])) {
        $project_table .= "<tr><td class = \"left_header\">Additional information:</td><td title='$project_id' rel='info' >" . $arr_project[0]["info"] . "</td></tr>";
    }
    if (isset($arr_project[0]["request_description"])) {
        $project_table .= "<tr><td class = \"left_header\">Research request description:</td><td title='$project_id' rel='request_description' >" . $arr_project[0]["request_description"] . "</td></tr>";
    }
    if (isset($arr_project[0]["spec_questions"])) {
        $project_table .= "<tr><td class = \"left_header\">Specific questions:</td><td title='$project_id' rel='spec_questions' >" . $arr_project[0]["spec_questions"] . "</td></tr>";
    }
    $statuses=getStatuses($arr_project[0]["status_id"]);

    $pif_id = "";
    $pif_code = "";
    if ($_SESSION["user_level"] > 10 || (in_array($_SESSION["user_id"],$assignedUsers))){
        $arr_pif = get_max_pif_for_project($project_id);
        $pif_id = $arr_pif[0]["pif_id"];
        $pif_code = $arr_pif[0]["pif_code"];
        if(!empty($pif_id)){
            $project_table .= "<tr><td class = \"left_header\">Project Brief:</td><td><a href = \"view_pif.php?p=" . $pif_id . "\" target = \"_blank\">" . $pif_code . "</a></td></tr>";
        }
    }


}

$project_table .= " </table>";




$spend_colspan = "4";
if ($_SESSION["user_level"] > 30){
    $spend_colspan = "5";
}

//Put together file section


$legal_current_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Upload date</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$legal_archive_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Upload date</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$final_current_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Upload date</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$final_archive_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Upload date</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
$round_area = "";

$arr_project_files = get_project_files($project_id);
$prev_round = 0;
$has_rounds = 0;
$current_round = 0;
$approval_document_select = "No active documents";
if (!empty($arr_project_files)){
    $approval_document_select = "<select name = \"approval_project_file_id\"><option value = \"\">Please select (optional)</option>";
    foreach ($arr_project_files as $file_row){
        $project_file_id = $file_row["project_file_id"];
        $file_name = $file_row["project_file_name"];
        $file_notes = $file_row["file_notes"];
        $file_type = $file_row["file_type"];
        $file_active = $file_row["active"];
        $file_network_folder = $file_row["file_network_folder"];
        $file_upload_date=$file_row["update_date"];
        $directory = "mri_files/" . $arr_project[0]["code"] . "/";
        $file_location = $directory. $file_name;
        $notes_field = $file_notes;
        if ($_SESSION["user_level"] > 10){
            $notes_field = "<a href=\"#\" onclick=\"openpopup3('popup3','" . $project_file_id . "','" . $file_notes . "','" . $file_type . "','" . $file_name . "','" . $project_id . "')\"><img src = \"images/edit_sm.png\" border = \"0\"></a>&nbsp;&nbsp;" . $file_notes;

        }

        if ($file_type == "Legal"){
            if ($file_active == 1){
                $legal_current_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>".date("m/d/Y H:m",strtotime($file_upload_date))."</td><td>" . $notes_field . "</td><td><a href =\"del_mri.php?a=2&f=" . $file_type . "&pfid=" . $project_file_id . "&id=" . $project_id ."\">archive</a></td></tr>";
                $approval_document_select .= "<option value = \"" .  $project_file_id . "\">LEGAL - " . $file_name . "</option>\n";
            }else{
                $legal_archive_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>".date("m/d/Y H:m",strtotime($file_upload_date))."</td><td>" . $file_notes . "</td><td><a href =\"del_mri.php?a=1&f=" . $file_type . "&pfid=" . $project_file_id . "&id=" . $project_id ."\">activate</a></td></tr>";

            }

        }

        if ($file_type == "Final"){
            $file_network_location_string = "";
            if(!empty($file_network_folder)){
                $file_network_location_string = "<br><b>Location:</b> " . $file_network_folder;
            }
            $asset_item_name_string = "";


            if ($file_active == 1){
                $final_current_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a>" . $asset_item_name_string . $file_network_location_string  . "</td><td>".date("m/d/Y H:m",strtotime($file_upload_date))."</td><td>" . $notes_field . "</td><td><a href =\"del_mri.php?a=2&f=" . $file_type . "&pfid=" . $project_file_id . "&id=" . $project_id ."\">archive</a></td></tr>";
                $approval_document_select .= "<option value = \"" .  $project_file_id . "\">FINAL - " . $file_name . "</option>\n";
            }else{
                $final_archive_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>".date("m/d/Y H:m",strtotime($file_upload_date))."</td><td>" . $file_notes . "</td><td><a href =\"del_mri.php?a=1&f=" . $file_type . "&pfid=" . $project_file_id . "&id=" . $project_id ."\">activate</a></td></tr>";

            }
        }


        //handle rounds, which are more complicated.
        if ($file_type[0] == "R"){
            $has_rounds = 1;
            $current_round = substr($file_type, 1);
            if ($prev_round <> $current_round){

                if($prev_round <> 0){
                    //print "Round " . $current_round . "<br>";
                    //if the prev round is not zero, close the tables and add to the round_area

                    $round_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup1','" . $popup_project_name . "','" . $project_id . "','R" . $prev_round . "')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
                    $round_archive_table .= "</table>";

                    $container_table = str_replace("##CURRENT_TABLE##", $round_current_table, $container_table);
                    $container_table = str_replace("##ARCHIVE_TABLE##", $round_archive_table, $container_table);
                    $round_area .= $container_table . "&nbsp;";
                }

                //create container table and the two main tables for this round
                $container_table = "<table class = \"file_main\" width = \"80%\"><tr><th>Round " . $current_round . " Current</th><th>Round " . $current_round . " Archived</th></tr><tr><td width = \"50%\" valign=\"top\">##CURRENT_TABLE##</td><td width = \"50%\" valign=\"top\">##ARCHIVE_TABLE##</td></tr></table>";
                $round_current_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";
                $round_archive_table = "<table class = \"file_list\" width = \"100%\"><tr><th align=\"left\">File</th><th align=\"left\">Notes</th><th>&nbsp;</th></tr>\n";

            }
            //add files to these containers
            if ($file_active == 1){
                $round_current_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $notes_field . "</td><td><a href =\"del_mri.php?a=2&f=" . $file_type . "&pfid=" . $project_file_id . "&id=" . $project_id ."\">archive</a></td></tr>";
                $approval_document_select .= "<option value = \"" .  $project_file_id . "\">" . $file_type . " - " . $file_name . "</option>\n";
            }else{
                $round_archive_table .= "<tr><td><a href = \"" . $file_location . "\" target = \"_blank\">" . $file_name . "</a></td><td>" . $file_notes . "</td><td><a href =\"del_mri.php?a=1&f=" . $file_type . "&pfid=" . $project_file_id . "&id=" . $project_id ."\">activate</a></td></tr>";
            }

            $prev_round = $current_round;
        }
    }
    $approval_document_select .= "</select>";
}


$legal_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup1','" . $arr_project[0]["code"] . "','" . $project_id . "','Legal')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
$legal_archive_table .= "</table>";

$final_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup4','" . $arr_project[0]["code"] . "','" . $project_id . "','Final')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
$final_archive_table .= "</table>";

if ($has_rounds == 1){
    //handle the final round
    $round_current_table .= "<tr><td colspan = \"3\" align = \"left\"><a href=\"#\" onclick=\"openpopup('popup1','" . $popup_project_name . "','" . $project_id . "','R" . $current_round . "')\">add</a><div class = \"error\">" . $file_error . "</div></td></tr></table>";
    $round_archive_table .= "</table>";

    $container_table = str_replace("##CURRENT_TABLE##", $round_current_table, $container_table);
    $container_table = str_replace("##ARCHIVE_TABLE##", $round_archive_table, $container_table);
    $round_area .= $container_table;

}

//no matter what, show the next round link:
$round_area .= "<table class = \"file_main\"><tr><th>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"#\" onclick=\"openpopup('popup1','" . $arr_project[0]["code"] . "','" . $project_id . "','R" . ($current_round + 1) . "')\">Add Round " . ($current_round + 1) . " File</a><div class = \"error\">" . $file_error . "</div></th></tr></table>";


//build phase and project table
$phase_and_project_table = "<table class = \"schedule_container\" width = \"100%\"><tr><td><table class = \"budget\" width = \"100%\">";
$arr_projects_and_phases = get_project_phases_and_schedules($project_id);
//print_r($arr_projects_and_phases);
$current_phase = "";
$next_phase_id = "";
$i=0;
$approval_table_top = "<table class= \"budget\"><tr><th colspan = \"6\">Approvals</th></tr><tr><th>Schedule</th><th>Task</th><th>Status</th><th>Due</th><th>Notes</th><th>History</th></tr>";
$approval_count = 0;
if (!empty($arr_projects_and_phases)){
    foreach ($arr_projects_and_phases as $schedule_row){
        $schedule_down_arrow = "";
        $schedule_up_arrow = "";
        $schedule_id = $schedule_row["schedule_id"];
        $schedule_name = $schedule_row["schedule_name"];
        $schedule_phase_order = $schedule_row["schedule_phase_order"];
        $fast_track_status = $schedule_row["fast_track_status"];
        $phase_id = $schedule_row["phase_id"];
        $phase_name = $schedule_row["phase_name"];

        //print $phase_name . "--" . $schedule_name;
        if (empty($phase_name)){
            $phase_name = "No phase";
        }
        if($current_phase <> $phase_id){
            //add phase header row
            $phase_and_project_table .= "<tr><th colspan = \"7\" align=\"left\">Phase: " . $phase_name . "</th></tr>";
        }


        if ($fast_track_status  == 1){
            $fast_track_button = "<a href = \"toggle_fasttrack.php?a=2&s=" . $schedule_id . "&p=". $project_id . "\">stop fast track</a>";
        }else{
            $fast_track_button = "<a href = \"toggle_fasttrack.php?a=1&s=" . $schedule_id . "&p=". $project_id . "\">start fast track</a>";
        }
        $phase_and_project_table .= "<tr><td width = \"30\" align=\"right\" valign=\"top\"><b>" . $schedule_phase_order ."</b></td><td><b>Schedule: " . $schedule_name . "</b></td>";

        if ($_SESSION["user_level"] > 10){
            $phase_and_project_table .= "<td><a href = \"manage_schedules.php?p=" . $project_id . "&s=" . $schedule_id . "\">manage schedule</a></td><td><a href = \"manage_tasks.php?p=" . $project_id . "&s=" . $schedule_id . "\">manage tasks</a></td><td><a href = \"shift_schedule_tasks.php?p=" . $project_id . "&s=" . $schedule_id . "\">shift schedule</a></td><td>" . $fast_track_button  . "</td><td nowrap><a href = \"export_schedule_csv.php?s=" . $schedule_id . "\">save csv</a></td></tr>";
            $current_phase = $phase_id;
        }else{
            $phase_and_project_table .= "<td colspan = \"6\">&nbsp;</td>";
        }
        //Build task table

        $task_table = "<table width = \"100%\" class = \"task_table\"><tr><th>Order</th><th>Task</th><th>Manager</th><th>Start</th><th>End</th><th>Hours</th><th>Mins</th><th>Progress</th><th>Complete?</th><th>Assignee(s)</th><th>Approval</th><th>Cal</th>";

        if ($fast_track_status  == 1){
            $task_table .= "<th>Fast Track</th>";
        }

        $task_table .= "</tr>";
        $arr_schedule_tasks = get_schedule_tasks($schedule_id);
        $n=0;
        //print_r($arr_schedule_tasks);
        if (!empty($arr_schedule_tasks)){
            foreach ($arr_schedule_tasks as $task_row){
                $task_down_arrow = "";
                $task_up_arrow = "";
                $display_order = $task_row["display_order"];
                $schedule_task_id = $task_row["schedule_task_id"];
                $task_name = $task_row["task_name"];
                $manager_name = $task_row["initials"];
                $is_approval = $task_row["is_approval"];
                $is_approved = $task_row["is_approved"];
                $approver_initials = $task_row["approver"];
                $approval_notes = $task_row["approval_notes"];
                $is_current_task = $task_row["is_current_task"];
                $start_date = translate_mysql_todatepicker($task_row["start_date"]);
                $end_date = translate_mysql_todatepicker($task_row["end_date"]);
                $estimated_hours = $task_row["estimated_hours"];
                $complete = $task_row["complete"];
                $approval_string = "&nbsp;";

                //needs this format: 20130731
                $arr_calendar_start_date = explode("-", $task_row["start_date"]);
                //print_r($arr_calendar_start_date) . "<br>";
                $calendar_start_date = $arr_calendar_start_date[0] . $arr_calendar_start_date[1] . $arr_calendar_start_date[2];

                $arr_calendar_end_date = explode("-", $task_row["end_date"]);
                $calendar_end_date = $arr_calendar_end_date[0] . $arr_calendar_end_date[1] . $arr_calendar_end_date[2];

                $calendar_decription = "Project: " . $project_code . " - " . $project_name . "*";
                $calendar_decription .= "Task: " . $task_name . "*";
                $calendar_decription .= "PM: " . $project_manager . "*";
                $calendar_decription .= "Start Date: " . $start_date. "*";
                $calendar_decription .= "Due Date: " . $end_date. "**";
                //$calendar_decription .= "<a href = \"close_task.php?stid=" . $schedule_task_id . "&p=" . $project_id . "&s=" . $schedule_id . "&ft=" . $is_current_task . "\">close task</a>";

                if ($complete == 1){
                    $complete_string = "yes";
                }else{
                    $complete_string = "no";
                    if ($_SESSION["user_level"] > 10){
                        $complete_string .= " (<a href = \"close_task.php?stid=" . $schedule_task_id . "&p=" . $project_id . "&s=" . $schedule_id . "&ft=" . $is_current_task . "\">close</a>)";

                    }

                }
                $assignee_list = get_assignee_initials($schedule_task_id);
                list($hours, $minutes, $seconds) = explode(":", $estimated_hours);
                $progress = $task_row["progress"];

                $send_string = "(<a href=\"#\" onclick=\"openpopup2('popup2','" . $schedule_name . "','" . $task_name . "','" . $assignee_list . "','" . $schedule_task_id . "')\">send</a>)";

                if($assignee_list=="Nobody Assigned."){
                    $send_string = "(<a href = \"manage_tasks.php?p=" . $project_id . "&s=" . $schedule_id . "\">assign</a>)";
                }

                if ($is_approval == 1){
                    $approval_string = "Approval pending - " . $assignee_list . " ". $send_string ;
                    $approval_history_table = "";
                    $history_link = "";
                    if ($is_approved == 1){
                        $approval_date =$task_row["approval_date"];
                        $approval_string = "Approved by " . $approver_initials . ":<br>" . $approval_date;
                        $approval_history_table = get_approval_history_table($schedule_task_id, $project_code);
                        $history_link = "<a id = \"stid" . $schedule_task_id . "\" href = \"#\" class=\"view_approval_history_click\">view</a>";
                    }elseif($is_approved == 2){
                        $approval_date =$task_row["approval_date"];
                        $approval_string = "<div class = \"error\">NOT APPROVED by " . $approver_initials . ":<br>" . $approval_date . " " . $send_string . "</div>";
                        $approval_history_table = get_approval_history_table($schedule_task_id, $project_code);
                        $history_link = "<a id = \"stid" . $schedule_task_id . "\" href = \"#\" class=\"view_approval_history_click\">view</a>";
                    }
                    $approval_table_top .= "<tr><td>" . $schedule_name . "</td><td>" . $task_name . "</td><td>" . $approval_string . "</td><td>" . $end_date . "</td><td>" . $approval_notes . "</td><td>" . $history_link . $approval_history_table . "</td></tr>";
                    $approval_count ++;
                }


                if (!empty($arr_schedule_tasks[$n+1]["display_order"])){
                    $next_task_order = $arr_schedule_tasks[$n+1]["display_order"];
                }else{
                    $next_task_order = "0";
                }

                if ($display_order <> 1){
                    $swap1 = $display_order;
                    $swap2 = $display_order - 1;
                    $task_up_arrow = "<a href = \"move_schedule_task.php?s=" . $schedule_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_up.png\" border=\"0\"></a>";
                }
                if ($display_order < $next_task_order){
                    $swap1 = $display_order;
                    $swap2 = $display_order + 1;
                    $task_down_arrow = "<a href = \"move_schedule_task.php?s=" . $schedule_id . "&s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_down.png\" border=\"0\"></a>";
                }

                $task_class = "";
                $todays_date = date("m/d/Y");



                if ($is_current_task == 1){
                    $task_class = "current_task";
                }

                if ($complete == 0){
                    if (strtotime($todays_date) > strtotime($end_date)){
                        $task_class = "late";
                        if ($is_current_task == 1){
                            $task_class = "current_task_late";
                        }
                    }
                }


                $task_table .= "<tr class = \"task_row\">";
                $task_table .= "<td class = \"" . $task_class . "\" align=\"right\">" . $display_order . "</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\">" . $task_name . "</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\">" . $manager_name . "</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"right\">" . $start_date  . "</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"right\">" . $end_date  . "</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"right\">" . $hours  . "</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"right\">" . $minutes  . "</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"right\">" . $progress  . "%</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\">" . $complete_string  . "</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\">" . $assignee_list  . "</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\">" . $approval_string  . "</td>";
                $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\"><a href = \"add_outlook_meeting.php?date=" . $calendar_start_date . "&amp;startTime=1600&amp;endTime=1700&amp;subject=" . $project_code . " - " . $task_name . "&amp;desc=" . $calendar_decription . "\" border=\"0\"><img src = \"images/sm_calendar_icon.png\"></a></td>";

                //$task_table .= "<td>" . $task_up_arrow  . "</td>";
                //$task_table .= "<td>" . $task_down_arrow  . "</td>";
                if ($_SESSION["user_level"] > 10){
                    if ($is_current_task  == 1){
                        $task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\"><a href = \"fast_track_complete_send.php?stid=" . $schedule_task_id . "&s=" . $schedule_id . "&p=" . $project_id . "\">complete/send</td>";
                    }else{
                        $task_table .= "";
                    }
                }else{
                    //$task_table .= "<td class = \"" . $task_class . "\" valign = \"top\" align=\"left\">&nbsp;</td>";
                    $task_table .= "";
                }

                $task_table .= "</tr>";
                $n++;
            }
        }else{
            $task_table .= "<tr><td colspan = \"12\">No tasks</td></tr>";

        }

        $task_table .= "</table>";
        $phase_and_project_table .= "<tr><td>&nbsp;</td><td colspan = \"6\">" . $task_table . "</td></tr>";



        $i++;
    }

}else{
    $phase_and_project_table .= "<tr><td colspan = \"4\">No schedules.</td></tr>";
}

$phase_and_project_table .= "</table></td></tr></table>";

if ($approval_count == 0){
    $approval_table_top .= "<tr><td colspan = \"6\">No approvals for this project</td></tr>";
}

$approval_table_top .= "</table>";

$arr_states = get_states();
$js_all_states_array = "var arr_all_states = ['NAT',";
if(!empty($arr_states)){
    foreach ($arr_states as $state_row){
        $state_id = $state_row["state_id"];
        $state_name = $state_row["state_name"];
        $state_abbrev = $state_row["state_abbrev"];
        if ($state_abbrev <> "NAT"){
            $js_all_states_array .= "\"" . $state_abbrev . "\",";
        }

    }
    //delete the last comma
    $js_all_states_array = substr($js_all_states_array, 0, -1);
}
$js_all_states_array .= "];";

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
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
    <script type="text/javascript">
        $( ".datepicker" ).datepicker();
        $(document).ready(function(){
            $("#spend_form").validate({
                rules: {
                    percent_complete: {
                        required: true,
                        max: 100,
                        min: 0,
                        number: true
                    },
                    cost_expense_account: {
                        required: true,
                        minlength: 21
                    }

                }

            });
            $('[id^=noneditable_]').click(function(){
                var name=this.getAttribute('rel');
                $('#noneditable_'+name).hide();
                $('#editable_'+name).show();
                $('#input_'+name).focus();
            });

            $('[id^=input_]').blur(function(){
                var id=this.getAttribute('title');
                var name=this.getAttribute('rel');
                var val=this.value;
                var isSelect=false;
                if ($(this).prop('tagName')=='SELECT'){
                    isSelect=true;
                    var selected=$(this).find('option:selected').attr('title');
                }

                else {}
                var params = {
                    request:"Update mri",
                    id: id,
                    field:name,
                    value:val
                };
                $.ajax({
                    url: 'mri_handler.php',
                    global: false,
                    type: "POST",
                    data: params,
                    dataType:"text",
                    success: function (result) {
                        if (isSelect){
                            $('#noneditable_'+name).text(selected);
                            }
                        else {
                            $('#noneditable_'+name).text(val);
                        }
                        this.value=val  ;
                        $('#noneditable_'+name).append( "<span style='left:5px;' class='fa fa-pencil' id='noneditable_'" + name + "></span>");
                    }
                });
                $('#noneditable_'+name).show();
                $('#editable_'+name).hide();
            });
            $('.state_click').click(function() {

                var aaid = $(this).attr("aaid");

                //get the proper state list
                var arr_state_list = allStatesObj["states_" + aaid];
                //alert(arr_state_list);
                var arrayLength = arr_all_states .length;
                var strStateForm = "";
                for (var i = 0; i < arrayLength; i++) {
                    var current_state_abbrev = arr_all_states[i];
                    strStateForm = strStateForm + "<input type = 'checkbox' class = 'chk_state' name = 'chk_" + current_state_abbrev + "' value = '1' ";
                    //if the current state is in the state list...
                    if(jQuery.inArray(current_state_abbrev, arr_state_list)!==-1){
                        strStateForm  = strStateForm  + " checked";
                    }
                    strStateForm  = strStateForm  + "> " + current_state_abbrev + "<br>\n";
                }
                //$('#state_list').html(strStateForm);
                $('#edit_states_checkboxes_' + aaid).html(strStateForm);
                $('#state_list_' + aaid).toggle();
                $('#edit_states_' + aaid).toggle();
                $('#sel_states_' + aaid).toggle();
                return false;
            });


            $('.sel_states_click').click(function(event) {  //on click
                var aaid = $(this).attr("aaid");
                if(this.checked) { // check select status
                    $('#chk_state_list_' + aaid + ' .chk_state').prop('checked', true);
                }else{
                    $('#chk_state_list_' + aaid + ' .chk_state').prop('checked', false);
                }
            });


            $('.view_approval_history_click').click(function() {
                var click_id = $(this).attr("id");
                var toggle_section = '#approval_history_' + click_id;
                //alert(toggle_section);
                $(toggle_section).toggle();
                return false;
            });

            //spend keyup function - replace cost code underscores with dashes
            $( "#cost_expense_account").keyup(function() {
                var cost_expense_value = $( "#cost_expense_account").val();
                cost_expense_value = cost_expense_value.replace("_","-");
                $("#cost_expense_account").val(cost_expense_value);
                //alert(cost_expense_value);
            });


            $("#add_project").validate();


            $('#add_spend').hide();
            $('#schedule_area').<?php echo $show_schedules ?>();

            $('#file_area').<?php echo $show_files ?>();





            $('#file_area_click').click(function() {
                $('#file_area').toggle();
                return false;
            });
            $('#schedule_area_click').click(function() {
                $('#schedule_area').toggle();
                return false;
            });
            $('#add_spend_click').click(function() {
                $('#add_spend').toggle();
                return false;
            });
            $( ".datepicker" ).datepicker();

            hide_all_files();

            <?php
                if ($show_legal == "show"){
                    echo "$(\"#legal\").show();";
                    echo "$(\"#legal_link\").toggleClass(\"file_nav_selected\");\n";
                }elseif ($show_studio == "show"){
                    echo "$(\"#studio\").show();";
                    echo "$(\"#studio_link\").toggleClass(\"file_nav_selected\");\n";
                }elseif ($show_financial == "show"){
                    echo "$(\"#financial\").show();";
                    echo "$(\"#financial_link\").toggleClass(\"file_nav_selected\");\n";
                }elseif ($show_final == "show"){
                    echo "$(\"#final\").show();";
                    echo "$(\"#final_link\").toggleClass(\"file_nav_selected\");\n";
                }elseif ($show_cr == "show"){
                    echo "$(\"#cr\").show();";
                    echo "$(\"#cr_link\").toggleClass(\"file_nav_selected\");\n";
                }elseif ($show_cb == "show"){
                    echo "$(\"#cb\").show();";
                    echo "$(\"#cb_link\").toggleClass(\"file_nav_selected\");\n";
                }else{
                    echo "$(\"#pif\").show();\n";
                    echo "$(\"#pif_link\").toggleClass(\"file_nav_selected\");\n";
                }
            ?>
            $(".file_nav_link").click(function(){
                var getName = $(this).attr("name");
                var getID = $(this).attr("id");
                hide_all_files();
                $("#" + getName).fadeIn("slow");
                $("#" + getID).toggleClass("file_nav_selected");
                return false;

            });

            $("#vendor_select").change(function(){
                vendor_value = $( "#vendor_select option:selected" ).text();

                if(vendor_value == "_Other"){
                    //alert(vendor_value);
                    $("#vendor_other").show();
                }else{
                    //alert("hide");
                    $("#vendor_other").hide();
                }
            });

            function hide_all_files(){
                $(".file_section").hide();
                $(".file_nav_link").removeClass("file_nav_selected");
            }

        });
    </script>

    <script language="javascript">
        function openpopup(id,project_name,project_id,file_type){
            //Calculate Page width and height

            var pageWidth = window.innerWidth;
            var pageHeight = window.innerHeight;
            if (typeof pageWidth != "number"){
                if (document.compatMode == "CSS1Compat"){
                    pageWidth = document.documentElement.clientWidth;
                    pageHeight = document.documentElement.clientHeight;
                } else {
                    pageWidth = document.body.clientWidth;
                    pageHeight = document.body.clientHeight;
                }
            }
            //Make the background div tag visible...
            var divbg = document.getElementById('bg');
            divbg.style.visibility = "visible";

            var divobj = document.getElementById(id);
            divobj.style.visibility = "visible";
            if (navigator.appName=="Microsoft Internet Explorer")
                computedStyle = divobj.currentStyle;
            else computedStyle = document.defaultView.getComputedStyle(divobj, null);
            //Get Div width and height from StyleSheet
            var divWidth = computedStyle.width.replace('px', '');
            var divHeight = computedStyle.height.replace('px', '');
            var divLeft = (pageWidth - divWidth) / 2;
            var divTop = (pageHeight - divHeight) / 2;
            //Set Left and top coordinates for the div tag
            divobj.style.left = divLeft + "px";
            divobj.style.top = divTop + "px";
            //Put a Close button for closing the popped up Div tag
            if(divobj.innerHTML.indexOf("closepopup('" + id +"')") < 0 )
                divobj.innerHTML = "<a href=\"#\" onclick=\"closepopup('" + id +"')\"><span class=\"close_button\">X</span></a>" + divobj.innerHTML;
            document.getElementById('project_id_pop1').value=project_id;
            document.getElementById('project_id_pop4').value=project_id;
            document.getElementById('file_type').value=file_type;
            document.getElementById('file_type_id_pop4').value=file_type;
            document.getElementById('pname').innerHTML=project_name;
            document.getElementById('pname4').innerHTML=project_name;
            document.getElementById('file_type_text').innerHTML=file_type;
            document.getElementById('file_type_text4').innerHTML=file_type;

        }

        function openpopup2(id,schedule_name,task_name,user_initials,schedule_task_id){
            //Calculate Page width and height
            var pageWidth = window.innerWidth;
            var pageHeight = window.innerHeight;
            if (typeof pageWidth != "number"){
                if (document.compatMode == "CSS1Compat"){
                    pageWidth = document.documentElement.clientWidth;
                    pageHeight = document.documentElement.clientHeight;
                } else {
                    pageWidth = document.body.clientWidth;
                    pageHeight = document.body.clientHeight;
                }
            }
            //Make the background div tag visible...
            var divbg = document.getElementById('bg');
            divbg.style.visibility = "visible";

            var divobj = document.getElementById(id);
            divobj.style.visibility = "visible";
            if (navigator.appName=="Microsoft Internet Explorer")
                computedStyle = divobj.currentStyle;
            else computedStyle = document.defaultView.getComputedStyle(divobj, null);
            //Get Div width and height from StyleSheet
            var divWidth = computedStyle.width.replace('px', '');
            var divHeight = computedStyle.height.replace('px', '');
            var divLeft = (pageWidth - divWidth) / 2;
            var divTop = (pageHeight - divHeight) / 2;
            //Set Left and top coordinates for the div tag
            divobj.style.left = divLeft + "px";
            divobj.style.top = divTop + "px";
            //Put a Close button for closing the popped up Div tag
            if(divobj.innerHTML.indexOf("closepopup('" + id +"')") < 0 )
                divobj.innerHTML = "<a href=\"#\" onclick=\"closepopup('" + id +"')\"><span class=\"close_button\">X</span></a>" + divobj.innerHTML;
            document.getElementById('schedule_name').innerHTML=schedule_name;
            document.getElementById('task_name').innerHTML=task_name;
            document.getElementById('user_initials').value=user_initials;
            document.getElementById('schedule_task_id').value=schedule_task_id;
        }


        function openpopup3(id,project_file_id,file_notes, file_type, file_name, project_id){
            //alert("foo");
            //Calculate Page width and height
            var pageWidth = window.innerWidth;
            var pageHeight = window.innerHeight;
            if (typeof pageWidth != "number"){
                if (document.compatMode == "CSS1Compat"){
                    pageWidth = document.documentElement.clientWidth;
                    pageHeight = document.documentElement.clientHeight;
                } else {
                    pageWidth = document.body.clientWidth;
                    pageHeight = document.body.clientHeight;
                }
            }
            //Make the background div tag visible...
            var divbg = document.getElementById('bg');
            divbg.style.visibility = "visible";

            var divobj = document.getElementById(id);
            divobj.style.visibility = "visible";
            if (navigator.appName=="Microsoft Internet Explorer")
                computedStyle = divobj.currentStyle;
            else computedStyle = document.defaultView.getComputedStyle(divobj, null);
            //Get Div width and height from StyleSheet
            var divWidth = computedStyle.width.replace('px', '');
            var divHeight = computedStyle.height.replace('px', '');
            var divLeft = (pageWidth - divWidth) / 2;
            var divTop = (pageHeight - divHeight) / 2;
            //Set Left and top coordinates for the div tag
            divobj.style.left = divLeft + "px";
            divobj.style.top = divTop + "px";
            //Put a Close button for closing the popped up Div tag
            if(divobj.innerHTML.indexOf("closepopup('" + id +"')") < 0 )
                divobj.innerHTML = "<a href=\"#\" onclick=\"closepopup('" + id +"')\"><span class=\"close_button\">X</span></a>" + divobj.innerHTML;
            document.getElementById('pop3_project_file_id').value=project_file_id;
            document.getElementById('pop3_file_notes').value=file_notes;
            document.getElementById('pop3_file_type').value=file_type;
            document.getElementById('pop3_project_id').value=project_id;
            document.getElementById('pop3_file_name').innerHTML=file_name;


        }


        function closepopup(id){
            var divbg = document.getElementById('bg');
            divbg.style.visibility = "hidden";
            var divobj = document.getElementById(id);
            divobj.style.visibility = "hidden";
        }
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

            <div id="mainContent"> <!--mainContent div tag-->
                <div class = "section_area">
                    <div class = "section_header">

                        <!--project header-->
                        <table width = "100%">
                            <tr>
                                <td>
                                    Project <?php echo $arr_project[0]["code"] ?> -
                                    <?php
                                    if (isset($arr_project[0]["title"])){
                                        echo $arr_project[0]["title"];
                                    }else if (isset($arr_project[0]["pic_name"])){
                                        echo $arr_project[0]["pic_name"];
                                    }
                                    ?>
                                </td>
                                <td align = "right">
                                    <?php
                                    if ($_SESSION["user_level"] >= 20){
                                        ?>
                                        <a href = "edit_mri.php?id=<?php echo $project_id ?>">edit</a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>


                    </div>
                    <table width = "40%" border = "0">
                        <tr>
                            <td valign="top"><!--project info-->
                                <?php echo $project_table  ?><br>
                            </td>
                        </tr>

                    </table>
                </div><!--end section_area div tag-->



                <!--file area-->
                <a name = "files"></a>
                <div class = "section_area">
                    <div class = "section_header">

                        <!--area header-->
                        <table width = "100%">
                            <tr>
                                <td>
                                    <a href="#" id="file_area_click">Project Files</a>
                                </td>
                                <td align = "right">
                                    &nbsp;
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id = "file_area">
                        <div class = "error"><?php echo $file_error_message ?></div>
                        <div id = "file_nav">
                            <ul class="file_nav_ul">
                                <li><a class = "file_nav_link file_nav_selected" id = "legal_link" name = "legal" href = "#files">Legal</a></li>
                                <li><a class = "file_nav_link" id = "final_link" name = "final" href = "#files">Final</a></li>
                            </ul>
                        </div>
                        <div class = "file_container">
                            <div class = "file_section" id = "legal">
                                <table class = "file_main" width = "80%">
                                    <tr>
                                        <th>Current</th>
                                        <th>Archived</th>
                                    </tr>
                                    <tr>
                                        <td width = "50%" valign="top">
                                            <?php echo $legal_current_table ?>
                                        </td>
                                        <td width = "50%" valign="top">
                                            <?php echo $legal_archive_table ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class = "file_section" id = "final">
                                <table class = "file_main" width = "80%">
                                    <tr>
                                        <th>Current</th>
                                        <th>Archived</th>
                                    </tr>
                                    <tr>
                                        <td width = "50%" valign="top">
                                            <?php echo $final_current_table ?>
                                        </td>
                                        <td width = "50%" valign="top">
                                            <?php echo $final_archive_table ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- end file area-->




            </div> <!--end mainContent div tag-->

        </div>

    </div>

</div>

<div id="popup1" class="popup">
    <form id = "add_file" action = "add_mri_file.php" method = "POST" enctype="multipart/form-data" class="budget">
        <table border = "0">
            <tr>
                <td>
                    Project:
                </td>
                <td>
                    <div id = "pname">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    File Type:
                </td>
                <td>
                    <div id = "file_type_text">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>Select File:</td>
                <td><input type="file" name="file" id="file">
                </td>
            </tr>
            <tr>
                <td>File Notes:</td>
                <td>
                    <input class = "required" type = "text" name = "file_notes">
                </td>
            </tr>
            <tr>
                <td colspan = "2">
                    <input id = "project_id_pop1" type = "hidden" name = "project_id" value = "">
                    <input id = "file_type" type = "hidden" name = "file_type" value = "">
                    <input type = "submit" value = "add file">
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="bg" class="popup_bg"></div>


<div id="popup2" class="popup">
    <form id = "approval_email" action = "send_approval_email.php" method = "POST" class="budget">
        <table border = "0">
            <tr>
                <td>
                    Project:
                </td>
                <td>
                    <?php echo $project_code . " - " . $project_name ?>
                </td>
            </tr>
            <tr>
                <td>
                    Schedule:
                </td>
                <td>
                    <div id = "schedule_name">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>Task:</td>
                <td>
                    <div id = "task_name">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>Approve Specific Document?</td>
                <td>
                    <?php echo $approval_document_select ?>
                </td>
            </tr>
            <tr>
                <td>Comment:</td>
                <td>
                    <textarea name = "comment" cols = "40">Your approval is required for this task.</textarea>
                </td>
            </tr>
            <tr>
                <td colspan = "2">
                    <input id = "user_initials" type = "hidden" name = "user_initials" value = "">
                    <input id = "schedule_task_id" type = "hidden" name = "schedule_task_id" value = "">
                    <input type = "submit" value = "Send">
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="popup3" class="popup">
    <form id = "edit_file_notes" action = "update_file_notes_mri.php" method = "POST" class="budget">
        <table border = "0">
            <tr>
                <td>
                    File:
                </td>
                <td>
                    <div id = "pop3_file_name">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    Notes:
                </td>
                <td>
                    <input type = "text" id = "pop3_file_notes" name = "file_notes" value = "">
                </td>
            </tr>

            <tr>
                <td colspan = "2">
                    <input id = "pop3_project_file_id" type = "hidden" name = "project_file_id" value = "">
                    <input id = "pop3_file_type" type = "hidden" name = "file_type" value = "">
                    <input id = "pop3_project_id" type = "hidden" name = "project_id" value = "">
                    <input type = "submit" value = "update">
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="popup4" class="popup">
    <form id = "add_file" action = "add_mri_file.php" method = "POST" enctype="multipart/form-data" class="budget">
        <table border = "0">
            <tr>
                <td>
                    Project:
                </td>
                <td>
                    <div id = "pname4">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    File Type:
                </td>
                <td>
                    <div id = "file_type_text4">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>Select File:</td>
                <td><input type="file" name="file" id="file">
                </td>
            </tr>
            <tr>
                <td>File Notes:</td>
                <td>
                    <input class = "required" type = "text" name = "file_notes">
                </td>
            </tr>


            <tr>
                <td colspan = "2">
                    <input id = "project_id_pop4" type = "hidden" name = "project_id" value = "">
                    <input id = "file_type_id_pop4" type = "hidden" name = "file_type" value = "">
                    <input type = "submit" value = "add file">
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="bg" class="popup_bg"></div>
</body>
</html>
