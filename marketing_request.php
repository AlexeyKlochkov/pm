<?php
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
date_default_timezone_set('America/Los_Angeles');
//print $company_id;
$error_message = "";
$active_flag = 1;
if (!empty($_GET["wif_type_id"])){
	$wif_type = $_GET["wif_type_id"];
	$arr_wif_type = explode("-", $wif_type);
	$wif_type_id = $arr_wif_type[0];
	$is_web_request = $arr_wif_type[1];
	if ($is_web_request == 7){
		$location = "Location: new_aop.php";
	}elseif ($is_web_request == 8){
		$location = "Location: new_up.php";
	}elseif ($is_web_request == 9 || $is_web_request == 10){
        $location = "Location: new_wif.php?wtid=" . $wif_type_id;
    }
	header($location);
	
}

$wif_status_select = get_wif_type_select(2,0);
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Marketing Request</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#request_form").validate();
	$( "#wif_type_select" ).change(function() {
	  $( "#request_form" ).submit();
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
		<!--container div tag--> 
		<div id="container"> 
			
			<div id="mainContent"> <!--mainContent div tag--> 
				<h1>Apollo Marketing Services</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				<form id = "request_form" action = "marketing_request.php" method = "GET">
					<table class = "form_table" width = "600">
						<tr>
							<td align="left"><h2>Welcome to the Marketing Project Brief. <br><br>

							Please let us know how we can help you, by choosing one of the following services below.<br>
							<br><?php echo $wif_status_select ?></h2><br>
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
