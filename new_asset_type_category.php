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
		$error_message = "Asset Type Category name or abbreviation exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Asset Type Category Added.";
	}
}

$edit_mode=0;
$current_atcid = 0;
if (!empty($_GET["atcid"])){
	$edit_mode = 1;
	$current_atcid=$_GET["atcid"];
}


$arr_asset_type_category = get_asset_type_categories($company_id, 1);
$asset_type_category_table = "<table width = \"400\" class = \"budget\"><tr><th colspan = \"4\">Current Asset Type Categories</th></tr><tr><th>Asset Type Category Name</th><th>Abbrev.</th><th colspan = \"2\">&nbsp;</th></tr>";
if (!empty($arr_asset_type_category)){
	foreach ($arr_asset_type_category as $asset_type_category_row){
		$asset_type_category_id = $asset_type_category_row["asset_type_category_id"];
		$asset_type_category_name= $asset_type_category_row["asset_type_category_name"];
		$asset_type_category_abbrev= $asset_type_category_row["asset_type_category_abbrev"];
		if ($current_atcid == $asset_type_category_id){
			$asset_type_category_table .= "<tr><form method = \"POST\" action = \"update_asset_type_category.php\" id=\"edit_asset_type_category\"><td><a name = \"atcid" . $asset_type_category_id . "\"><input type = \"text\" name = \"asset_type_category_name\" value = \"" . $asset_type_category_name . "\" class=\"required\"></td><td align=\"left\"><input type = \"text\" name = \"asset_type_category_abbrev\" value = \"" . $asset_type_category_abbrev . "\" maxlength=\"4\"></td><td colspan = \"2\"><input type = \"hidden\" name = \"atcid\" value = \"" . $asset_type_category_id . "\" class=\"required\"><input type = \"submit\" value = \"update\"></td></form></tr>\n";
		}else{
			$asset_type_category_table .= "<tr><td>" . $asset_type_category_name . "</td><td>" . $asset_type_category_abbrev . "</td><td><a href = \"new_asset_type_category.php?atcid=" . $asset_type_category_id . "\">edit</a><td><a href = \"activate_asset_type_category.php?a=2&atcid=" . $asset_type_category_id . "\">del</a></td></tr>";
		}
	
	}
}
$asset_type_category_table .= "</table>";

$arr_asset_type_category2 = get_asset_type_categories($company_id, 0);
$asset_type_category_table2 = "<table width = \"400\" class = \"budget\"><tr><th colspan = \"3\">Inactive Asset Type Categories</th></tr><tr><th>Asset Type Category Name</th><th>Abbrev.</th><th>&nbsp;</th></tr>";
if (!empty($arr_asset_type_category2)){
	foreach ($arr_asset_type_category2 as $asset_type_category_row){
		$asset_type_category_id = $asset_type_category_row["asset_type_category_id"];
		$asset_type_category_name= $asset_type_category_row["asset_type_category_name"];
		$asset_type_category_abbrev= $asset_type_category_row["asset_type_category_abbrev"];

		$asset_type_category_table2 .= "<tr><td>" . $asset_type_category_name . "</td><td>" . $asset_type_category_abbrev . "</td><td><a href = \"activate_asset_type_category.php?a=1&atcid=" . $asset_type_category_id . "\">activate</a></td></tr>";
	
	}
}
$asset_type_category_table2 .= "</table>";

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Manage Asset Type Categories</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#asset_category_form" ).validate({
  rules: {
    task_rate: {
      required: false,
      number: true
    }
  }
});
	
	$( "#edit_asset_type_category" ).validate({
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
				<h1>Asset Type Categories</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Asset Type Category:<form id = "asset_category_form" action = "add_asset_category.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Asset Type Category Name:</td>
									<td><input class = "required" type = "text" name = "asset_type_category_name"></td>
								</tr>
								<tr>
									<td>Asset Type Category Abbreviation:</td>
									<td><input id = "asset_type_category_abbrev" class = "required" type = "text" name = "asset_type_category_abbrev" size="2" maxlength="4"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add Asset Type Category"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $asset_type_category_table ?>
							<br><br>
							<?php echo $asset_type_category_table2 ?>
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