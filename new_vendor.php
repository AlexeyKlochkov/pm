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
		$error_message = "Vendor name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Vendor Added.";
	}
}

$arr_vendor = get_vendors($company_id, 1);
$vendor_table = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Current vendors</th></tr>";
if (!empty($arr_vendor)){
	foreach ($arr_vendor as $vendor_row){
		$vendor_id = $vendor_row["vendor_id"];
		$vendor_name = $vendor_row["vendor_name"];
		$vendor_table .= "<tr><td>" . $vendor_name . "</td><td><a href = \"activate_vendor.php?a=2&v=" . $vendor_id . "\">del</a></td></tr>";
	
	}
}
$vendor_table .= "</table>";

$arr_retired_vendors = get_vendors($company_id, 2);
$vendor_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"2\">Retired vendors</th></tr>";
if (!empty($arr_retired_vendors)){
	foreach ($arr_retired_vendors as $vendor_row){
		$vendor_id = $vendor_row["vendor_id"];
		$vendor_name = $vendor_row["vendor_name"];
		$vendor_table2 .= "<tr><td>" . $vendor_name . "</td><td><a href = \"activate_vendor.php?a=1&v=" . $vendor_id . "\">activate</a></td></tr>";
	
	}
}
$vendor_table2 .= "</table>";

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Vendor</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#vendor_form" ).validate({
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
				<h1>Vendors</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Vendor:<form id = "vendor_form" action = "add_vendor.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Vendor Name:</td>
									<td><input class = "required" type = "text" name = "vendor_name"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add Vendor"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $vendor_table ?>
							<br><br>
							<?php echo $vendor_table2 ?>
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