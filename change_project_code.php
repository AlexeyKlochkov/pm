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
$edit_mode = 0;
if (!empty($_GET["em"])){
	$edit_mode = $_GET["em"];
}

if($edit_mode == 1){
	$project_code = $_GET["project_code"];
	if(empty($project_code)){
		$error_message = "Invalid project code.";
		$edit_mode = 0;
	}else{
		$arr_project_info = get_project_info_by_project_code($project_code);
		if(empty($arr_project_info)){
			$error_message = "Project not found.";
			$edit_mode = 0;
		}else{
			$project_id = $arr_project_info[0]["project_id"];
			$project_name = $arr_project_info[0]["project_name"];
		}
	}
}

if($edit_mode == 2){
	$project_id = $_GET["project_id"];
	$new_project_code_prefix = $_GET["new_project_code"];
	$new_project_code = $new_project_code_prefix . "-" . $project_id;
	$insert_success = insert_project_code($project_id, $new_project_code);
	if($insert_success == 1){
		$error_message = "Project code updated.";
	}else{
		$error_message = "An error occurred.";
	}
	$edit_mode = 0;

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
<?php 
if($edit_mode == 0){
?>
							<form id = "change_project_code" action = "change_project_code.php" method = "GET">
								<table class = "form_table"  width = "400">
									<tr>
										<td>Enter Current Project Code:</td>
										<td><input class = "required" type = "text" name = "project_code"></td>
									</tr>
									
									<tr>
										<td>
										<input type = "hidden" name = "em" value = "1">
										<input type = "submit" value = "Go"></td>
										<td>&nbsp;</td>
									</tr>
								</table>
								<br>
							</form>
<?php
} 
if($edit_mode == 1){
	

?>

							<form id = "change_project_code" action = "change_project_code.php" method = "GET">
								<table class = "form_table" width = "300">
									<tr>
										<td width = "100">Project ID</td>
										<td><?php echo $project_id ?></td>
									</tr>
									<tr>
										<td>Project Name</td>
										<td><?php echo $project_name ?></td>
									</tr>
									<tr>
										<td>Current Project Code</td>
										<td><?php echo $project_code ?></td>
									</tr>
									<tr>
										<td>New Project Code Prefix:</td>
										<td><input class = "required" type = "text" name = "new_project_code" size = "4">-<?php echo $project_id ?></td>
									</tr>
									<tr>
										<td>
										<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
										<input type = "hidden" name = "em" value = "2">
										<input type = "submit" value = "Go"></td>
										<td>&nbsp;</td>
									</tr>
								</table>
								<br>
							</form>
<?php
}
?>
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