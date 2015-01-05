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
		$error_message = "Please select a business unit.";
	}
		if ($error_num == 2){
		$error_message = "Duplicate campaign for the chosen business unit and year.";
	}
}

$business_unit_select = get_business_unit_select($company_id, 0);
//$quarter_select = get_quarter_select(0);
//$quarter_select = str_replace("<option value = \"\">All</option>", "", $quarter_select);
$year_select = get_year_select(0);
$year_select = str_replace("<option value = \"\">All</option>", "", $year_select);
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Add AOP Budget</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#campaign_form").validate();
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
				<h1>New AOP Budget</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					
				<form id = "campaign_form" action = "add_campaign.php" method = "POST">
					<table class = "form_table">
						<tr>
							<td>
								Business Unit:
							</td>
							<td>
								<?php echo $business_unit_select  ?>
							</td>
						</tr>
						<tr>
							<td>
								Description:
							</td>
							<td>
								<textarea name = "campaign_description"></textarea>
							</td>
						</tr>
						<tr>
							<td>
								Year:
							</td>
							<td>
							<?php echo $year_select  ?>
							</td>
						</tr>
						<tr>
							<td>
								Overall Budget:
							</td>
							<td>
								<input type = "text" name = "campaign_budget" class="required">
							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
							<td>
								<input type = "hidden" name = "campaign_quarter" value = "">
								<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
								<input type = "submit" value = "Add Campaign">
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