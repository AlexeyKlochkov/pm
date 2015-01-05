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
		$error_message = "Error occurred.";
	}
	if ($error_num == 2){
		$error_message = "Asset updated.";
	}
}
$asset_id = 0;

if (!empty($_GET["a"])){
	$asset_id = $_GET["a"];
	//print $asset_id;
}else{
	print "No asset selected.";
}

$asset_info = get_asset_details($asset_id);
//print_r($asset_info);
$asset_name = $asset_info[0]["asset_name"];
$asset_type_id = $asset_info[0]["asset_type_id"];
$asset_budget_media = $asset_info[0]["asset_budget_media"];
$asset_budget_production = $asset_info[0]["asset_budget_production"];
$asset_quantity = $asset_info[0]["asset_quantity"];
$asset_notes = $asset_info[0]["asset_notes"];
$project_code = $asset_info[0]["project_code"];
$project_name = $asset_info[0]["project_name"];
$project_id = $asset_info[0]["project_id"];
$asset_start_date =$asset_info[0]["asset_start_date"];
$asset_end_date = $asset_info[0]["asset_end_date"];
$asset_has_ge = $asset_info[0]["asset_has_ge"];
$asset_for_aps = $asset_info[0]["asset_for_aps"];
$asset_type_select = get_asset_type_select($company_id, $asset_type_id);

if(!empty($asset_start_date)){
	$asset_start_date = convert_mysql_to_datepicker($asset_start_date);
}

if(!empty($asset_end_date)){
	$asset_end_date = convert_mysql_to_datepicker($asset_end_date);
}

if (empty($asset_quantity)){
	$asset_quantity = 1;
}

$has_ge_checked = "";
if ($asset_has_ge == 1){
	$has_ge_checked = " checked";
}

$for_aps_checked = "";
if ($asset_for_aps == 1){
	$for_aps_checked = " checked";
}

$today_datepicker = date( 'm/d/Y');
$one_year_from_now = date('m/d/Y', strtotime('+1 years'));

//print $today_datepicker;
//print $one_year_from_now;
if(empty($asset_start_date)){
	$asset_start_date = $today_datepicker;
}

if(empty($asset_end_date)){
	$asset_end_date = $one_year_from_now;
}

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit Asset</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#asset_form").validate();
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
				<h1>Edit Asset</h1>
				<h3>Project: <a href = "manage_project.php?p=<?php echo $project_id ?>&showassets=1#assets"><?php echo $project_name ?> (<?php echo $project_code ?>)</a>
				<div class = "error"><?php echo $error_message ?></div>
				<form id = "asset_form" action = "update_asset.php" method = "POST">
					<table class = "form_table">
						<tr>
							<td>Asset Name</td>
							<td><input class = "required" type = "text" name = "asset_name" value = "<?php echo $asset_name ?>"></td>
						</tr>
						<tr>
							<td>Asset Type:</td>
							<td><?php echo $asset_type_select ?></td>
						</tr>
						<tr>
							<td>Asset Quantity:</td>
							<td><input class = "required" type = "text" name = "asset_quantity" value = "<?php echo $asset_quantity ?>"></td>
						</tr>
						<tr>
							<td>In-Market Date:</td>
							<td><input type = "text" name = "asset_start_date" value = "<?php echo $asset_start_date ?>" class = "required datepicker" size = "6"></td>
						</tr>
						<tr>
							<td>Expiration Date:</td>
							<td><input type = "text" name = "asset_end_date" value = "<?php echo $asset_end_date ?>" class = "required datepicker" size = "6"></td>
						</tr>
						<tr>
							<td valign="top">Asset Has GE:</td>
							<td><input type = "checkbox" name = "asset_has_ge" value = "1" <?php echo $has_ge_checked ?>></td>
						</tr>
						<tr>
							<td valign="top">Asset is for APS:</td>
							<td><input type = "checkbox" name = "asset_for_aps" value = "1" <?php echo $for_aps_checked ?>></td>
						</tr>
						<tr>
							<td valign="top">Asset Notes:</td>
							<td><textarea name = "asset_notes" style="width: 500px; height: 150px;"><?php echo $asset_notes ?></textarea></td>
						</tr>

						<tr>
							<td>
							<input type = "hidden" name = "asset_budget_media" value = "0">
							<input type = "hidden" name = "asset_budget_production" value = "0">
							<input type = "hidden" name = "asset_id" value = "<?php echo $asset_id ?>">
							<input type = "hidden" name = "project_id" value = "<?php echo $project_id?>">
							<input type = "submit" value = "Update Asset"></td>
							<td>&nbsp;</td>
						</tr>
					</table>
				
				</form>
				<table border = "0" width = "90%">
					<tr>
						<td align="right">
						<form action = "del_asset.php" method = "POST">
							<input type = "hidden" name = "asset_id" value = "<?php echo $asset_id?>">
							<input type = "hidden" name = "project_id" value = "<?php echo $project_id?>">
							<input type = "submit" value = "delete this asset">
						</form>
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