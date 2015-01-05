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
		$error_message = "Audience name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Audience Added.";
	}
}

$arr_audience = get_audience($company_id, 1);
$audience_table = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Current Audiences</th></tr>";
if (!empty($arr_audience)){
	foreach ($arr_audience as $audience_row){
		$audience_id = $audience_row["audience_id"];
		$audience_name = $audience_row["audience_name"];
		$audience_table .= "<tr><td>" . $audience_name . "</td><td><a href = \"activate_audience.php?a=2&aid=" . $audience_id . "\">del</a></td></tr>";
	}
}
$audience_table .= "</table>";

$arr_retired_audiences = get_audience($company_id, 2);
$audience_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Retired audiences</th></tr>";
if (!empty($arr_retired_audiences)){
	foreach ($arr_retired_audiences as $audience_row){
		$audience_id = $audience_row["audience_id"];
		$audience_name = $audience_row["audience_name"];
		$audience_table2 .= "<tr><td>" . $audience_name . "</td><td><a href = \"activate_audience.php?a=1&aid=" . $audience_id . "\">activate</a></td></tr>";
	
	}
}
$audience_table2 .= "</table>";

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New audience</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#audience_form" ).validate({
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
				<h1>audiences</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Audience:<form id = "audience_form" action = "add_audience.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Audience Name:</td>
									<td><input class = "required" type = "text" name = "audience_name"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add Audience"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $audience_table ?>
							<br><br>
							<?php echo $audience_table2 ?>
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