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
				<h1>Admin Tools</h1>
				<br>
				<b>Manage:</b><br>
				<a href = "new_asset_attribute.php">Asset Attributes</a><br>
				<a href = "new_asset_type.php">Asset Types</a><br>
				<a href = "new_asset_type_category.php">Asset Type Categories</a><br>
				<a href = "new_asset_type_template.php">Asset Type Template</a><br>
				<a href = "edit_asset_type_template.php">Asset Type Template - Edit</a><br>
				<a href = "new_audience.php">Audiences</a><br>
				<a href = "new_business_unit.php">Business Units</a><br>
<?php 
if ($_SESSION["user_level"] > 30){
?>
				<a href = "change_project_code.php">Change Project Code</a><br>
<?php 
}
?>
				<a href = "image.php">Images</a><br>
				<a href = "model.php">Models</a><br>
				<a href = "new_phase.php">Phases</a><br>
				<a href = "manage_products.php">Products</a><br>
				<a href = "new_status.php">Project Status</a><br>
				<a href = "new_role.php">Roles</a><br>
				<a href = "new_schedule_template.php">Schedule Templates</a><br>
				<a href = "new_task.php">Tasks</a><br>
				<a href = "manage_users.php">Users</a><br>
				<a href = "new_user_group.php">User Groups</a><br>
				<a href = "new_vendor.php">Vendors</a><br>
				<br>
				<b>Project Intake</b><br>
				<a href = "new_aop.php">New Project Brief (AOP)</a><br>
               		 	<a href = "new_up.php">New Project Brief (unplanned activity)</a><br>
				<a href = "pif_list.php">Review Open Project Briefs</a><br>
				<a href = "pif_assign_aop.php">Assign AOP to Project Briefs</a><br>
				<a href = "pif_count_report.php">Project Brief Count Report</a><br>
				<br>
				<b>Web Intake</b><br>
				<a href = "new_wif.php">New WIF</a><br>
				<a href = "wif_list.php">WIF List</a><br>
				<a href = "new_wif_type.php">WIF Types</a><br>
				
				
				
				
			</div> <!--end mainContent div tag--> 

		</div>
		<?php 
		include "footer.php";
		?> 

	</div>

</div>
</body>
</html>
