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
		$error_message = "Please select a business unit.";
	}
		if ($error_num == 2){
		$error_message = "Duplicate campaign for the chosen business unit and quarter.";
	}
}
$sub_button = "";
$model_id = 0;
if (!empty($_GET["m"])){
	$model_id = $_GET["m"];
	$button_text = "Update Model";
}else{
	$button_text = "Add Model";
}
$image_file = "";
$show_model_image = "hide";
$prev_button = "";
$next_button = "";

$model_name = "";
$model_email = "";
$model_address= "";
$model_phone= "";
$model_notes= "";
$model_active_checked = "";
$model_gender = "";
$model_is_minor = "";
$model_territory = "";
$model_territory_other = "";
$model_usage_category_list = "";
$model_usage_category_other = "";
$model_start_date = "";
$model_end_date = "";
$representation_type = "";
$agency_id = "";
$model_released = "";
$duration_type = "";
$media_rights_list = "";
$arr_media_rights_list = array();
$arr_usage_category_selected = array();
$media_rights_other = "";

if(!empty($model_id)){
	$arr_model = get_model_info($model_id);
	if (!empty($arr_model)){
		$model_name = $arr_model[0]["model_name"];
		$model_email = $arr_model[0]["model_email"];
		$model_address = $arr_model[0]["model_address"];
		$model_phone = $arr_model[0]["model_phone"];
		$model_notes = $arr_model[0]["model_notes"];
		$model_gender = $arr_model[0]["model_gender"];
		$model_is_minor = $arr_model[0]["model_is_minor"];
		$model_territory = $arr_model[0]["model_territory"];
		$model_territory_other = $arr_model[0]["model_territory_other"];
		$model_usage_category_list = $arr_model[0]["model_usage_category"];
		$model_usage_category_other = $arr_model[0]["model_usage_category_other"];
		$media_rights_other = $arr_model[0]["media_rights_other"];
		$model_start_date = $arr_model[0]["model_start_date"];
		$model_end_date = $arr_model[0]["model_end_date"];
		$representation_type = $arr_model[0]["representation_type"];
		$agency_id = $arr_model[0]["agency_id"];
		$model_released = $arr_model[0]["model_released"];
		$duration_type = $arr_model[0]["duration_type"];
		$media_rights_list = $arr_model[0]["media_rights"];
		$media_rights_other = $arr_model[0]["media_rights_other"];
		$arr_media_rights_list = explode(",",$media_rights_list);
		$arr_usage_category_selected = explode(",",$model_usage_category_list);
		
		
	}
	//check if file exists
	$filename = "images/models/m" . $model_id . ".jpg";
	if (file_exists($filename)) {
		$image_file = "<img src = \"" . $filename . "\">";
		
	}else{
		$image_file = "<img src = \"images/models/no_image.jpg\">";
	}
	$show_model_image = "show";
	//handle next and prev buttons
	
	
	$prev_button = "";
	$next_button = "";


	if($model_id <> 1){
		$prev_model = ($model_id -1);
		$prev_button = "<a href = \"model.php?m=" . $prev_model . "\">prev</a>";
	}

}
$max_model_id = get_max_model_id();
if($model_id <> $max_model_id){
	$next_model = ($model_id +1);
	$next_button = "<a href = \"model.php?m=" . $next_model . "\">next</a>";
}



$arr_gender = array("Male","Female");
$gender_radio = get_generic_radio($arr_gender, "model_gender", $model_gender);

$arr_model_is_minor = array("Yes","No");
$model_is_minor_radio = get_generic_radio($arr_model_is_minor, "model_is_minor", $model_is_minor);

$arr_model_territory = array("US Only","US Only (Inc. Mil Bases", "World Wide", "Other");
$model_territory_radio = get_generic_radio($arr_model_territory, "model_territory", $model_territory);

$arr_representation_type = array("Agency Representation","Individual");
$representation_type_radio = get_generic_radio($arr_representation_type, "representation_type", $representation_type);

$arr_model_released = array("Yes","No");
$model_released_radio = get_generic_radio($arr_model_released, "model_released", $model_released);

$arr_duration_type = array("Limited","Unlimited", "Buyout");
$duration_type_radio = get_generic_radio($arr_duration_type, "duration_type", $duration_type);

$arr_media_rights = array("All Media","Excl. Broadcast", "Print Media", "OOH", "Web", "Other");
$media_rights_checkboxes = get_generic_checkboxes($arr_media_rights, $arr_media_rights_list, "MR");

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

$agency_select = get_agency_select($company_id, $agency_id);


?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Model</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
$(document).ready(function(){
	$('#image_container').<?php echo $show_model_image ?>();
	
	$("#image_container").appendTo("#image_area");
	
	$( ".datepicker" ).datepicker();
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
				<table border = "0" width = "25%">
					<tr>
						<td width = "100"><h1>Model</h1>
						</td>
						<td  width = "50">
							<a href = "model.php">new</a>
						</td>
						<td  width = "50">
							<?php echo $prev_button ?>
						</td>
						<td  width = "50">
							<?php echo $next_button ?>
						</td>
						
					</tr>
				</table>
				
				Model: <?php echo $model_name ?><br><div class = "error"><?php echo $error_message ?></div>
				<form id = "project_form" action = "update_model.php" method = "POST">
					<table border = "0">
						<tr>
							<td valign="top">
								<table class = "image_form" border = "0" width = "100%">
									<th colspan = "2">
										Personal Info
									</th>
									<tr>
										<td valign="top">Model Name:</td>
										<td><input type = "text" name = "model_name" value = "<?php echo $model_name ?>" class = "required"></td>
									</tr>
									<tr>
										<td valign="top">Email</td>
										<td><input type = "text" name = "model_email" value = "<?php echo $model_email ?>" class = "required"></td>
									</tr>
									<tr>
										<td valign="top">Address:</td>
										<td><textarea name = "model_address" rows = "4" cols = "30"><?php echo $model_address ?></textarea></td>
									</tr>
									<tr>
										<td valign="top">Phone:</td>
										<td><input type = "text" name = "model_phone" value = "<?php echo $model_phone ?>" class = "required"></td>
									</tr>
									<tr>
										<td valign="top">Notes:</td>
										<td><textarea name = "model_notes" rows = "4" cols = "30"><?php echo $model_notes ?></textarea></td>
									</tr>
									<tr>
										<td valign="top">Gender:</td>
										<td><?php echo $gender_radio ?></td>
									</tr>
									<tr>
										<td valign="top">Model Is a mior?</td>
										<td><?php echo $model_is_minor_radio ?></td>
									</tr>
								</table>
							</td>
							<td valign="top" align = "center">
								<div id = "image_area">
									
								</div>
							</td>
						</tr>
						<tr>
							<td valign="top">
								<table class = "image_form" border = "0" width = "100%">
									<th colspan = "2">
										Apollo Usage
									</th>
									<tr>
										<td valign="top">Model Territory?</td>
										<td><?php echo $model_territory_radio ?><br>Other: <input type = "text" name = "model_territory_other" value = "<?php echo $model_territory_other ?>" ></td>
									</tr>
									<tr>
										<td valign="top">Usage Category:</td>
										<td><?php echo $usage_category_checkbox_list ?><br>Other: <input type = "text" name = "model_usage_category_other" value = "<?php echo $model_usage_category_other ?>" ></td>
									</tr>
								</table>
							</td>
							<td valign="top">
								<table class = "image_form" border = "0" width = "100%">
									<th colspan = "2">
										General Usage Info
									</th>
									<tr>
										<td valign="top">Model Usage Start Date:</td>
										<td><input type = "text" name = "model_start_date" value = "<?php echo $model_start_date ?>" class = "required datepicker" size = "6"></td>
									</tr>
									<tr>
										<td valign="top">Model Usage End Date:</td>
										<td><input type = "text" name = "model_end_date" value = "<?php echo $model_end_date ?>" class = "required datepicker" size = "6"></td>
									</tr>
									<tr>
										<td valign="top">Representation Type:</td>
										<td><?php echo $representation_type_radio ?></td>
									</tr>
									<tr>
										<td valign="top">Agency:</td>
										<td><?php echo $agency_select ?></td>
									</tr>
									<tr>
										<td valign="top">Model Released?</td>
										<td><?php echo $model_released_radio ?></td>
									</tr>
									<tr>
										<td valign="top">Duration Type:</td>
										<td><?php echo $duration_type_radio ?></td>
									</tr>
									<tr>
										<td valign="top">Media Rights:</td>
										<td><?php echo $media_rights_checkboxes  ?><br>Other: <input type = "text" name = "media_rights_other" value = "<?php echo $media_rights_other ?>" ></td>
									</tr>
									
									<tr>
										<td>
										<input type = "hidden" name = "user_id" value = "<?php echo $user_id ?>">
										<input type = "hidden" name = "model_id" value = "<?php echo $model_id ?>">

										<input type = "submit" value = "<?php echo $button_text ?>"></td>

										<td>&nbsp;</td>
									</tr>
								</table>
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
		<form action = "add_model_file.php" method = "POST" enctype="multipart/form-data">
		<?php echo $image_file  ?>
		<br><br>
		<input type = "hidden" name = "model_id" value = "<?php echo $model_id ?>">
		<input type="file" name="file" id="file" value = "add image">
		<input type = "submit" value = "add">
		</form>
	</div>
</div>
</body>
</html>