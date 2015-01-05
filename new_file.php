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
		$error_message = "Duplicate file name.";
	}
}

$project_id = 0;

if (!empty($_GET["p"])){
	$project_id = $_GET["p"];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "https://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Add File</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#file_form").validate();
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
				<h1>Add File</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				<form id = "file_form" action = "add_file.php" method = "POST" enctype="multipart/form-data">
					<table class = "form_table">
						<tr>
							<td>Select File:</td>
							<td><input type="file" name="file" id="file">
							</td>
						</tr>
						<tr>
							<td>File Notes:</td>
							<td>
								<input class = "required" type = "text" name = "file_notes">
							</td>
						</tr>
						<tr>
							<td>
							<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
							<input type = "submit" value = "Add File"></td>
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