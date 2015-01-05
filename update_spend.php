<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";

$project_id = $_POST["project_id"];
$spend_id = $_POST["spend_id"];
$vendor_id = $_POST["vendor_id"];
$vendor_other = $_POST["vendor_other"];
$spend_amount = $_POST["spend_amount"];
$spend_type = $_POST["spend_type"];
$asset_id = $_POST["asset_id"];
$notes = $_POST["notes"];
$po_number = $_POST["po_number"];
$invoice_number = $_POST["invoice_number"];
$percent_complete = $_POST["percent_complete"];
$cost_expense_account = $_POST["cost_expense_account"];

$update_success = update_spend($spend_id, $vendor_id, $spend_amount, $spend_type, $asset_id, $notes, $po_number, $invoice_number, $percent_complete, $cost_expense_account, $vendor_other);

if ($update_success <> 0){
	$location = "Location: edit_spend.php?e=2&p=" . $project_id . "&s=" . $spend_id;
}else{
	$location = "Location: edit_spend.php?e=1&p=" . $project_id . "&s=" . $spend_id;
}


header($location) ;

?>