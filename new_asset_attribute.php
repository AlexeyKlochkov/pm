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
		$error_message = "Error adding attribute - attribute name probably already exists.";
	}
		if ($error_num == 2){
		$error_message = "Error editing attribute - attribute name probably already exists.";
	}
}
$edit_mode=0;
$edit_type = "";
$edit_name = "";
$current_aaid = 0;
if (!empty($_GET["aaid"])){
	$edit_mode = 1;
	$current_aaid=$_GET["aaid"];
}
$arr_attributes = get_asset_attributes($company_id, $active_flag);
//print_r($arr_attributes );
$attribute_table = "<table width = \"400\" class = \"budget\"><tr><th colspan = \"4\">Current Attributes</th></tr>";
if (!empty($arr_attributes)){
	foreach ($arr_attributes as $attribute_row){
		$asset_attribute_id = $attribute_row["asset_attribute_id"];
		$asset_attribute_name = $attribute_row["asset_attribute_name"];
		$display_type = $attribute_row["display_type"];
		$active = $attribute_row["active"];
		if ($current_aaid == $asset_attribute_id){
			$attribute_table .= "<form method = \"POST\" action = \"update_asset_attribute.php\"><tr><td><input type = \"text\" name = \"asset_attribute_name\" value = \"" . $asset_attribute_name . "\"</td><td align=\"left\">" .  get_display_type_select($display_type) . "</td><td colspan = \"2\"><a href = \"new_asset_attribute.php?ed=1&aaid=" . $asset_attribute_id . "\"><input type = \"submit\" value = \"update\"></td></tr><input type = \"hidden\" name = \"aaid\" value = \"" . $asset_attribute_id . "\"></form>\n";
			$edit_type = $display_type;
			$edit_name = $asset_attribute_name;
		}else{
		$attribute_table .= "<tr><td>" . $asset_attribute_name . "</td><td align=\"left\">" . $display_type . "</td><td><a href = \"new_asset_attribute.php?ed=1&aaid=" . $asset_attribute_id . "\">edit</a></td><td><a href = \"activate_asset_attribute.php?a=0&aaid=".$asset_attribute_id . "\">del</a></td></tr>\n";
		}
	}
}
$attribute_table .= "</table>";
$edit_table = "<table class = \"form_table\">";


$arr_inactive_attributes = get_asset_attributes($company_id, 2);
//print_r($arr_attributes );
$inactive_attribute_table = "<table width = \"400\" class = \"budget\"><tr><th colspan = \"3\">Inactive Attributes</th></tr>";
if (!empty($arr_inactive_attributes)){
	foreach ($arr_inactive_attributes as $attribute_row){
		$asset_attribute_id = $attribute_row["asset_attribute_id"];
		$asset_attribute_name = $attribute_row["asset_attribute_name"];
		$display_type = $attribute_row["display_type"];
		$active = $attribute_row["active"];
		$inactive_attribute_table .= "<tr><td>" . $asset_attribute_name . "</td><td align=\"left\">" . $display_type . "</td><td><a href = \"activate_asset_attribute.php?a=1&aaid=" . $asset_attribute_id . "\">activate</a></td></tr>\n";
	}
}
$inactive_attribute_table .= "</table>";




$display_order = 0;
$max_active_display_order = 0;
if ($edit_mode == 1){
	if (in_array($edit_type, array("Pull-Down Menu","Check Box","Radio Button"))){
		//print $edit_type;
		$edit_table .= $edit_name . ": " . $edit_type . " Choices";
		$arr_choices = get_asset_attribute_choices($current_aaid, 1);
		if (!empty($arr_choices)){
			$max_display_order = count($arr_choices);
			$max_active_display_order = $max_display_order;
			//print $max_display_order;
			foreach ($arr_choices as $choice_row){
				
				$asset_attribute_choice_id = $choice_row["asset_attribute_choice_id"];
				$asset_attribute_choice_name = $choice_row["asset_attribute_choice_name"];
				$display_order = $choice_row["display_order"];
				$up_arrow = "<a href = \"move_asset_attribute_choice.php?s1=" . ($display_order -1) . "&s2=" . $display_order . "&aaid=" .$current_aaid . "\"><img src = \"images/arrow_up.png\" border=\"0\"></a>";
				$down_arrow = "<a href = \"move_asset_attribute_choice.php?s1=" . ($display_order + 1) . "&s2=" . $display_order . "&aaid=" .$current_aaid . "\"><img src = \"images/arrow_down.png\" border=\"0\"></a>";
				if ($display_order == 1){
					$up_arrow = "&nbsp;";
				}
				if ($display_order == $max_display_order){
					$down_arrow = "&nbsp;";
				}
				
				$edit_table .= "<tr><td>" . $asset_attribute_choice_name . "</td><td>" . $up_arrow . "</td><td>" . $down_arrow . "</td><td><a href = \"del_asset_attribute_choice.php?aacid=" . $asset_attribute_choice_id . "&aaid=" . $current_aaid . "&d=" . $display_order . "\">del</a></td></tr>";
			}
		}
		$edit_table .= "<form action = \"add_asset_attribute_choice.php\" method = \"POST\"><tr><td><input type = \"text\" name = \"asset_attribute_choice_name\"></td><td colspan = \"3\"><input type = \"submit\" value = \"add\"><input type = \"hidden\" name = \"aaid\" value = \"" . $current_aaid . "\"><input type = \"hidden\" name = \"display_order\" value = \"" . ($display_order + 1) . "\"></td></tr></form>";
	}
}
$edit_table .= "</table>";


$inactive_table = "";

$display_order = 0;
if ($edit_mode == 1){
	if (in_array($edit_type, array("Pull-Down Menu","Check Box","Radio Button"))){
		
		$arr_choices = get_asset_attribute_choices($current_aaid, 0);
		if (!empty($arr_choices)){
			$inactive_table = "<table class = \"form_table\">";
			$inactive_table .= "Inactive choices:";
			$max_display_order = count($arr_choices);
			//print $max_display_order;
			foreach ($arr_choices as $choice_row){
				
				$asset_attribute_choice_id = $choice_row["asset_attribute_choice_id"];
				$asset_attribute_choice_name = $choice_row["asset_attribute_choice_name"];
				$display_order = $choice_row["display_order"];
								
				$inactive_table .= "<tr><td>" . $asset_attribute_choice_name . "</td><td><a href = \"activate_asset_attribute_choice.php?aacid=" . $asset_attribute_choice_id . "&aaid=" . $current_aaid . "&d=" . $max_active_display_order . "\">activate</a></td></tr>";
			}
			$inactive_table .= "</table>";
		}
	}
}


$field_example = "";
if ($edit_mode == 1){
	$field_example = get_asset_attribute_input_field($current_aaid, 0, "");
	
}


?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Asset Attribute</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#asset_attribute_form").validate();
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
				<h1>Asset Attributes</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				<table border = "0">
					<tr>
						<td valign="top">
							<form id = "asset_attribute_form" action = "add_asset_attribute.php" method = "POST">
								New Asset Attribute:<br>
								<table class = "form_table">
									<tr>
										<td>Attribute Name:</td>
										<td><input class = "required" type = "text" name = "asset_attribute_name"></td>
									</tr>
									<tr>
										<td>Field Type:</td>
										<td>
											<select name = "display_type" required>
												<option value = "Text Box">Text Box</option>
												<option value = "Radio Button">Radio Button</option>
												<option value = "Check Box">Check Box</option>
												<option value = "Pull-Down Menu">Pull-Down Menu</option>
												<option value = "Text Area">Text Area</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>
										<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
										<input type = "submit" value = "Add Field"></td>
										<td>&nbsp;</td>
									</tr>
								</table>
								<br>
							<?php
							if ($edit_mode == 1){
							?>
							Example:<br>
							<table class = "form_table" width = "100%">
								<tr>
									<td>
								<?php echo $field_example  ?>
									</td>
								</tr>
							</table>
							<?php
							}
							?>
							</form>
							</td>
							<td  valign="top">
								<?php echo $attribute_table  ?><br>
								<?php echo $inactive_attribute_table  ?>
							</td>
							<td  valign="top">
								<?php echo $edit_table  ?><br>
								<?php echo $inactive_table ?><br>
								
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