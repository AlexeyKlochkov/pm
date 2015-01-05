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
		$error_message = "An error occurred, most likely a duplicate status name.";
	}
		if ($error_num == 2){
		$error_message = "Status Added.";
	}
}


$arr_status = get_statuses($company_id, 1);
$status_table = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"5\">Current Status List</th></tr>";
$n=0;
if (!empty($arr_status)){
	foreach ($arr_status as $status_row){
		$project_status_id = $status_row["project_status_id"];
		$project_status_name = $status_row["project_status_name"];
		$display_order = $status_row["display_order"];
		$status_down_arrow = "";
		$status_up_arrow = "";
		
		if (!empty($arr_status[$n+1]["display_order"])){
			$next_display_order = $arr_status[$n+1]["display_order"];
		}else{
			$next_display_order = "0";
		}
		
		if ($display_order <> 1){
				$swap1 = $display_order;
				$swap2 = $display_order - 1;
				$status_up_arrow = "<a href = \"move_status.php?s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_up.png\" border=\"0\"></a>";
			}
		if ($display_order < $next_display_order){
			$swap1 = $display_order;
			$swap2 = $display_order + 1;
			$status_down_arrow = "<a href = \"move_status.php?s1=" . $swap1 . "&s2=" . $swap2 . "\"><img src = \"images/arrow_down.png\" border=\"0\"></a>";
		}
		
		
		$status_table .= "<tr><td align=\"right\">" . $display_order . "</td><td>" . $project_status_name . "</td><td><a href = \"activate_status.php?a=2&s=" . $project_status_id . "&d=" . $display_order ."\">del</a><td>" . $status_down_arrow . "</td><td>" . $status_up_arrow . "</td></td></tr>";
		$n++;
	}
}
$status_table .= "</table>";


$arr_retired_status = get_statuses($company_id, 0);
$status_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Retired Status List</th></tr>";
if (!empty($arr_retired_status)){
	foreach ($arr_retired_status as $status_row){
		$project_status_id = $status_row["project_status_id"];
		$project_status_name = $status_row["project_status_name"];
		$display_order = $status_row["display_order"];
		$status_table2 .= "<tr><td align=\"right\">" . $display_order . "</td><td>" . $project_status_name . "</td><td><a href = \"activate_status.php?a=1&s=" . $project_status_id . "&d=" . $display_order . "\">activate</a></td></tr>";
	
	}
}
$status_table2 .= "</table>";

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>New Status</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#status_form" ).validate({
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
				<h1>Status</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Status:<form id = "status_form" action = "add_status.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Status Name:</td>
									<td><input class = "required" type = "text" name = "project_status_name"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add Status"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $status_table ?>
							<br><br>
							<?php echo $status_table2 ?>
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