<?php 
include "loggedin.php";
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Resource Reports</title>
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
				<h1>Resource Reports</h1>
				<br>
				<a href = "resource_report.php">Resource Report</a><br>

			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>