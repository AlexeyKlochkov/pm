<?php 
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
//print $company_id;
$error_message = "";
$active_flag = 1;
$wif_status_id = 0;
$project_code = "";

if (!empty($_GET["wif_status_id"])){
	$wif_status_id_main = $_GET["wif_status_id"];
}else{
	$wif_status_id_main = 1;
}

if (!empty($_GET["pc"])){
	$project_code = $_GET["pc"];
	$error_message = "Project " . $project_code . " created.";
}


$edit_mode=0;
$edit_type = "";
$edit_name = "";
$current_wid = 0;
if (!empty($_GET["wid"])){
	$edit_mode = 1;
	$current_wid=$_GET["wid"];
}

$emergency_email_user_id = get_admin_value("user_id_for_wif_emergency_emails");
$emergency_email_user_select = get_user_select($company_id, "user_id", "please_select", $emergency_email_user_id, 1);
$product_select = get_product_select($company_id, 0);
$campaign_select = get_campaign_code_select($company_id, 0);
//$campaign_select = str_replace("aop_select","required",$campaign_select );
$wif_status_select_main = get_wif_status_select($company_id, $wif_status_id_main );
$wif_status_select_main = str_replace("wif_status_select", "wif_status_select_main", $wif_status_select_main);
//$product_select = str_replace("required", "required new_project", $product_select);
$pm_select = get_project_manager_select($company_id, 0);
$arr_wifs = get_wifs($company_id, $wif_status_id_main);
$arr_wifs1 = get_bm($company_id,$wif_status_id_main,"p.pif_code","desc");
$wif_table = "<table class = \"budget\"><tr><th colspan = \"10\">Current WIFs</th></tr>\n";
$wif_table .= "<tr><th>WIF Code</th><th>WIF Name</th><th>WIF Type</th><th>Requested By</th><th>Requested Date</th><th>Description</th><th>Desired Delivery Date</th><th>Project</th><th colspan = \"2\">&nbsp;</th></tr>\n";
if (!empty($arr_wifs)){
	foreach ($arr_wifs as $wif_row){
		$wif_id = $wif_row["wif_id"];
		$wif_name = $wif_row["wif_name"];
		$wif_code = $wif_row["wif_code"];
		$wif_type_name = $wif_row["wif_type_name"];
		$wif_status_id = $wif_row["wif_status_id"];
		$requester_name = $wif_row["requester_name"];
		$requester_email = $wif_row["requester_email"];
		$request_date = $wif_row["request_date"];
		$desired_delivery_date = $wif_row["desired_delivery_date"];
		$description = $wif_row["description"];
		$project_id = $wif_row["project_id"];
		$project_code = $wif_row["project_code"];
		
		if ($current_wid == $wif_id){
			$wif_table .= "<form method = \"POST\" action = \"update_wif_status.php\" id=\"update_wif_status\"><tr><td valign=\"top\"><a name = \"wtid" . $wif_id . "\">" . $wif_code . "</td><td valign=\"top\">" . $wif_name . "</td><td colspan = \"2\"><div class = \"aop_select\" style = \"display:none;\"><b>AOP:</b><br>" . $campaign_select . "</div></td><td colspan = \"2\"><div class = \"new_project\" style = \"display:none;\"><b>Product:</b><br>" . $product_select . "</div></td><td><div class = \"new_project\" style = \"display:none;\"><b>PM:</b><br>" . $pm_select . "</div></td><td align=\"left\"><b>Change Status:</b><br>" .   get_wif_status_select($company_id,$wif_status_id). "</td><td colspan = \"2\"><input type = \"hidden\" name = \"wif_id\" value = \"" . $wif_id . "\"><input type = \"hidden\" name = \"wif_code\" value = \"" . $wif_code . "\"><input type = \"hidden\" name = \"requester_email\" value = \"" . $requester_email . "\"><input type = \"hidden\" name = \"wif_name\" value = \"" . $wif_name . "\"><input type = \"hidden\" name = \"requester_name\" value = \"" . $requester_name . "\"><input type = \"submit\" id= \"wif_sub\" value = \"update\"></td></tr></form>\n";
		}else{
			$wif_table .= "<tr><td>" . $wif_code . "</td><td>" . $wif_name . "</td><td>" . $wif_type_name . "</td><td>" . $requester_name . "</td><td>" . $request_date . "</td><td>" . $description . "</td align=\"right\"><td>" . $desired_delivery_date . "</td><td><a href = \"manage_project.php?p=" . $project_id . "\">" . $project_code . "</a></td>";
			$wif_table .= "<td><a href = \"wif_list.php?wif_status_id=" . $wif_status_id_main . "&wid=" . $wif_id . "#wid" . $wif_id . "\">update</a></td>";
			$wif_table .= "<td>&nbsp;</td></tr>";
		}
	}
}
if (!empty($arr_wifs1)) {
	foreach ($arr_wifs1 as $wif_row) {
		$wif_id = $wif_row["pif_id"];
		$wif_name = $wif_row["pif_project_name"];
		$wif_code = $wif_row["pif_code"];
		$wif_type_name = "Small changes(Brand manager)";
		$requester_name = $wif_row["requester_first_name"] . " " . $wif_row["requester_last_name"];
		$request_date = $wif_row["request_date"];
		$desired_delivery_date = $wif_row["desired_delivery_date"];
		$description = $wif_row["project_description"];
		$project_id = $wif_row["project_id"];

		if ($current_wid == $wif_id) {
			$wif_table .= "<form method = \"POST\" action = \"update_wif_status.php\" id=\"update_wif_status\"><tr><td valign=\"top\"><a name = \"wtid" . $wif_id . "\">" . $wif_code . "</td><td valign=\"top\">" . $wif_name . "</td><td colspan = \"2\"><div class = \"aop_select\" style = \"display:none;\"><b>AOP:</b><br>" . $campaign_select . "</div></td><td colspan = \"2\"><div class = \"new_project\" style = \"display:none;\"><b>Product:</b><br>" . $product_select . "</div></td><td><div class = \"new_project\" style = \"display:none;\"><b>PM:</b><br>" . $pm_select . "</div></td><td align=\"left\"><b>Change Status:</b><br>" . get_wif_status_select($company_id, $wif_status_id) . "</td><td colspan = \"2\"><input type = \"hidden\" name = \"wif_id\" value = \"" . $wif_id . "\"><input type = \"hidden\" name = \"wif_code\" value = \"" . $wif_code . "\"><input type = \"hidden\" name = \"requester_email\" value = \"" . $requester_email . "\"><input type = \"hidden\" name = \"wif_name\" value = \"" . $wif_name . "\"><input type = \"hidden\" name = \"requester_name\" value = \"" . $requester_name . "\"><input type = \"submit\" id= \"wif_sub\" value = \"update\"></td></tr></form>\n";
		} else {
			$wif_table .= "<tr><td>" . $wif_code . "</td><td>" . $wif_name . "</td><td>" . $wif_type_name . "</td><td>" . $requester_name . "</td><td>" . $request_date . "</td><td>" . $description . "</td align=\"right\"><td>" . $desired_delivery_date . "</td><td><a href = \"manage_project.php?p=" . $project_id . "\">" . $project_code . "</a></td>";
			$wif_table .= "<td><a href = \"view_pif.php?p=". $wif_id . "&is_bm=1\">update</a></td>";
			$wif_table .= "<td>&nbsp;</td></tr>";
		}
	}
}
$wif_table .= "</table>";
?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>WIF List</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
  $(document).ready(function(){

	$( "#wif_status_select" ).change(function() {
	  if ($( "#wif_status_select" ).val()==2){
		 //alert( "Handler for .change() called." );
		 $( ".new_project" ).show();
		 $( ".aop_select" ).show();
		 
		 $("#wif_sub").prop('value', 'Create Project');
	  }else{
		$( ".new_project" ).hide();
		 $( ".aop_select" ).show();
		$("#wif_sub").prop('value', 'Update');
	  }
	  
	});
	
	$( "#wif_status_select_main" ).change(function() {
		 $( "#wif_list_refresh" ).submit();
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
				<h1>WIF List</h1>
				<div class = "error"><?php echo $error_message ?></div>
				<table border = "0" width = "90%">
					<tr>
						<td>
							<form id="wif_list_refresh" action = "wif_list.php" method = "GET">
							Status: <?php echo $wif_status_select_main ?>
							</form>
						</td>
						<td align="right" valign="top">
							<form id="change_emergency_contact" action = "update_wif_emergency_contact.php" method = "POST">
							Emergency Contact: <?php echo $emergency_email_user_select ?> <input type = "submit" value = "change">
							</form>
						</td>
					</tr>
				</table>
					<table border = "0" width = "95%">
						<tr>
							<td valign="top">
								<?php echo $wif_table ?>
								<br><br>
							</td>
						</tr>
					</table>
			</div> <!--end mainContent div tag-->
		</div>
		<?php 
		include "footer.php";
		?>
	</div>
</div>
<script>
$( "#update_wif_status" ).validate({
		rules: {
			task_rate: {
			  required: false,
			  number: true
			}
		
		}
	});	
</script>
</body>
</html>