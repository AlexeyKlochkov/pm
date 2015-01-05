<?php 
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";
//print $company_id;
$error_message = "";

if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "Please select a business unit.";
	}
		if ($error_num == 2){
		$error_message = "Duplicate campaign for the chosen business unit and year.";
	}
}

$export = 0;
if (!empty($_GET["export"])){
	$export = 1;
}

$arr_prod_status_report = get_production_status_report($company_id);
//print_r($arr_campaigns );
$prod_status_table = "<table class = \"stats_table\" width = \"100%\"><tr><th>Project Code</th><th>Project Name</th><th>Product</th><th>Status</th><th>Project End Date</th><th>IPM</th></tr>";

if (!empty($arr_prod_status_report)){
	foreach ($arr_prod_status_report as $prod_status_row){
			$project_id = $prod_status_row["project_id"];
			$project_name = $prod_status_row["project_name"];
			$project_code = $prod_status_row["project_code"];
			$product_name = $prod_status_row["product_name"];
			$pm_last_name = $prod_status_row["pm_last_name"];
			$end_date = $prod_status_row["end_date"];
			$project_status_name = $prod_status_row["project_status_name"];
		
			$prod_status_table .= "<tr>";
			$prod_status_table .= "<td>" . $project_code . "</td>";
			$prod_status_table .= "<td><a href = \"manage_project.php?p=" . $project_id . "\" target=\"_blank\">" . $project_name . "</a></td>";

			$prod_status_table .= "<td>" . $product_name . "</td>";
			$prod_status_table .= "<td>" . $project_status_name . "</td>";
			$prod_status_table .= "<td align = \"right\">" .  $end_date. "</td>";
			$prod_status_table .= "<td>" . $pm_last_name . "</td>";
			$prod_status_table .= "</tr>";
	}
}else{
	$prod_status_table .= "<tr><td colspan = \"7\">No results for this query</td></tr>";
}
$prod_status_table .= "</table>";



?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Production Status Report</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
  <script>
  $(document).ready(function(){
    $("#campaign_form").validate();
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
				<h1>Production Status Report <?php echo date("m-d-Y") ?></h1>
				<form action = "export_production_status_report_csv.php" method="POST">
				<input type = "submit" value = "export">
				</form>

				<?php echo $prod_status_table ?>
				<br>
				<form action = "export_production_status_report_csv.php" method="POST">
				<input type = "submit" value = "export">
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