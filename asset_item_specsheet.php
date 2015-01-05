<?php 
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";

$asset_item_id = "";
$project_id = "";
$asset_type_id = "";
if (!empty($_GET["aiid"])){
	$asset_item_id = $_GET["aiid"];
}
if (!empty($_GET["p"])){
	$project_id = $_GET["p"];
}
if (!empty($_GET["atid"])){
	$asset_type_id = $_GET["atid"];
}

$asset_type_template_id = get_asset_type_template_id($asset_type_id);


$unplaced_object_list = "";
$offset_list = "";
$placed_object_list = "";
$color_module_id = "";
$show_cm = "hide";
$cm_bg_color = "";
$show_color_module = 0;

$vendor_module_id = "";
$show_vm = "hide";
$vm_bg_color = "";
$show_vendor_module = 0;

$image_module_id = "";
$show_im = "hide";
$im_bg_color = "";
$show_image_module = 0;

$checkbox_list = "";

//get all attribute values
$arr_attribute_values = get_asset_item_attributes($asset_item_id);
$arr_attribute_key_value = array();
if(!empty($arr_attribute_values)){
	foreach ($arr_attribute_values as $attribute_value_row){
		$asset_attribute_id = $attribute_value_row["asset_attribute_id"];
		$asset_item_value = $attribute_value_row["asset_item_value"];
		//create the key-value pair. 
		$arr_attribute_key_value[$asset_attribute_id] = $asset_item_value;
		
	}
}

//print_r($arr_attribute_key_value );

if(!empty($asset_type_template_id)){
	$arr_template_obejects = get_asset_type_template_attributes($asset_type_template_id);
	if (!empty($arr_template_obejects)){
		foreach ($arr_template_obejects as $asset_type_template_attribute_row){
			$asset_type_template_attribute_id = $asset_type_template_attribute_row["asset_type_template_attribute_id"];
			$asset_attribute_id = $asset_type_template_attribute_row["asset_attribute_id"];
			$asset_attribute_name = $asset_type_template_attribute_row["asset_attribute_name"];
			$include_attribute_name = $asset_type_template_attribute_row["include_attribute_name"];
			$x_offset = $asset_type_template_attribute_row["x_offset"];
			$y_offset = $asset_type_template_attribute_row["y_offset"];
			//top = 144,245
			
			$asset_item_value = "";
			if(!empty($arr_attribute_key_value[$asset_attribute_id])){
				$asset_item_value = $arr_attribute_key_value[$asset_attribute_id];
			}
			$current_input_field = get_asset_attribute_input_field($asset_attribute_id, $include_attribute_name, $asset_item_value);
			$current_object = "<div id = \"attaid_" . $asset_type_template_attribute_id . "\" class = \"draggable_item\" title = \"" . $asset_attribute_name . "\">" . $current_input_field . "</div>";
			
			//check for checkboxes
			if (strpos($current_input_field, "checkbox")){
				$checkbox_list .= "," . $asset_attribute_id;
			}
			
			if(empty($x_offset)){
				//object has not been placed
				$unplaced_object_list .= $current_object;
			}else{
				//create javascript offset to place the objects
				$offset_list .= "placeObject(\"attaid_" . $asset_type_template_attribute_id . "\", " . $x_offset . ", " . $y_offset  . ");\n";
				//add objects
				$placed_object_list .= $current_object . "\n";
			}
			
		}
	}
	
	//get garnishes - text labels and lines
	$arr_template_garnishes = get_asset_type_template_garnishes($asset_type_template_id);
	if (!empty($arr_template_garnishes)){
		foreach ($arr_template_garnishes as $asset_type_template_garnish_row){
			$asset_type_template_garnish_id = $asset_type_template_garnish_row["asset_type_template_garnish_id"];
			$garnish_type = $asset_type_template_garnish_row["garnish_type"];
			$garnish_text = $asset_type_template_garnish_row["garnish_text"];
			$garnish_color = $asset_type_template_garnish_row["garnish_color"];
			$garnish_width = $asset_type_template_garnish_row["garnish_width"];
			$garnish_height = $asset_type_template_garnish_row["garnish_height"];
			$garnish_font_size = $asset_type_template_garnish_row["garnish_font_size"];
			$x_offset = $asset_type_template_garnish_row["x_offset"];
			$y_offset = $asset_type_template_garnish_row["y_offset"];
			if($garnish_type == "label"){
				$current_object = "<div id = \"attgid_" . $asset_type_template_garnish_id . "\" class = \"draggable_item\" style = \"font-size: " . $garnish_font_size . "px; color: #" . $garnish_color . ";\">" . $garnish_text . "</div>";
				$placed_object_list .= $current_object . "\n";
			}
			if($garnish_type == "line"){
				$current_object = "<div id = \"attgid_" . $asset_type_template_garnish_id . "\" class = \"draggable_item\" style = \"width: " . $garnish_width . "px; height: " . $garnish_height . "px; background: #" . $garnish_color . "; border: 0; margin: 0; padding: 0; display: block;\">" . $garnish_text . "</div>";
				$placed_object_list .= $current_object . "\n";
			}
			if($garnish_type == "color_module"){
				$show_color_module = 1;
				$show_cm = "show";
				$color_module_id = "attgid_" . $asset_type_template_garnish_id;
				$cm_bg_color = $garnish_color;
			}
			
			if($garnish_type == "vendor_module"){
				$show_vendor_module = 1;
				$show_vm = "show";
				$vendor_module_id = "attgid_" . $asset_type_template_garnish_id;
				$vm_bg_color = $garnish_color;
			}
			if($garnish_type == "image_module"){
				$show_image_module = 1;
				$show_im = "show";
				$image_module_id = "attgid_" . $asset_type_template_garnish_id;
				$im_bg_color = $garnish_color;
			}
			
			$offset_list .= "placeObject(\"attgid_" . $asset_type_template_garnish_id . "\", " . $x_offset . ", " . $y_offset  . ");\n";
		}
	}
}

$add_attribute_select = get_add_attribute_select($company_id, $asset_type_template_id);

$checkbox_list = substr($checkbox_list, 1);

$vendor_select = get_vendor_select($company_id, 0);
$vendor_table_rows = "";
if(!empty($vendor_module_id )){
	$arr_asset_item_vendor = get_asset_item_vendors($asset_item_id);
	if (!empty($arr_asset_item_vendor)){
		foreach ($arr_asset_item_vendor as $asset_item_vendor_row){
			$asset_item_vendor_id = $asset_item_vendor_row["asset_item_vendor_id"];
			$vendor_name = $asset_item_vendor_row["vendor_name"];
			$delivery_method = $asset_item_vendor_row["delivery_method"];
			$released_by = $asset_item_vendor_row["released_by"];
			$released_what = $asset_item_vendor_row["released_what"];
			$release_date = $asset_item_vendor_row["release_date"];
			$issue_date = $asset_item_vendor_row["issue_date"];
			$vendor_table_rows .= "<tr><td>" . $vendor_name . "</td>";
			$vendor_table_rows .= "<td>" . $delivery_method . "</td>";
			$vendor_table_rows .= "<td>" . $released_by . "</td>";
			$vendor_table_rows .= "<td>" . $released_what . "</td>";
			$vendor_table_rows .= "<td>" . convert_mysql_to_datepicker($release_date) . "</td>";
			$vendor_table_rows .= "<td>" . convert_mysql_to_datepicker($issue_date) . "</td>";
			$vendor_table_rows .= "<td><a href = \"del_asset_item_vendor.php?p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id . "&aivid=" . $asset_item_vendor_id . "\">del</a></td></tr>\n";
		}
	}
}

$print_color_select = get_print_color_select($company_id, 0);

$color_table_rows = "";
if(!empty($color_module_id )){
	$arr_asset_item_color = get_asset_item_colors($asset_item_id);
	if (!empty($arr_asset_item_color)){
		foreach ($arr_asset_item_color as $asset_item_color_row){
			$asset_item_color_id = $asset_item_color_row["asset_item_color_id"];
			$print_color_name = $asset_item_color_row["print_color_name"];
			$coated = $asset_item_color_row["coated"];
			$process_or_spot = $asset_item_color_row["process_or_spot"];
			$ink_used_in = $asset_item_color_row["ink_used_in"];
			$tint = $asset_item_color_row["tint"];
			$notes = $asset_item_color_row["notes"];
			$color_table_rows .= "<tr><td>" . $print_color_name . "</td>";
			$color_table_rows .= "<td>" . $coated . "</td>";
			$color_table_rows .= "<td>" . $process_or_spot . "</td>";
			$color_table_rows .= "<td>" . $ink_used_in . "</td>";
			$color_table_rows .= "<td>" . $tint . "</td>";
			$color_table_rows .= "<td>" . $notes . "</td>";
			$color_table_rows .= "<td><a href = \"del_asset_item_color.php?p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id . "&aicid=" . $asset_item_color_id . "\">del</a></td></tr>\n";
		}
	}
}

//put together the image module
$image_module_rows = "";
if($show_im == "show"){
	$arr_item_images = get_asset_item_images($asset_item_id);
	if (!empty($arr_item_images)){
		foreach ($arr_item_images as $asset_item_image_row){
			$asset_item_image_id = $asset_item_image_row["asset_item_image_id"];
			$image_id = $asset_item_image_row["image_id"];
			$image_module_rows .= "<tr><td align=\"right\">" . $image_id . "</td><td align=\"right\">view</td><td align=\"right\"><a href = \"del_asset_item_image.php?aiiid=" . $asset_item_image_id . "&p=" . $project_id . "&aiid=" . $asset_item_id . "&atid=" . $asset_type_id . "\">del</a></td></tr>\n";
		}
	}
}

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Asset SpecSheet</title>
<style>

#<?php echo $color_module_id ?>{



	background-color: #<?php echo $cm_bg_color ?>;
	width: 650px;
	clear: both;


}
#<?php echo $vendor_module_id ?>{
	
	background-color: #<?php echo $vm_bg_color ?>;
	width: 650px;
}

#<?php echo $image_module_id ?>{
	
	background-color: #<?php echo $im_bg_color ?>;
	width: 150px;

}

#templateGrid { 
	overflow:auto; 
	
}
</style>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script> 
  <script>
   $(function() {
	$( ".datepicker" ).datepicker();
	$("#add_asset_item_image").validate();

	function placeObject(object_id, x_offset, y_offset){
		top_position = y_offset + $("#templateGrid").offset().top;
		left_position = x_offset + $("#templateGrid").offset().left;
		//alert($("#templateGrid").offset().left);
		//top_position = 0;
		//left_position = 300;
		$("#" + object_id).offset({ top: top_position, left: left_position });
		//alert($("#" + object_id).offset());
	}
	
	
	
	$('#<?php echo $color_module_id ?>').<?php echo $show_cm ?>();
	$('#<?php echo $vendor_module_id ?>').<?php echo $show_vm ?>();
	$('#<?php echo $image_module_id ?>').<?php echo $show_im ?>();
	
	
	
	
	<?php echo $offset_list  ?>

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
				<h1>Asset SpecSheet</h1>
				<div id = "templateGrid">
				<form action = "update_asset_item_attributes.php" method = "POST">
				<input type = "hidden" name = "asset_item_id" value = "<?php echo $asset_item_id  ?>">
				<input type = "hidden" name = "asset_type_id" value = "<?php echo $asset_type_id  ?>">
				<input type = "hidden" name = "project_id" value = "<?php echo $project_id  ?>">
					<?php echo $unplaced_object_list  ?>
					<?php echo $placed_object_list ?>
				<input type = "hidden" name = "checkbox_list" value = "<?php echo $checkbox_list ?>">
				<input type = "submit" value = "update">
				</form>
					<div id = "<?php echo $color_module_id ?>" style = "display:none;">
						<form action = "add_asset_item_color.php" method = "POST">
						<table width = "500" class = "specsheet_module">
							<tr>
								<th>
									Color
								</th>
								<th>
									U/C
								</th>
								<th>
									4C/Spot
								</th>
								<th>
									Ink Used In
								</th>
								<th>
									Tint
								</th>
								<th>
									Notes
								</th>
								<th>
									&nbsp;
								</th>
							</tr>
							<?php echo $color_table_rows ?>
							<tr>
								<td>
									<?php echo $print_color_select ?>
								</td>
								<td>
									<select name = "coated">
										<option>Uncoated</option>
										<option>Coated</option>
									</select>
								</td>
								<td>
									<select name = "process_or_spot">
										<option>Process</option>
										<option>Spot</option>
									</select>
								</td>
								<td>
									<select name = "ink_used_in">
										<option>Solid</option>
										<option>Dark Phoenix</option>
										<option>Light Phoenix</option>
										<option>Headline</option>
										<option>Text Weight</option>
										<option>Image</option>
									</select>
								</td>
								<td>
									<input name = "tint" type = "text" class = "number" size = "3">
								</td>
								<td>
									<input name = "notes" type = "text" size = "12">
								</td>
								<td>
									<input type = "hidden" name = "asset_item_id" value = "<?php echo $asset_item_id ?>">
									<input type = "hidden" name = "asset_type_id" value = "<?php echo $asset_type_id  ?>">
									<input type = "hidden" name = "project_id" value = "<?php echo $project_id  ?>">
									<input type = "submit" value = "add">
								</td>
							</tr>
						</table>
						</form>
					</div>
					<div id = "<?php echo $vendor_module_id ?>" style = "display:none;">
						<form action = "add_asset_item_vendor.php" method = "POST">
						<table width = "600" class = "specsheet_module">
							<tr>
								<th valign = "top">
									Vendor
								</th>
								<th valign = "top">
									Delivery Method
								</th>
								<th valign = "top">
									Released By
								</th>
								<th valign = "top">
									Released What
								</th>
								<th valign = "top">
									Release/Close Date
								</th>
								<th valign = "top">
									Issue Date
								</th>
								<th>
									&nbsp;
								</th>
							</tr>
							<?php echo $vendor_table_rows ?>
							<tr>
								<td>
									<?php echo $vendor_select ?>
								</td>
								<td>
									<select name = "delivery_method">
										<option>Disk</option>
										<option>Email</option>
										<option>FTP</option>
										<option>Website</option>
										<option>YouSendIt</option>
									</select>
										
								</td>
								<td>
									<select name = "released_by">
										<option>Studio</option>
										<option>Prepress</option>
									</select>
								</td>
								<td>
									<select name = "released_what">
										<option>Final Materials</option>
										<option>Mechanical</option>
									</select>
								</td>
								<td>
									<input  type = "text" name = "release_date" value = "" class = "datepicker" size = "6">
								</td>
								<td>
									<input  type = "text" name = "issue_date" value = "" class = "datepicker" size = "6">
								</td>
								<td>
									<input type = "hidden" name = "asset_item_id" value = "<?php echo $asset_item_id ?>">
									<input type = "hidden" name = "asset_type_id" value = "<?php echo $asset_type_id  ?>">
									<input type = "hidden" name = "project_id" value = "<?php echo $project_id  ?>">
									<input type = "submit" value = "add">
								</td>
							</tr>
						</table>
						</form>
									
					</div>
					<div id = "<?php echo $image_module_id ?>" class = "draggable_item"  style = "display:none;">
					<form id = "add_asset_item_image" action = "add_asset_item_image.php" method = "POST">
						<table width = "150" class = "specsheet_module">
							<tr>
								<th valign = "top" colspan = "3">
									Image
								</th>
								
							</tr>
							<?php echo $image_module_rows ?>
							
							<tr>
								
								<td align = "right">
									<input type = "text" name = "image_id" value = "" class = "required number" size = "2">
									<input type = "hidden" name = "asset_item_id" value = "<?php echo $asset_item_id ?>">
									<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
									<input type = "hidden" name = "asset_type_id" value = "<?php echo $asset_type_id ?>">
								</td>
								<td colspan = "2">
									<input type = "submit" value = "add">
								
								</td>
								
							</tr>
							
						</table>
							</form>		
					</div>
					
				</div>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>