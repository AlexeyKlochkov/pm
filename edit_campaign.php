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
		$error_message = "Error.";
	}

}
if ($_SESSION["user_level"] < 20){
	$location = "Location: loggedout.php";
	header($location) ;
}

$campaign_id = 0;
if (!empty($_GET["c"])){
	$campaign_id = $_GET["c"];
}
$arr_campaign = get_campaign_info($campaign_id);
//print_r($arr_campaign);
$business_unit_id = $arr_campaign[0]["business_unit_id"];
$campaign_description = $arr_campaign[0]["campaign_description"];
$campaign_quarter = $arr_campaign[0]["campaign_quarter"];
$campaign_year = $arr_campaign[0]["campaign_year"];
$campaign_budget = $arr_campaign[0]["campaign_budget"];
$campaign_code = $arr_campaign[0]["campaign_code"];
$campaign_active = $arr_campaign[0]["active"];
if ($campaign_active == 1){
	$active_checked = "checked";
}else{
	$active_checked = "";
}


$business_unit_select = get_business_unit_select($company_id, $business_unit_id);
$quarter_select = get_quarter_select($campaign_quarter);
$year_select = get_year_select($campaign_year);
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit Campaign</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#project_form").validate();
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
				<h1>Update AOP Line of Business</h1>
				<h3>AOP Line of Business: <?php echo $campaign_code ?></h3>
				<div class = "error"><?php echo $error_message ?></div>
				
					
				<form id = "campaign_form" action = "update_campaign.php" method = "POST">
					<table class = "form_table">
						<tr>
							<td>
								Line of Business:
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
								<textarea name = "campaign_description"><?php echo $campaign_description  ?></textarea>
							</td>
						</tr>
						<tr>
							<td>
								Quarter:
							</td>
							<td>
							<?php echo $quarter_select  ?>
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
								<input type = "text" name = "campaign_budget" class="required" value = "<?php echo $campaign_budget  ?>">
							</td>
						</tr>
						<tr>
							<td>
								Active:
							</td>
							<td>
								<input type = "checkbox" name = "active" value = "1" <?php echo $active_checked ?>>
							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
							<td>
								<input type = "hidden" name = "campaign_id" value = "<?php echo $campaign_id ?>">
								<input type = "submit" value = "Update Campaign">
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