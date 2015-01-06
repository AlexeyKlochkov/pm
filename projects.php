<?php 

include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
include "loggedin.php";

$active = 1;
if (!empty($_GET["active"])){
	$active = $_GET["active"];
}

$project_id = "";
$selected_project_id = "";
if (!empty($_GET["project_id"])){
	$project_id = $_GET["project_id"];
	$selected_project_id = $project_id;
}

$campaign_id = "";
if (!empty($_GET["campaign_id"])){
	$campaign_id = $_GET["campaign_id"];
}

$product_id = "";
if (!empty($_GET["product_id"])){
	$product_id = $_GET["product_id"];
}

$audience_id = "";
if (!empty($_GET["audience_id"])){
	$audience_id = $_GET["audience_id"];
}

$project_status_id = "";
if (!empty($_GET["project_status_id"])){
	$project_status_id = $_GET["project_status_id"];
}

$project_manager_id = "";

if (!empty($_GET["project_manager_id"])){
	$project_manager_id = $_GET["project_manager_id"];
}else{
	if (empty($_GET["run"])){
		if ($_SESSION["is_project_manager"] ==1){
			$project_manager_id = $_SESSION["user_id"];
		}
	}
}

$arr_projects = get_projects_query($company_id, $project_id, $campaign_id, $product_id, $audience_id, $project_status_id, $project_manager_id, $active);

//print_r($arr_projects);

$project_table = "<table width = \"100%\" class = \"stats_table\"><tr><th>#</th><th>Project Code</th><th>Project Name</th><th>AOP Line of Business</th><th>Product</th><th>Status</th><th>IPM</th><th>&nbsp;</th></tr>";
if (!empty($arr_projects)){
	foreach ($arr_projects as $project_row){
			$project_id = $project_row["project_id"];
			$project_code = $project_row["project_code"];
			$project_name = $project_row["project_name"];
			$campaign_code = $project_row["campaign_code"];
			$product_name = $project_row["product_name"];
			$audience_name = $project_row["audience_name"];
			$project_status = $project_row["project_status_name"];
			$project_manager = $project_row["last_name"];
			$project_table .= "<tr><td>" . $project_id . "</td>";
			$project_table .= "<td>" . $project_code . "</td>";
			$project_table .= "<td>" . $project_name . "</td>";
			$project_table .= "<td>" . $campaign_code . "</td>";
			$project_table .= "<td>" . $product_name . "</td>";
			//$project_table .= "<td>" . $audience_name . "</td>";
			$project_table .= "<td>" . $project_status . "</td>";
			$project_table .= "<td>" . $project_manager . "</td>";
			$project_table .= "<td><a href =\"manage_project.php?p=" . $project_id . "\">View</a></td>";
			$project_table .= "</tr>";
	}
}else{
	$project_table .= "<tr><td>No results for this query</td></tr>";

}
$project_table .= "</table>";

$campaign_select = get_campaign_code_select($company_id, $campaign_id);
$campaign_select = str_replace("Please select", "All", $campaign_select );
$project_code_select = get_project_code_select($company_id, $selected_project_id);
$project_code_select = str_replace("Please select", "All", $project_code_select );
$product_select = get_product_select($company_id, $product_id);
$product_select = str_replace("Please select", "All", $product_select );
//$business_unit_select = get_business_unit_select($company_id, $business_unit_id);
$audience_select =  get_audience_select($company_id, $audience_id);
$audience_select = str_replace("Please select", "All", $audience_select );
$status_select =  get_project_status_select($company_id, $project_status_id);
$status_select = str_replace("Please select", "All", $status_select );
$project_manager_select =  get_project_manager_select($company_id, $project_manager_id);
$project_manager_select = str_replace("Please select", "All", $project_manager_select );

$active_checked = "";
$archived_checked = "";
$all_checked = "";

if (!empty($_GET["active"])){
	if(($_GET["active"]) == 1){
		$active_checked = " selected";
	}
	if(($_GET["active"]) == 2){
		$archived_checked = " selected";
	}
	if(($_GET["active"]) == 3){
		$all_checked = " selected";
	}
}

$archive_select = "<select name = \"active\">\n";
$archive_select .= "<option value = \"1\"" . $active_checked . ">Active</option>\n";
$archive_select .= "<option value = \"2\"" . $archived_checked . ">Archived</option>\n";
$archive_select .= "<option value = \"3\"" . $all_checked . ">All</option>\n";
$archive_select .= "</select>\n";
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Projects</title>
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
				<h1>Projects</h1>
				<table width = "100%" class = "small_link">
					<tr>
						<td align = "right">
							<a href = "new_project.php?c=<?php echo $campaign_id?>&pr=<?php echo $product_id?>&pm=<?php echo $project_manager_id?>&a=<?php echo $audience_id?>">Add Project</a>
						</td>
					</tr>
				</table>
				<table class = "small_link" width = "90%">
					<tr><form id = "get_projects" action = "projects.php" method = "GET">
						<td>Project:<br><?php echo $project_code_select ?></td>
						<td>AOP Line of Business:<br><?php echo $campaign_select ?></td>
						<td>Product:<br><?php echo $product_select ?></td>
						<td>Active?<br><?php echo $archive_select?></td>
					</tr>
					<tr>
						<td>Status:<br><?php echo $status_select ?></td>
						<td>IPM:<br><?php echo $project_manager_select ?></td>
						<td>&nbsp;<br></td>
						<td><input type = "hidden" name = "run" value = "1"><input type = "submit" value = "go"></td>
						</form>
					</tr>
				</table>
				<?php echo $project_table  ?>
				<br><br>
				<form action = "export_projects.php" method="POST">
					<input type = "hidden" name = "active" value = "<?php echo $active ?>">
					<input type = "hidden" name = "project_id" value = "<?php echo $selected_project_id ?>">
					<input type = "hidden" name = "campaign_id" value = "<?php echo $campaign_id ?>">
					<input type = "hidden" name = "product_id" value = "<?php echo $product_id ?>">
					<input type = "hidden" name = "audience_id" value = "<?php echo $audience_id ?>">
					<input type = "hidden" name = "project_status_id" value = "<?php echo $project_status_id ?>">
					<input type = "hidden" name = "project_manager_id" value = "<?php echo $project_manager_id ?>">
					<input type = "submit" value = "Export CSV File">
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