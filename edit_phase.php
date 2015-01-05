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
		$error_message = "Phase name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Phase Updated.";
	}
}
if (!empty($_GET["p"])){
	$phase_id = $_GET["p"];
}

$arr_phase_info = get_phase_info($phase_id);
//print_r($arr_phase_info );
$phase_name = $arr_phase_info[0]["phase_name"];
$active = $arr_phase_info[0]["active"];

if ($active == 1){
	$active_checked = "checked";
}else{
	$active_checked = "";
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
    
	$( "#phase_form" ).validate();

	
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
				<h1>Phases</h1>
				<a href = "new_phase.php">All Phases</a><br><br>
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							Edit Phase:<form id = "phase_form" action = "update_phase.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Phase Name:</td>
									<td><input class = "required" type = "text" name = "phase_name" value = "<?php echo $phase_name ?>"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "phase_id" value = "<?php echo $phase_id ?>">
									<input type = "submit" value = "Update Phase"></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>Active:</td>
									<td><input type = "checkbox" <?php echo $active_checked ?> name = "active" value = "1"></td>
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