<?php 
include "loggedin.php";
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Admin Tools</title>
<script type="text/javascript" src="jquery-1.7.2.js"></script> 
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
				<h1>Reports</h1>
				<br>
				<a href = "tasks.php">Tasks & Time Entry</a><br>
				<a href = "sow_report.php">SOW Report</a><br>
				<a href = "production_status_report.php">Production Status Report</a><br>
				<a href = "project_and_asset_report.php">Project and Asset Report</a><br>
				<a href = "asset_item_report.php">Asset Item Report</a><br>
				<a href = "resource_report.php">Resource Report</a><br>
				<a href = "projects_report.php">Projects Report</a><br>
				<a href = "asset_report.php">Assets type Report</a><br>
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>
