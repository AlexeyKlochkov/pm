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
		$error_message = "There was a problem.";
	}
	if ($error_num == 2){
		$error_message = "Field updated.";
	}
}

if (!empty($_GET["f"])){
	$field_id = $_GET["f"];
}

$arr_field = get_field_info($field_id);
//print_r($arr_field);
$field_name = $arr_field[0]["field_name"];
$field_type = $arr_field[0]["display_type"];
$active = $arr_field[0]["active"];

if ($active == 1){
	$checked = "checked";
}else{
	$checked = "";
}

$add_choices = 0;

if ($field_type == "radio" | $field_type == "check" | $field_type == "pulldown"){
	$add_choices = 1;
}
//print $add_choices;

$choice_form = "";

if ($add_choices == 1){
	$choice_form = "<table class = \"form_table\" width = \"100%\">";
	$choice_form .= "<tr><td><b>Add/Delete Choices</b></td></tr>";
	
	$choice_form .= "<tr><form action = \"add_choice.php\" method = \"POST\"><td><input type = \"text\" name = \"field_choice_name\"></td>";
	$choice_form .= "<td><input type = \"hidden\" name = \"field_id\" value = \"" . $field_id . "\"><input type = \"submit\" value = \"add\"></td></form></tr>";
	$choice_form .= "<table>";
	
	$arr_choice_list = get_field_choices($field_id);
	print_r($arr_choice_list);
	
}

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Field</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#project_form").validate();
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
				<h1>Edit Field</h1>
				
				Field Type: <b><?php echo $field_type ?></b><br><div class = "error"><?php echo $error_message ?></div>
				<form id = "project_form" action = "update_field.php" method = "POST">
					<table border = "1">
						<tr>
							<td valign = "top">
					<table class = "form_table">
						<tr>
							<td>Field Name:</td>
							<td><input class = "required" type = "text" name = "field_name" value = "<?php echo $field_name ?>"></td>
						</tr>
						<tr>
							<td>Active:</td>
							<td>
								<input <?php echo $checked ?> type = "checkbox" name = "active" value = "1">
							</td>
						</tr>
						<tr>
							<td>
							<input type = "hidden" name = "field_id" value = "<?php echo $field_id ?>">
							<input type = "submit" value = "Update Field"></td></form>
							<td>&nbsp;</td>
						</tr>
					</table>
							</td>
							<td valign = "top">
								<?php echo $choice_form ?>
							</td>
						</tr>
					</table>
				
				</form>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>