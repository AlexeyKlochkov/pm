<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$project_id = $_POST["project_id"];
$vendor_id = $_POST["vendor_id"];
$spend_amount = $_POST["spend_amount"];
$spend_amount = str_replace(",","",$spend_amount);
$spend_type = $_POST["spend_type"];
$asset_id = $_POST["asset_id"];
$notes = $_POST["notes"];
$po_number = $_POST["po_number"];
$invoice_number = $_POST["invoice_number"];
$percent_complete = $_POST["percent_complete"];
$cost_expense_account = $_POST["cost_expense_account"];
$spend_month = $_POST["month"];
$spend_year = $_POST["spend_year"];
$vendor_other = $_POST["vendor_other"];
$spend_date = $spend_year . "-" . $spend_month . "-1";

//$spend_date = convert_datepicker_date($spend_date);
$new_spend_id = add_spend($project_id, $vendor_id, $spend_amount, $spend_type, $asset_id, $notes, $po_number, $invoice_number, $percent_complete, $cost_expense_account, $user_id, $vendor_other);

$insert_initial_spend_success = insert_spend_month_percentage($new_spend_id, $spend_date, $percent_complete);


$location = "Location: manage_project.php?p=" . $project_id . "#budget";

header($location) ;
