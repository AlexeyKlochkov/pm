<?php 

function get_wif_email($wif_id){
//$company_id =$_SESSION["company_id"];
$arr_wif = get_wif_info($wif_id);
if (!empty($arr_wif)){
	$wif_code = $arr_wif[0]["wif_code"];
	$wif_name = $arr_wif[0]["wif_name"];
	$request_date = convert_mysql_to_datepicker($arr_wif[0]["request_date"]);
	$desired_delivery_date = convert_mysql_to_datepicker($arr_wif[0]["desired_delivery_date"]);
	$requester_name = $arr_wif[0]["requester_name"];
	$requester_email = $arr_wif[0]["requester_email"];
	$wif_type_id = $arr_wif[0]["wif_type_id"];
	$wif_type_name = $arr_wif[0]["wif_type_name"];
	$wif_type_abbrev = $arr_wif[0]["wif_type_abbrev"];
	$description = $arr_wif[0]["description"];
	
}

$email_html = "";

$email_html .= "
<html>
<head>

<title>PIF Approval</title>
<style>
#mainContent {
    margin: 0 0 0 0px; 
    padding: 0 20px 20px 20px; 
	z-index: 0; 
	font-family: 'Arial', sans-serif !important;
}

#container { 
    width: 600px;
	height: auto;
    background-color: #FFFFFF;
    margin: 0 auto; 
    text-align: left; 
	font-family: 'Arial', sans-serif !important;
	position: relative;
	top: 5px;
}

.pif_form table td, .pif_form table th { 
	padding: 3px 10px; 
	font-family: 'Arial', sans-serif !important;
}
.pif_form table th {
	background-color:#006699; 
	color:#FFFFFF; 
	font-size: 15px; 
	font-weight: bold; 
	border-left: 1px solid #0070A8; 
	font-family: 'Arial', sans-serif !important;
	text-align: left;
	border: none; 
}

.pif_form table td { 
	color: #00557F; 
	border-left: 1px solid #E1EEF4;
	border-bottom: 1px solid #E1EEF4;
	font-family: 'Arial', sans-serif !important;
	font-size: 13px;
	font-weight: normal; 
}

.pif_review_title { 
	font-weight: bold; 
	font-size: 13px;
	font-family: 'Arial', sans-serif !important;
	margin: 0px 0px 0px 0px;
}
.pif_code { 
	font-weight: bold; 
	font-family: 'Arial', sans-serif !important;
	font-size: 20px;
}
</style>
</head>
<body>
<div id = \"page\">
	<div id = \"main\">
		<!--container div tag--> 
		<div id=\"container\"> 
			
			<div id=\"mainContent\"> <!--mainContent div tag--> 
				
					<br>
					
					Please see the below receipt of your Web Intake Project. <br><br>

					A Marketing Project Manager will reach out to you shortly. Thank you. <br><br>
					<table width = \"100%\">
						<tr>
							<td nowrap>
								<div class = \"pif_code\">WIF Received:" .  $wif_code . " </div><div class = \"pif_review_title\">Requested by " . $requester_name . " - " .  $request_date . "</div>
							</td>
						</tr>
					</table>
					<div class = \"pif_form\">
					<div class = \"pif_admin\">
					</div>
					

					<table width = \"560\">
						<tr>
							<th colspan = \"2\">WIF Information</th>
						</tr>
						<tr>
							<td valign=\"top\">
								<div class = \"pif_review_title\">WIF Name:</div>
								" . $wif_name . " (" . $wif_type_name . ")
							</td>
							<td valign=\"top\">
								<div class = \"pif_review_title\">WIF Code:</div>
								" .  $wif_code . "
							</td>
						</tr>

						<tr>
							<td><div class = \"pif_review_title\">Submit Date:</div>
									" . $request_date . "
							<td><div class = \"pif_review_title\">Desired Delivery Date:</div>
									" .  $desired_delivery_date . "
						</tr>
						<tr>
							<th colspan = \"2\">Requirements</th>
						</tr>
						<tr>
							<td colspan = \"2\">
								<div class = \"pif_review_title\">Project Description:</div>
								" . $description . "
							</td>
						</tr>
					</table>
					
						
				
				
				</div>
			</div> <!--end mainContent div tag--> 

		</div>


	</div>

</div>
</body>
</html>
";


return $email_html;
}

?>