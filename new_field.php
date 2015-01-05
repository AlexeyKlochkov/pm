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
		$error_message = "Duplicate campaign for the chosen business unit and quarter.";
	}
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
				<h1>Projects</h1>
				
				New Field:<br><div class = "error"><?php echo $error_message ?></div>
				<form id = "project_form" action = "add_field.php" method = "POST">
					<table class = "form_table">
						<tr>
							<td>Field Name:</td>
							<td><input class = "required" type = "text" name = "field_name"></td>
						</tr>
						<tr>
							<td>Field Type:</td>
							<td>
								<select name = "display_type">
									<option value = "text">Text Field</option>
									<option value = "radio">Radio Button</option>
									<option value = "check">Check Box</option>
									<option value = "pulldown">Pull-Down Menu</option>
									<option value = "textarea">Text Area</option>
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