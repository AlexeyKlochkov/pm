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
		$error_message = "Phase name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Phase Added.";
	}
}

$arr_phase = get_phases($company_id, 1);
$phase_table = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Current Phases</th></tr>";
if (!empty($arr_phase)){
	foreach ($arr_phase as $phase_row){
		$phase_id = $phase_row["phase_id"];
		$phase_name = $phase_row["phase_name"];
		$phase_table .= "<tr><td>" . $phase_name . "</td><td><a href = \"edit_phase.php?p=" . $phase_id . "\">edit</a></td><td><a href = \"activate_phase.php?a=2&p=" . $phase_id . "\">del</a></td></tr>";
	
	}
}
$phase_table .= "</table>";


$arr_retired_phases = get_phases($company_id, 0);
$phase_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"2\">Retired Phases</th></tr>";
if (!empty($arr_retired_phases)){
	foreach ($arr_retired_phases as $phase_row){
		$phase_id = $phase_row["phase_id"];
		$phase_name = $phase_row["phase_name"];
		$phase_table2 .= "<tr><td>" . $phase_name . "</td><td><a href = \"activate_phase.php?a=1&p=" . $phase_id . "\">activate</a></td></tr>";
	
	}
}
$phase_table2 .= "</table>";

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Phase</title>
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
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Phase:<form id = "phase_form" action = "add_phase.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Phase Name:</td>
									<td><input class = "required" type = "text" name = "phase_name"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add Phase"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $phase_table ?>
							<br><br>
							<?php echo $phase_table2 ?>
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