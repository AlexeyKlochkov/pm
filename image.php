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
		$error_message = "An error occurred..";
	}
		if ($error_num == 2){
		$error_message = "Image file size is too big.";
	}
}
$sub_button = "";
$image_id = 0;
if (!empty($_GET["i"])){
	$image_id = $_GET["i"];
	$button_text = "Update Image";
}else{
	$button_text = "Add Image";
}
$image_file = "";
$show_image = "hide";
$prev_button = "";
$next_button = "";

$width = "";
$height = "";
$file_size = "";
$resolution= "";
$stock_ref_code= "";
$image_stock_name = "";
$stock_quote_id = "";
$stock_or_photographer = "";
$rep_or_stock_house = "";
$photographer_name = "";
$rights_managed_type = "";
$royalty_free_type = "";
$image_media_rights = "";
$image_media_rights_other = "";
$image_notes = "";
$image_usage_start = "";
$image_usage_end = "";
$unlimited_usage = "";
$image_territory = "";
$image_territory_other = "";
$release_received = "";
$release_type = "";
$image_exclusivity = "";
$exclusivity_notes = "";
$image_usage_category = "";
$image_usage_category_other = "";
$original_project_code = "";
$original_project_manager = "";
$original_art_buyer = "";
$posting_to_asset_library = "";
$high_resolution_location = "";
$low_resolution_location = "";
$image_needs_retouching = "";
$image_has_been_replaced = "";
$active = "";

$arr_media_rights_list = array();
$arr_usage_category_selected = array();
$arr_hi_res_location_selected = array();
$arr_low_res_location_selected = array();

if(!empty($image_id)){
	$arr_image = get_image_info($image_id);
	if (!empty($arr_image)){
		$width = $arr_image[0]["width"];
		$height = $arr_image[0]["height"];
		$file_size = $arr_image[0]["file_size"];
		$resolution = $arr_image[0]["resolution"];
		$stock_ref_code = $arr_image[0]["stock_ref_code"];
		$image_stock_name = $arr_image[0]["image_stock_name"];
		$stock_quote_id = $arr_image[0]["stock_quote_id"];
		$stock_or_photographer = $arr_image[0]["stock_or_photographer"];
		$rep_or_stock_house = $arr_image[0]["rep_or_stock_house"];
		$photographer_name = $arr_image[0]["photographer_name"];
		$rights_managed_type = $arr_image[0]["rights_managed_type"];
		$royalty_free_type = $arr_image[0]["royalty_free_type"];
		$image_media_rights = $arr_image[0]["image_media_rights"];
		$image_media_rights_other = $arr_image[0]["image_media_rights_other"];
		$image_notes = $arr_image[0]["image_notes"];
		$image_usage_start = convert_mysql_to_datepicker($arr_image[0]["image_usage_start"]);
		$image_usage_end = convert_mysql_to_datepicker($arr_image[0]["image_usage_end"]);
		$unlimited_usage = $arr_image[0]["unlimited_usage"];
		$image_territory = $arr_image[0]["image_territory"];
		$image_territory_other =$arr_image[0]["image_territory_other"];
		$release_received = $arr_image[0]["release_received"];
		$release_type = $arr_image[0]["release_type"];
		$image_exclusivity = $arr_image[0]["image_exclusivity"];
		$exclusivity_notes = $arr_image[0]["exclusivity_notes"];
		$image_usage_category = $arr_image[0]["image_usage_category"];
		$image_usage_category_other = $arr_image[0]["image_usage_category_other"];
		$original_project_code = $arr_image[0]["original_project_code"];
		$original_project_manager = $arr_image[0]["original_project_manager"];
		$original_art_buyer = $arr_image[0]["original_art_buyer"];
		$posting_to_asset_library = $arr_image[0]["posting_to_asset_library"];
		$high_resolution_location = $arr_image[0]["high_resolution_location"];
		$low_resolution_location = $arr_image[0]["low_resolution_location"];
		$image_needs_retouching = $arr_image[0]["image_needs_retouching"];
		$image_has_been_replaced = $arr_image[0]["image_has_been_replaced"];
		$active = $arr_image[0]["active"];

		$arr_media_rights_list = explode(",",$image_media_rights);
		$arr_usage_category_selected = explode(",",$image_usage_category);
		$arr_hi_res_location_selected  = explode(",",$high_resolution_location);
		$arr_low_res_location_selected  = explode(",",$low_resolution_location);
	}
	//check if file exists
	$filename = "images/image/i" . $image_id . ".jpg";
	if (file_exists($filename)) {
		$image_file = "<img src = \"" . $filename . "\">";
		
	}else{
		$image_file = "<img src = \"images/no_image.jpg\">";
	}
	$show_image = "show";
	//handle next and prev buttons
	
	
	$prev_button = "";
	$next_button = "";


	if($image_id <> 1){
		$prev_image = ($image_id -1);
		$prev_button = "<a href = \"image.php?i=" . $prev_image . "\">prev</a>";
	}

}
$max_image_id = get_max_image_id();
if($image_id <> $max_image_id){
	$next_image = ($image_id +1);
	$next_button = "<a href = \"image.php?i=" . $next_image . "\">next</a>";
}



$arr_stock_or_photographer = array("Photographer", "Stock House");
$stock_or_photographer_select = get_generic_pulldown($arr_stock_or_photographer, "stock_or_photographer", $stock_or_photographer);

$rep_stock_house_select = get_vendor_select($company_id, $rep_or_stock_house);

$arr_rights_managed_type = array("Quick License (Corbis)", "Custom License (Corbis)", "Individual Use (Getty)", "Flexible Pack (Getty)", "Rights Managed");
$rights_managed_type_select = get_generic_pulldown($arr_rights_managed_type, "rights_managed_type", $rights_managed_type);

$arr_royalty_free_type = array("Basic User License (Veer RF/1 seat)","Multi user License (Veer RF/multiple seats)","Basic License (Corbis/10 seats)","Basic License (Thinkstock/Getty)", "Multi User Package (Corbis/30 seats)", "10 User Package (Getty)","30 User Package (Getty)","Standard License (istock)", "Extended License (istock)","Basic License(Masterfile)","Multi User License (Masterfile)");
$royalty_free_type_select = get_generic_pulldown($arr_royalty_free_type, "royalty_free_type", $royalty_free_type);

$arr_media_rights = array("All Media","Excl. Broadcast", "Print Media", "OOH", "Web", "Other");
$media_rights_checkboxes = get_generic_checkboxes($arr_media_rights, $arr_media_rights_list, "MR");

$unlimited_usage_checked = "";
if($unlimited_usage == 1){
	$unlimited_usage_checked = "checked";
}

$arr_image_territory = array("US Only","US Only (Inc. Mil Bases)", "World Wide","Other");
$image_territory_radio = get_generic_radio($arr_image_territory, "image_territory", $image_territory);

$arr_release_received = array("Yes","No");
$release_received_radio = get_generic_radio($arr_release_received, "release_received", $release_received);

$arr_image_exclusivity = array("Exclusive","Non-Exclusive");
$image_exclusivity_radio = get_generic_radio($arr_image_exclusivity, "image_exclusivity", $image_exclusivity);

$arr_business_units = get_business_units($company_id, 1);
$arr_usage_category = array();
if (!empty($arr_business_units)){
	foreach ($arr_business_units as $business_unit_row){
		$business_unit_id = $business_unit_row["business_unit_id"];
		$business_unit_abbrev = $business_unit_row["business_unit_abbrev"];
		array_push($arr_usage_category, $business_unit_abbrev);
	}
}
//print_r($arr_usage_category);
$usage_category_checkbox_list = get_generic_checkboxes($arr_usage_category, $arr_usage_category_selected, "UC");

$arr_posting = array("Yes","No");
$posting_to_asset_library_radio = get_generic_radio($arr_posting, "posting_to_asset_library", $posting_to_asset_library);

$arr_hi_res_location = array("Server","Asset Library", "Backup");
$high_resolution_location_checkbox = get_generic_checkboxes($arr_hi_res_location, $arr_hi_res_location_selected, "HR");

$arr_low_res_location = array("Server","Asset Library", "Backup");
$low_resolution_location_checkbox = get_generic_checkboxes($arr_low_res_location, $arr_low_res_location_selected, "LR");


$arr_needs_retouching = array("Yes","No");
$image_needs_retouching_radio = get_generic_radio($arr_needs_retouching, "image_needs_retouching", $image_needs_retouching);

$arr_been_replaced = array("Yes","No");
$image_has_been_replaced_radio = get_generic_radio($arr_been_replaced, "image_has_been_replaced", $image_has_been_replaced);

$active_checked = "";
if($active==1){
	$active_checked = "checked";
}


$arr_meta_data_checked = get_meta_data_for_image($image_id);
//print_r($arr_meta_data_checked);
$all_meta_data_list = ""; //for the hidden variable
$arr_image_meta_ids = array();
if (!empty($arr_meta_data_checked)){
	foreach ($arr_meta_data_checked as $meta_data_row){
		//print $meta_data_row["meta_data_id"];
		array_push($arr_image_meta_ids, $meta_data_row["meta_data_id"]);
		$all_meta_data_list .= $meta_data_row["meta_data_id"] . ",";
		
	}
}
$all_meta_data_list = substr($all_meta_data_list, 0, -1);

$program_meta_data_list = get_meta_data_list_by_category($company_id, 1, 1);
$program_checkboxes = get_meta_checkbox_list("Program",$program_meta_data_list,$arr_image_meta_ids);

$setting_meta_data_list = get_meta_data_list_by_category($company_id, 2, 1);
$setting_checkboxes = get_meta_checkbox_list("Setting",$setting_meta_data_list,$arr_image_meta_ids);

$gender_meta_data_list = get_meta_data_list_by_category($company_id, 3, 1);
$gender_checkboxes = get_meta_checkbox_list("Gender",$gender_meta_data_list,$arr_image_meta_ids);

$groupind_meta_data_list = get_meta_data_list_by_category($company_id, 4, 1);
$groupind_checkboxes = get_meta_checkbox_list("Group or Individual",$groupind_meta_data_list,$arr_image_meta_ids);

$dress_meta_data_list = get_meta_data_list_by_category($company_id, 5, 1);
$dress_checkboxes = get_meta_checkbox_list("Dress",$dress_meta_data_list,$arr_image_meta_ids);

$groupindtype_meta_data_list = get_meta_data_list_by_category($company_id, 6, 1);
$groupindtype_checkboxes = get_meta_checkbox_list("Group or Individual Type",$groupindtype_meta_data_list,$arr_image_meta_ids);

$props_meta_data_list = get_meta_data_list_by_category($company_id, 7, 1);
$props_checkboxes = get_meta_checkbox_list("Props",$props_meta_data_list,$arr_image_meta_ids);

$age_meta_data_list = get_meta_data_list_by_category($company_id, 8, 1);
$age_checkboxes = get_meta_checkbox_list("Age",$age_meta_data_list,$arr_image_meta_ids);

$ethnicity_meta_data_list = get_meta_data_list_by_category($company_id, 9, 1);
$ethnicity_checkboxes = get_meta_checkbox_list("Ethnicity",$ethnicity_meta_data_list,$arr_image_meta_ids);

$image_label = "Image#<br>" . $image_id;
if(empty($image_id)){
	$image_label = "New Image";
}
$model_table = "";
if(!empty($image_id)){
	$model_table = "<table border = \"0\" class = \"image_form\"><tr><th colspan = \"2\">Models</th></tr>";
	$arr_models = get_models_for_image($image_id);
	if (!empty($arr_models)){
		foreach ($arr_models as $model_row){
			$model_table .= "<tr><td>" . $model_row["model_name"] . "</td><td><a href = \"del_image_model.php?imid=" . $model_row["image_model_id"] . "&i=" . $image_id . "\">del</a></td></tr>\n";
			
		}
	}

	$model_select = get_model_select_for_image($image_id);

	$model_table .= "<tr><td colspan = \"2\">Add: " . $model_select . "<input type = \"hidden\" name = \"image_id\" value = \"" . $image_id . "\"></td></tr>\n";
	$model_table .= "</table>";
}



?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Image</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
$(document).ready(function(){
	$('#image_container').<?php echo $show_image ?>();
	
	$("#image_container").appendTo("#image_area");
	
	$( ".datepicker" ).datepicker();
	
	$("#model_select").change(function() {
		this.form.submit();
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
				<table border = "0" width = "60%">
					<tr>

						<td  width = "50">
							<a href = "image.php">new</a>
						</td>
						<td  width = "50">
							<?php echo $prev_button ?>
						</td>
						<td  width = "50">
							<?php echo $next_button ?>
						</td>
						
					</tr>
				</table>
				
				<div class = "error"><?php echo $error_message ?></div>
				
				<table width = "60%" border = "0">
					<tr>
						<td valign = "top" align="right">
							<h2><?php echo $image_label ?></h2>
						</td>
						<td align="center">
							<div id = "image_area">
									
								</div>
						</td>
						<td valign="top">
							<form action = "add_image_model.php" method = "POST">
							<?php echo $model_table  ?>
							</form>
						</td>
					</tr>
				</table>
				<form id = "project_form" action = "update_image.php" method = "POST">
					<table border = "0">
						<tr>
							<td valign="top">
								<table class = "image_form" border = "0" width = "100%">
									<tr>
										<td colspan = "2">
										<h2>Image Info</h2>
											<input type = "checkbox" name = "active" value = "1" <?php echo $active_checked ?>> Active
										</td>
									</tr>
									<tr>
									<th colspan = "2">
										Image Dimensions
									</th>
									</tr>
									<tr>
										<td valign="top">Width</td>
										<td><input type = "text" name = "width" value = "<?php echo $width ?>" class = "required" size = "4"> px</td>
									</tr>
									<tr>
										<td valign="top">Height</td>
										<td><input type = "text" name = "height" value = "<?php echo $height ?>" class = "required" size = "4"> px</td>
									</tr>
									<tr>
										<td valign="top">File Size</td>
										<td><input type = "text" name = "file_size" value = "<?php echo $file_size ?>" class = "required" size = "4"> MB</td>
									</tr>
									<tr>
										<td valign="top">Resolution</td>
										<td><input type = "text" name = "resolution" value = "<?php echo $resolution ?>" class = "required" size = "4"> dpi</td>
									</tr>
									<th colspan = "2">
										Image Source Info
									</th>
									<tr>
										<td valign="top">Stock Ref. Code</td>
										<td><input type = "text" name = "stock_ref_code" value = "<?php echo $stock_ref_code ?>" class = "required" size = "8"></td>
									</tr>
									<tr>
										<td valign="top">Image Stock Name</td>
										<td><input type = "text" name = "image_stock_name" value = "<?php echo $image_stock_name ?>" class = "required" size = "15"></td>
									</tr>
									<tr>
										<td valign="top">Stock Quote ID</td>
										<td><input type = "text" name = "stock_quote_id" value = "<?php echo $stock_quote_id ?>" class = "required" size = "8"></td>
									</tr>
									<tr>
										<td valign="top">Stock/Photographer:</td>
										<td><?php echo $stock_or_photographer_select ?></td>
									</tr>
									<tr>
										<td valign="top">Rep/Stock house:</td>
										<td><?php echo $rep_stock_house_select ?></td>
									</tr>
									<tr>
										<td valign="top">Photographer Name</td>
										<td><input type = "text" name = "photographer_name" value = "<?php echo $photographer_name ?>" class = "required" size = "15"></td>
									</tr>
									<tr>
										<td valign="top">Rep/Stock house:</td>
										<td><?php echo $rights_managed_type_select  ?></td>
									</tr>
									<tr>
										<td valign="top">Royalty Free Type:</td>
										<td><?php echo $royalty_free_type_select  ?></td>
									</tr>
									<tr>
										<td valign="top">Image Media Rights:</td>
										<td><?php echo $media_rights_checkboxes ?></td>
									</tr>
									<tr>
										<td valign="top">Media Rights Other:</td>
										<td><input type = "text" name = "image_media_rights_other" value = "<?php echo $image_media_rights_other ?>" class = "required" size = "8"></td>
									</tr>
									<tr>
										<td valign="top">Image Notes:</td>
										<td><textarea name = "image_notes" rows = "4" cols = "30"><?php echo $image_notes ?></textarea></td>
									</tr>
								</table>
							
								<table class = "image_form" border = "0" width = "100%">
									<th colspan = "2">
										Apollo Usage
									</th>
									<tr>
										<td valign="top">Image Usage Start</td>
										<td><input type = "text" name = "image_usage_start" value = "<?php echo $image_usage_start ?>" class = "required datepicker" size = "6"></td>
									</tr>
									<tr>
										<td valign="top">Image Usage End</td>
										<td><input type = "text" name = "image_usage_end" value = "<?php echo $image_usage_end ?>" class = "required datepicker" size = "6"></td>
									</tr>
									<tr>
										<td valign="top">Unlimited Usage?:</td>
										<td><input type = "checkbox" name = "unlimited_usage" value = "1" <?php echo $unlimited_usage_checked ?>></td>
									</tr>
									<tr>
										<td valign="top">Image Territory:</td>
										<td><?php echo $image_territory_radio ?></td>
									</tr>
									<tr>
										<td valign="top">Image Territory Other:</td>
										<td><input type = "text" name = "image_territory_other" value = "<?php echo $image_territory_other ?>" class = "required" size = "8"></td>
									</tr>
									<tr>
										<td valign="top">Release Received?</td>
										<td><?php echo $release_received_radio ?></td>
									</tr>
									<tr>
										<td valign="top">Release Type:</td>
										<td><input type = "text" name = "release_type" value = "<?php echo $release_type ?>" class = "required" size = "8"></td>
									</tr>
									<tr>
										<td valign="top">Image Exclusivity:</td>
										<td><?php echo $image_exclusivity_radio ?></td>
									</tr>
									<tr>
										<td valign="top">Exclusivity Notes:</td>
										<td><input type = "text" name = "exclusivity_notes" value = "<?php echo $exclusivity_notes ?>" class = "required" size = "8"></td>
									</tr>
									<tr>
										<td valign="top">Image Usage Category:</td>
										<td><?php echo $usage_category_checkbox_list ?></td>
									</tr>
									<tr>
										<td valign="top">Other Image Category:</td>
										<td><input type = "text" name = "image_usage_category_other" value = "<?php echo $image_usage_category_other ?>" class = "required" size = "8"></td>
									</tr>
									<tr>
										<td valign="top">Original Project Code</td>
										<td><input type = "text" name = "original_project_code" value = "<?php echo $original_project_code ?>" class = "required" size = "8"></td>
									</tr>
									<tr>
										<td valign="top">Original Project Manager</td>
										<td><input type = "text" name = "original_project_manager" value = "<?php echo $original_project_manager ?>" class = "required" size = "15"></td>
									</tr>
									<tr>
										<td valign="top">Original Art Buyer</td>
										<td><input type = "text" name = "original_art_buyer" value = "<?php echo $original_art_buyer ?>" class = "required" size = "15"></td>
									</tr>
								</table>
								<table class = "image_form" border = "0" width = "100%">
									<th colspan = "2">
										Asset Storage
									</th>
									<tr>
										<td valign="top">Posting to Asset Library?</td>
										<td><?php echo $posting_to_asset_library_radio ?></td>
									</tr>
									<tr>
										<td valign="top">High Resolution Location</td>
										<td><?php echo $high_resolution_location_checkbox ?></td>
									</tr>
									<tr>
										<td valign="top">Image needs retouching before use?</td>
										<td><?php echo $image_needs_retouching_radio ?></td>
									</tr>
									<tr>
										<td valign="top">Image has been replaced?</td>
										<td><?php echo $image_has_been_replaced_radio ?></td>
									</tr>
									<tr>
										<td>
										<input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
										<input type = "hidden" name = "image_id" value = "<?php echo $image_id ?>">

										<input type = "submit" value = "<?php echo $button_text ?>"></td>
										<input type = "hidden" name = "current_meta_data" value = "<?php echo $all_meta_data_list ?>">
										<td>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td valign="top">
								<table border = "0" class = "image_form">
									<tr>
										<td>
										<h2>Meta Data</h2>
										<br>
										<?php echo $program_checkboxes ?>
										<br>
										<?php echo $setting_checkboxes ?>
										<br>
										<?php echo $gender_checkboxes ?>
										<br>
										<?php echo $groupind_checkboxes ?>
										<br>
										<?php echo $dress_checkboxes ?>
										<br>
										<?php echo $groupindtype_checkboxes ?>
										<br>
										<?php echo $props_checkboxes ?>
										<br>
										<?php echo $age_checkboxes ?>
										<br>
										<?php echo $ethnicity_checkboxes ?>
										</td>
									</tr>
								</table>
							</td>
							</tr>
						</td>
					</table>
				
				</form>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>
	<div id = "image_container">
		<form action = "add_image_file.php" method = "POST" enctype="multipart/form-data">
		<?php echo $image_file  ?>
		<br>
		<input type = "hidden" name = "image_id" value = "<?php echo $image_id ?>">
		<input type="file" name="file" id="file" value = "add image">
		<input type = "submit" value = "add">
		</form>
	</div>
</div>
</body>
</html>