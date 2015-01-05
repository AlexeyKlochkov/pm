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
		$error_message = "Error adding template - template name probably already exists.";
	}
}
$edit_mode=0;
$edit_type = "";
$edit_name = "";
$current_tid = 0;
if (!empty($_GET["tid"])){
	$edit_mode = 1;
	$current_tid=$_GET["tid"];
}
$arr_templates = get_asset_type_templates($company_id, $active_flag);
//print_r($arr_attributes );
$template_table = "<table width = \"400\" class = \"budget\"><tr><th colspan = \"4\">Current Asset Type Templates</th></tr>";
if (!empty($arr_templates)){
	foreach ($arr_templates as $template_row){
		$asset_type_template_id = $template_row["asset_type_template_id"];
		$asset_type_template_name = $template_row["asset_type_template_name"];
		$active = $template_row["active"];
		$template_table .= "<tr><td width = \"300\">" . $asset_type_template_name . "</td>";
		$template_table .= "<td><a href = \"edit_asset_type_template.php?asset_type_template_id=".$asset_type_template_id . "\">edit</a></td>";
		$template_table .= "<td><a href = \"activate_asset_type_template.php?a=2&attid=".$asset_type_template_id . "\">del</a></td></tr>\n";
		
	}
}
$template_table .= "</table>";
$edit_table = "<table class = \"form_table\">";


$arr_inactive_templates = get_asset_type_templates($company_id, 2);
//print_r($arr_attributes );
$inactive_template_table = "<table width = \"400\" class = \"budget\"><tr><th colspan = \"3\">Inactive Asset Type Templates</th></tr>";
if (!empty($arr_inactive_templates)){
	foreach ($arr_inactive_templates as $template_row){
		$asset_type_template_id = $template_row["asset_type_template_id"];
		$asset_type_template_name = $template_row["asset_type_template_name"];
		$active = $template_row["active"];
		$inactive_template_table .= "<tr><td width = \"300\">" . $asset_type_template_name . "</td><td><a href = \"activate_asset_type_template.php?a=1&attid=" . $asset_type_template_id . "\">activate</a></td></tr>\n";
	}
}
$inactive_template_table .= "</table>";






?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Asset Type Template</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#asset_type_template_form").validate();
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
				<h1>Asset Type (SpecSheet) Templates</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				<table border = "0">
					<tr>
						<td valign="top">
							<form id = "asset_type_template_form" action = "add_asset_type_template.php" method = "POST">
								New Asset Type Template:<br>
								<table class = "form_table">
									<tr>
										<td>Template Name:</td>
										<td><input class = "required" type = "text" name = "asset_type_template_name"></td>
									</tr>
									<tr>
										<td>
										<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
										<input type = "submit" value = "Add Template"></td>
										<td>&nbsp;</td>
									</tr>
								</table>
								<br>
							</form>
							</td>
							<td  valign="top">
								<?php echo $template_table  ?><br>
								<?php echo $inactive_template_table  ?>
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
</body>
</html>