<?php 
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
//print $company_id;
$error_message = "";

if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "An error occurred.";
	}
		if ($error_num == 2){
		$error_message = "Spend Updated.";
	}
		if ($error_num == 3){
		$error_message = "Month/year already exists.";
	}
}
$project_id = "";
if (!empty($_GET["p"])){
	$project_id = $_GET["p"];
}

$spend_id = "";
if (!empty($_GET["s"])){
	$spend_id = $_GET["s"];
}

$arr_project_info = get_project_info($project_id);
//print_r($arr_phase_info );
$project_name = $arr_project_info[0]["project_name"];
$project_code = $arr_project_info[0]["project_code"];

$arr_spend = get_spend_info($spend_id);
$spend_type = $arr_spend[0]["spend_type"];
$spend_amount = $arr_spend[0]["spend_amount"];
$invoice_number = $arr_spend[0]["invoice_number"];
$po_number = $arr_spend[0]["po_number"];
$percent_complete = $arr_spend[0]["percent_complete"];
$spend_notes = $arr_spend[0]["spend_notes"];
$percent_complete = $arr_spend[0]["percent_complete"];
$cost_expense_account = $arr_spend[0]["cost_expense_account"];

$vendor_id = $arr_spend[0]["vendor_id"];
$vendor_other = $arr_spend[0]["vendor_other"];
$asset_id = $arr_spend[0]["asset_id"];
$media_checked = "";
$production_checked = "";
$other_checked = "";
//print $spend_type;
if ($spend_type == "Media"){
	$media_checked = "selected";
}
if ($spend_type == "Production"){
	$production_checked = "selected";
}
if ($spend_type == "Other"){
	$other_checked = "selected";
}
//print_r($arr_spend);

$vendor_select = get_vendor_select($company_id, $vendor_id);
$asset_select = get_asset_select($project_id, $asset_id);
$percentage_select = get_percentage_select("percent_complete", $percent_complete);

$spend_percent_table = "<table class = \"form_table\" width = \"100%\">";
$arr_spend_month_percentage = get_spend_month_percentage($spend_id);
$current_month = date("m");
$current_year = date("Y");
$highest_month = $current_month;
$highest_year = $current_year;
$n=0;
//print_r($arr_spend_month_percentage);
if (!empty($arr_spend_month_percentage)){
	foreach ($arr_spend_month_percentage as $spend_month_row){
		$spend_percent_id = $spend_month_row["spend_percent_id"];
		$spend_month = $spend_month_row["spend_month"];
		$date_array = explode('-', $spend_month);
		$spend_month_number = $date_array[1];
		$spend_year = $date_array[0];
		if ($n==0){
			$highest_month = $spend_month_number;
			$highest_year = $spend_year;
		}
		
		$spend_percent = $spend_month_row["spend_percent"];
		
		$month_name = date("F", mktime(0, 0, 0, $spend_month_number, 10));
		//$current_percentage_select = get_percentage_select("spend_percent", $spend_percent);
		$spend_percent_table .= "<tr><td>";
		$spend_percent_table .= "<form class = \"update_spend_month\" action = \"update_spend_month.php\" method = \"POST\">";
		$spend_percent_table .= "<table border = \"0\"><tr><td width = \"90\">" . $month_name . "</td><td width = \"75\">" . $spend_year . "</td><td nowrap width = \"75\"><input type = \"text\" class = \"required number\" name = \"percent_complete\" size = \"2\" maxlength=\"3\" value = \"" . $spend_percent . "\">%</td><td><input type = \"hidden\" name = \"spend_percent_id\" value = \"" . $spend_percent_id . "\"><input type = \"hidden\" name = \"spend_id\" value = \"" . $spend_id . "\"><input type = \"hidden\" name = \"project_id\" value = \"" . $project_id . "\"><input type = \"submit\"  value = \"update\"></td></tr></table>";
		$spend_percent_table .= "</form>\n";
		$spend_percent_table .= "</td></tr>";
		$n++;
	}
}else{
	$spend_percent_table .= "<tr><td colspan = \"2\">Please add a spend percent.</td></tr>\n";
}


$select_month = $highest_month + 1;
$select_year = $highest_year;
if ($select_month == 13){
	$select_month = 1;
	$select_year = $select_year + 1;
}

$month_select = get_month_select($select_month);
$year_select = get_year_select_no_all($select_year);

$spend_percent_table .= "<tr><td><form id = \"add_spend_month\" action = \"add_spend_month.php\" method = \"POST\"><table border = \"0\"><tr><td colspan = \"4\"><br><b>New Spend Month/Percentage</b></td></tr>\n";
$spend_percent_table .= "<tr><td width = \"90\">" . $month_select . "</td><td width = \"75\">" . $year_select . "</td><td nowrap width = \"75\"><input class = \"required\" type = \"text\" size = \"2\" name = \"percent_complete\">%<td><input type = \"hidden\" name = \"spend_id\" value = \"" . $spend_id . "\"><input type = \"hidden\" name = \"project_id\" value = \"" . $project_id . "\"><input type = \"submit\"  value = \"add\"></td></tr></table></form></td></tr>\n";


$spend_percent_table .= "</table>\n";

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit Spend</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  //$.noConflict();
  $(document).ready(function(){

	$( "#add_spend_month" ).validate({
	rules: {	
			percent_complete: {
				required: true,
				max: 100,
				min: 0,
				number: true
			}
		}
	});
	
	$( ".datepicker" ).datepicker();

	//var form = $(this);
	$("#spend_form").validate({
	//$(this).validate({
	rules: {	
				cost_expense_account: {
				required: true,
				minlength: 21
			}
		}
	});


	$( "#cost_expense_account").keyup(function() {
		var cost_expense_value = $( "#cost_expense_account").val();
		cost_expense_value = cost_expense_value.replace("_","-");
		$("#cost_expense_account").val(cost_expense_value);
		//alert(cost_expense_value);
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
				<h1>Edit Spend</h1>
				<h3>Project: <a href = "manage_project.php?p=<?php echo $project_id ?>#budget"><?php echo $project_name ?> (<?php echo $project_code ?>)</a></h3>
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "1">
						<tr>
							<td valign="top">
							Edit Spend:<form id = "spend_form" action = "update_spend.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Spend Amount:</td>
									<td><input class = "required" type = "text" name = "spend_amount" value = "<?php echo $spend_amount ?>"></td>
								</tr>
								<tr>
									<td>Spend Type:</td>
									<td>
									<select name = "spend_type" class = "required">
										<option <?php echo $media_checked ?> value = "Media">Media</option>
										<option <?php echo $production_checked ?> value = "Production">Production</option>
										<option <?php echo $other_checked ?> value = "Other">Other</option>
									</select>
									</td>
								</tr>
								<tr>
									<td>Vendor:</td>
									<td>
									<?php echo $vendor_select ?>
									</td>
								</tr>
								<tr>
									<td>Vendor Other:</td>
									<td><input type = "text" name = "vendor_other" value = "<?php echo $vendor_other ?>"></td>
								</tr>
								<tr>
									<td>Asset:</td>
									<td>
									<?php echo $asset_select ?>
									</td>
								</tr>
								<tr>
									<td>Invoice #:</td>
									<td><input type = "text" name = "invoice_number" value = "<?php echo $invoice_number ?>"></td>
								</tr>
								<tr>
									<td>PO #:</td>
									<td><input type = "text" name = "po_number" value = "<?php echo $po_number ?>"></td>
								</tr>
								<tr>
									<td>Notes:</td>
									<td><input type = "text" name = "notes" value = "<?php echo $spend_notes ?>"></td>
								</tr>
								<tr>
									<td>Cost Center:</td>
									<td><input type = "text"  name = "cost_expense_account" id = "cost_expense_account" maxlength="21" size="22" value = "<?php echo $cost_expense_account ?>"></td></td>
								</tr>
								<tr>
									<td colspan = "2">
										<input type = "hidden" name = "spend_id" value = "<?php echo $spend_id ?>">
										<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
										<input type = "submit" value = "Update Spend">
									</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "350" valign="top">
							Spend Month/Percentage
							
									<?php echo $spend_percent_table?><br>
									
						</td>
					</tr>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>