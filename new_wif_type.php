<?php 
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
//print $company_id;
$error_message = "";
$active_flag = 1;
if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "WIF Type name or abbreviation exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "WIF Type Added.";
	}
}

$edit_mode=0;
$edit_type = "";
$edit_name = "";
$current_wtid = 0;
if (!empty($_GET["wtid"])){
	$edit_mode = 1;
	$current_wtid=$_GET["wtid"];
}

$arr_wif_type = get_wif_types($company_id, 1);
$wif_type_table = "<table width = \"95%\" class = \"budget\"><tr><th colspan = \"8\">Current WIF Types</th></tr>\n";
$wif_type_table .= "<tr><th>WIF Type Name </th><th>WIF Type Abbrev</th><th>Description</th><th>Order</th><th>Web Request?</th><th>Asset Type</th><th colspan = \"2\">&nbsp;</th></tr>\n";
if (!empty($arr_wif_type)){
	foreach ($arr_wif_type as $wif_type_row){
		$wif_type_id = $wif_type_row["wif_type_id"];
		$wif_type_name = $wif_type_row["wif_type_name"];
		$wif_type_abbrev = $wif_type_row["wif_type_abbrev"];
		$wif_type_description = $wif_type_row["wif_type_description"];
		$display_order = $wif_type_row["display_order"];
		$is_web_request = $wif_type_row["is_web_request"];
		$asset_type_id = $wif_type_row["asset_type_id"];
		$is_web_requst_text = "yes";
		$yes_selected = "selected";
		$no_selected = "";
		$asset_type_category_abbrev = $wif_type_row["asset_type_category_abbrev"];
		$asset_type_name = $wif_type_row["asset_type_name"];
		
		if ($is_web_request == 0){
			$is_web_requst_text = "no";
			$no_selected = "selected";
			$yes_selected = "";
		}
		$asset_type_select = get_asset_type_select($company_id, $asset_type_id);
		$asset_type_display_name = $asset_type_category_abbrev . ": " . $asset_type_name;
		if ($asset_type_id == 0){
			$asset_type_display_name = "&nbsp;";
		}
		if ($current_wtid == $wif_type_id){
			$wif_type_table .= "<form method = \"POST\" action = \"update_wif_type.php\"><tr><td><a name = \"wtid" . $wif_type_id . "\"><input type = \"text\" name = \"wif_type_name\" value = \"" . $wif_type_name . "\"></td><td><input type = \"text\" name = \"wif_type_abbrev\" value = \"" . $wif_type_abbrev . "\"></td><td><textarea name = \"wif_type_description\">" . $wif_type_description . "</textarea></td><td><input type = \"text\" name = \"display_order\" value = \"" . $display_order . "\" size = \"2\"></td><td align=\"right\"><select name = \"is_web_request\" class = \"required\"><option value = \"0\" " . $no_selected . ">no</option><option value = \"1\" " . $yes_selected . ">yes</option></select></td><td>" . $asset_type_select . "</td><td colspan = \"2\"><input type = \"hidden\" name = \"wtid\" value = \"" . $wif_type_id . "\"><input type = \"submit\" value = \"update\"></td></tr></form>\n";
		}else{
			$wif_type_table .= "<tr><td>" . $wif_type_name . "</td><td>" . $wif_type_abbrev . "</td><td>" . $wif_type_description . "</td><td align=\"right\">" . $display_order . "</td><td align=\"right\">" . $is_web_requst_text . "</td><td>" . $asset_type_display_name . "</td>";
			$wif_type_table .= "<td><a href = \"new_wif_type.php?wtid=" . $wif_type_id . "#wtid" . $wif_type_id . "\">edit</a></td>";
			$wif_type_table .= "<td><a href = \"activate_wif_type.php?a=2&at=" . $wif_type_id . "\">del</a></td></tr>";
		}
	}
}
$wif_type_table .= "</table>";

$arr_retired_wif_types = get_wif_types($company_id, 2);
$wif_type_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Retired WIF Types</th></tr>";
if (!empty($arr_retired_wif_types)){
	foreach ($arr_retired_wif_types as $wif_type_row){
		$wif_type_id = $wif_type_row["wif_type_id"];
		$wif_type_name = $wif_type_row["wif_type_name"];
		$wif_type_table2 .= "<tr><td>" . $wif_type_name . "</td><td><a href = \"activate_wif_type.php?a=1&at=" . $wif_type_id . "\">activate</a></td></tr>";
	
	}
}
$wif_type_table2 .= "</table>";

$asset_type_select_main = get_asset_type_select($company_id,0); 

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New WIF Type</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#wif_type_form" ).validate({
  rules: {
    task_rate: {
      required: false,
      number: true
    }
  }
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
				<h1>WIF Types</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0" width = "95%">
					<tr>
						<td valign="top">
							<?php echo $wif_type_table ?>
							<br><br>
							<?php echo $wif_type_table2 ?>
						</td>
					</tr>
					<tr>
						<td valign="top">
							<br>New WIF Type:<form id = "wif_type_form" action = "add_wif_type.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>WIF Type Name:</td>
									<td><input class = "required" type = "text" name = "wif_type_name"></td>
								</tr>
								<tr>
									<td>WIF Type Abbrev:</td>
									<td><input class = "required" type = "text" name = "wif_type_abbrev"></td>
								</tr>
								<tr>
									<td>WIF Type Description:</td>
									<td><textarea name = "wif_type_description"></textarea></td>
								</tr>
								<tr>
									<td>Display Order:</td>
									<td><input class = "required number" type = "text" name = "display_order" size = "2"></td>
								</tr>
								<tr>
									<td>Is web request?</td>
									<td><select name = "is_web_request" class = "required"><option value = "0">no</option><option value = "1">yes</option></select></td>
								</tr>
								<tr>
									<td>Asset Type</td>
									<td><?php echo $asset_type_select_main ?></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add WIF Type"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
							
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