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
		$error_message = "Task name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Task Updated.";
	}
}
if (!empty($_GET["p"])){
	$project_id = $_GET["p"];
}

$arr_project_info = get_project_info($project_id);
//print_r($arr_task_info );
$project_code = $arr_project_info[0]["project_code"];
$project_name = $arr_project_info[0]["project_name"];

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Copy Project</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#task_form" ).validate({
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
				<h1>Copy Project</h1>
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							Copy Project: <?php echo $project_code ?>
							
							<form id = "project_copy" action = "add_project_copy.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Project Name:</td>
									<td>
										<input class = "required" type = "text" name = "project_name" value = "<?php echo $project_name ?>">
										<input type = "hidden" name = "project_id" value = "<?php echo $project_id ?>">
										<input type = "submit" value = "copy">
									</td>
								</tr>
								
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
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