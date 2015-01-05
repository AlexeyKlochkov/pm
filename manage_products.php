<?php 
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
//print $company_id;

if($_SESSION["user_level"] < 30){
		$location = "Location: loggedout.php";
		header($location) ;
}
$error_message = "";
$active_flag = 1;
if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "Product name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Product Added.";
	}
		if ($error_num == 3){
		$error_message = "Product Updated.";
	}
		if ($error_num == 4){
		$error_message = "An error occurred, possibly a duplicate product name.";
	}
}



$arr_products = get_products($company_id, 1);
$product_table = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Product List</th></tr>";

if (!empty($arr_products)){
	foreach ($arr_products as $product_row){
		$product_id = $product_row["product_id"];
		$product_name = $product_row["product_name"];

		$product_table .= "<tr><td>" . $product_name . "</td><td><a href = \"edit_product.php?p=" . $product_id . "\">edit</a></td><td><a href = \"activate_product.php?a=2&p=" . $product_id . "\">del</a></td></tr>";
	}
}
$product_table .= "</table>";


$arr_inactive_products = get_products($company_id, 0);
$product_table2 = "<table width = \"300\" class = \"budget\"><tr><th colspan = \"3\">Inactive Products</th></tr>";
if (!empty($arr_inactive_products)){
	foreach ($arr_inactive_products as $product_row){
		$product_id = $product_row["product_id"];
		$product_name = $product_row["product_name"];
		$product_table2 .= "<tr><td>" . $product_name . "</td><td><a href = \"activate_product.php?a=1&p=" . $product_id . "\">activate</a></td></tr>";
	}
}
$product_table2 .= "</table>";

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Manage Products</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>

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
				<h1>Manage Products</h1>
				
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							New Product:<form id = "user_form" action = "add_product.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Product Name:</td>
									<td><input class = "required" type = "text" name = "product_name"></td>
								</tr>
								<tr>
									<td>
									<input type = "hidden" name = "company_id" value = "<?php echo $company_id ?>">
									<input type = "submit" value = "Add Product" id = "submit"></td>
									<td>&nbsp;</td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
						</td>
						<td valign="top">
							<?php echo $product_table ?>
							<br><br>
							<?php echo $product_table2 ?>
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