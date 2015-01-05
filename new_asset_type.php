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
		$error_message = "Asset Type name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Asset Type Added.";
	}
}

$edit_mode=0;
$edit_type = "";
$edit_name = "";
$current_atid = 0;
if (!empty($_GET["atid"])){
	$edit_mode = 1;
	$current_atid=$_GET["atid"];
}

$arr_asset_type = get_asset_types($company_id, 1);
$asset_type_table = "<table width = \"500\" class = \"budget\"><tr><th colspan = \"5\">Current Asset Types</th></tr>\n";
$asset_type_table .= "<tr><th>Asset Type</th><th>Category</th><th>Template</th><th colspan = \"2\">&nbsp;</th></tr>\n";
if (!empty($arr_asset_type)){
	foreach ($arr_asset_type as $asset_type_row){
		$asset_type_id = $asset_type_row["asset_type_id"];
		$asset_type_name = $asset_type_row["asset_type_name"];
		$asset_type_category_id = $asset_type_row["asset_type_category_id"];
		$asset_type_category_name = $asset_type_row["asset_type_category_name"];
		$asset_type_category_abbrev = $asset_type_row["asset_type_category_abbrev"];
		$asset_type_template_name = $asset_type_row["asset_type_template_name"];
		$asset_type_template_id = $asset_type_row["asset_type_template_id"];
		
		if ($current_atid == $asset_type_id){
			$asset_type_table .= "<form method = \"POST\" action = \"update_asset_type.php\"><tr><td><a name = \"atid" . $asset_type_id . "\"><input type = \"text\" name = \"asset_type_name\" value = \"" . $asset_type_name . "\"></td><td align=\"left\">" .   get_asset_type_category_select($company_id, $asset_type_category_id). "</td><td align=\"left\">" .   get_asset_type_template_select($company_id, $asset_type_template_id). "</td><td colspan = \"2\"><input type = \"hidden\" name = \"atid\" value = \"" . $asset_type_id . "\"><input type = \"submit\" value = \"update\"></td></tr></form>\n";
		}else{
			$asset_type_table .= "<tr><td>" . $asset_type_name . "</td><td>" . $asset_type_category_name . "</td><td>" . $asset_type_template_name . "</td>";
			$asset_type_table .= "<td><a href = \"new_asset_type.php?atid=" . $asset_type_id . "#atid" . $asset_type_id . "\">edit</a></td>";
			$asset_type_table .= "<td><a href = \"activate_asset_type.php?a=2&at=" . $asset_type_id . "\">del</a></td></tr>";
		}
	}
}
$asset_type_table .= "</table>";

$arr_retired_asset_types = get_asset_types($company_id, 2);
$asset_type_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Retired Asset Types</th></tr>";
if (!empty($arr_retired_asset_types)){
	foreach ($arr_retired_asset_types as $asset_type_row){
		$asset_type_id = $asset_type_row["asset_type_id"];
		$asset_type_name = $asset_type_row["asset_type_name"];
		$asset_type_table2 .= "<tr><td>" . $asset_type_name . "</td><td><a href = \"activate_asset_type.php?a=1&at=" . $asset_type_id . "\">activate</a></td></tr>";
	
	}
}
$asset_type_table2 .= "</table>";

$asset_type_template_select = get_asset_type_template_select($company_id, 0);

$asset_type_category_select = get_asset_type_category_select($company_id, 0);
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Asset Type</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#asset_type_form" ).validate({
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
				<h1>Asset Types</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Asset Type:<form id = "asset_type_form" action = "add_asset_type.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Asset Type Name:</td>
									<td><input class = "required" type = "text" name = "asset_type_name"></td>
								</tr>
								<tr>
									<td>Asset Type Category:</td>
									<td><?php echo $asset_type_category_select ?></td>
								</tr>
								<tr>
									<td>Asset Type Template:</td>
									<td><?php echo $asset_type_template_select ?></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add Asset Type"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $asset_type_table ?>
							<br><br>
							<?php echo $asset_type_table2 ?>
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