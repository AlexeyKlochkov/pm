<?php 
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";

$asset_type_template_id = 0;
if (!empty($_GET["asset_type_template_id"])){
	$asset_type_template_id = $_GET["asset_type_template_id"];
}	

$asset_type_template_select = get_asset_type_template_select($company_id, $asset_type_template_id);
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
			
			
			$current_input_field = get_asset_attribute_input_field($asset_attribute_id, $include_attribute_name, "");
			$current_object = "<div id = \"attaid_" . $asset_type_template_attribute_id . "\" class = \"draggable_item\" title = \"" . $asset_attribute_name . "\">" . $current_input_field . "</div>";
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


?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Edit Asset Type Specsheet</title>
<style>

#<?php echo $color_module_id ?>{

	background-color: #<?php echo $cm_bg_color ?>;
	width: 650px;

}
#<?php echo $vendor_module_id ?>{

	background-color: #<?php echo $vm_bg_color ?>;
	width: 650px;
}

#<?php echo $image_module_id ?>{
	background-color: #<?php echo $im_bg_color ?>;
	width: 150px;
}
#templateGrid { display: inline-block; }
</style>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script> 
  <script>
   $(function() {

    $( ".draggable_item" ).draggable({ 
		grid: [ 20, 20 ],
		stop: handleDragStop,
		containment: "#templateGrid", 
		scroll: false,
		snap: true
	});

	function placeObject(object_id, x_offset, y_offset){
		top_position = y_offset + $("#templateGrid").offset().top;
		left_position = x_offset + $("#templateGrid").offset().left;
		//alert($("#templateGrid").offset().left);
		//top_position = 0;
		//left_position = 300;
		$("#" + object_id).offset({ top: top_position, left: left_position });
		//alert($("#" + object_id).offset());
	}
	function handleDragStop( event, ui ) {
		var offsetXPos = parseInt( ui.offset.left ) - $("#templateGrid").offset().left;
		var offsetYPos = parseInt( ui.offset.top ) - $("#templateGrid").offset().top;
		//alert( "Drag stopped!\n\nOffset: (" + offsetXPos + ", " + offsetYPos + ")\n");
		var arr_attaid = $(this).attr("id").split('_');
		current_id = arr_attaid[1];
		object_type = arr_attaid[0];
		var delete_this = 0;
		if(offsetXPos < 200){
			if(offsetYPos < 50){
				delete_this = 1;
				//alert ("Delete this!");
				$.ajax({   
				   type: 'POST',   
				   url: 'del_template_drag.php', 
				   data: {id:current_id, object_type:object_type}
				});
				$(this).css("visibility","hidden");

			}
		}
		if(delete_this ==0){
			$.ajax({   
			   type: 'POST',   
			   url: 'update_template_drag.php', 
			   data: {id:current_id, object_type:object_type, x_offset:offsetXPos, y_offset:offsetYPos}
			});
		}
		
	}
	
	$('#<?php echo $color_module_id ?>').<?php echo $show_cm ?>();
	$('#<?php echo $vendor_module_id ?>').<?php echo $show_vm ?>();
	$('#<?php echo $image_module_id ?>').<?php echo $show_im ?>();
	
	$('#asset_type_template_select').change(function() {
        this.form.submit();
    });
	
	$('#asset_attribute_select').change(function() {
        this.form.submit();
    });
	
	
	function hideAllForms(){
		$("#label_form").hide();
		$("#line_form").hide();
		$("#color_module_form").hide();
		$("#vendor_module_form").hide();
		$("#image_module_form").hide();
	}
	$('#garnish_type_select').change(function() {
        var garnish_value = $( "#garnish_type_select option:selected" ).val();
		if (garnish_value == "label"){
			 hideAllForms();
			$("#label_form").show();
		}
		if (garnish_value == "line"){
			hideAllForms();
			$("#line_form").show();
		}
		if (garnish_value == ""){
			hideAllForms();
		}
		if (garnish_value == "color_module"){
			hideAllForms();
			$("#color_module_form").show();
		}
		if (garnish_value == "vendor_module"){
			hideAllForms();
			$("#vendor_module_form").show();
		}
		if (garnish_value == "image_module"){
			hideAllForms();
			$("#image_module_form").show();
		}
		
    });
	$("#add_label_form").validate();
	$("#add_line_form").validate();
	$('#templateGrid').addClass('expand');
	
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
				<h1>Edit Asset Type Template</h1>
				<table border = "0" class = "small_link">
					<tr>
						<td valign = "top">
							<form id = "select_template" action = "edit_asset_type_template.php" method = "GET">
								<?php echo $asset_type_template_select ?>
							</form>
						</td>
<?php
if(!empty($asset_type_template_id)){
?>
						<td valign = "top">
							<form action = "add_asset_type_template_attribute.php" method = "POST">
							<?php echo $add_attribute_select  ?><br>
							<input type = "checkbox" name = "include_attribute_name" value = "1"> Include Name
							<input type = "hidden" name = "asset_type_template_id" value = "<?php echo $asset_type_template_id ?>">
							</form>
						</td>
						<td valign = "top">
							<select name = "garnish_type" id = "garnish_type_select">
								<option value = "">Label, Line or Module</option>
								<option value = "label">Label</option>
								<option value = "line">Line</option>
<?php
if($show_color_module == 0){
?>
								<option value = "color_module">Color Module</option>
<?php
}
if($show_image_module == 0){
?>
								<option value = "image_module">Image Module</option>

<?php
}
if($show_vendor_module == 0){
?>
								<option value = "vendor_module">Vendor Module</option>
<?php
}
?>
							</select>
						</td>
<?php
} //end if asset_type_template_id is not empty
?>
						<td  valign = "top">
						<div id = "label_form" style = "display:none;">
							<form action = "add_asset_type_template_garnish.php" method = "POST" id = "add_label_form">
								Text: <input type = "text" name = "garnish_text" value = "" class = "required"> Color: <input type = "text" name = "garnish_color" value = "000000" class = "required"> Font Size: <select name = "garnish_font_size"  class = "required"><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option><option>14</option><option>18</option><option>20</option></select><input type = "hidden" name = "asset_type_template_id" value = "<?php echo $asset_type_template_id ?>"><input type = "hidden" name = "garnish_type" value = "label"><input type = "submit" value = "add">
							</form>
						</div>
						<div id = "line_form" style = "display:none;">
							<form action = "add_asset_type_template_garnish.php" method = "POST" id = "add_line_form">
								Color: <input type = "text" name = "garnish_color" value = "000000" class = "required"> Height: <input type = "text" name = "garnish_height" value = "" size = "3" class = "required number"> Width: <input type = "text" name = "garnish_width" value = "" size = "3" class = "required number"><input type = "hidden" name = "asset_type_template_id" value = "<?php echo $asset_type_template_id ?>"><input type = "hidden" name = "garnish_type" value = "line"><input type = "submit" value = "add">
							</form>
						</div>
						<div id = "color_module_form" style = "display:none;">
							<form action = "add_asset_type_template_garnish.php" method = "POST" id = "add_line_form">
								Background Color: <input type = "text" name = "garnish_color" value = "F9F9F9" class = "required"><input type = "hidden" name = "asset_type_template_id" value = "<?php echo $asset_type_template_id ?>"><input type = "hidden" name = "garnish_type" value = "color_module"><input type = "submit" value = "add">
							</form>
						</div>
						<div id = "vendor_module_form" style = "display:none;">
							<form action = "add_asset_type_template_garnish.php" method = "POST" id = "add_line_form">
								Background Color: <input type = "text" name = "garnish_color" value = "F9F9F9" class = "required"><input type = "hidden" name = "asset_type_template_id" value = "<?php echo $asset_type_template_id ?>"><input type = "hidden" name = "garnish_type" value = "vendor_module"><input type = "submit" value = "add">
							</form>
						</div>
						<div id = "image_module_form" style = "display:none;">
							<form action = "add_asset_type_template_garnish.php" method = "POST" id = "add_line_form">
								Background Color: <input type = "text" name = "garnish_color" value = "F9F9F9" class = "required"><input type = "hidden" name = "asset_type_template_id" value = "<?php echo $asset_type_template_id ?>"><input type = "hidden" name = "garnish_type" value = "image_module"><input type = "submit" value = "add">
							</form>
						</div>
					</tr>
				</table>
				
				<div id = "templateGrid">
					<?php echo $unplaced_object_list  ?>
					<?php echo $placed_object_list ?>
					<div id = "trash_area">&nbsp; trash it!</div>
					<div id = "<?php echo $color_module_id ?>" class = "draggable_item"  style = "display:none;">
						<table width = "650" class = "specsheet_module">
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
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>
					</div>
					<div id = "<?php echo $vendor_module_id ?>" class = "draggable_item"  style = "display:none;">
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
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>
									
					</div>
					<div id = "<?php echo $image_module_id ?>" class = "draggable_item"  style = "display:none;">
						<table width = "150" class = "specsheet_module">
							<tr>
								<th valign = "top">
									Image
								</th>
								<th valign = "top">
									View
								</th>
								<th valign = "top">
									Del
								</th>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>
									
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