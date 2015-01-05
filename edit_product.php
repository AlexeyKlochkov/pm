<?php 
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";
include "functions/functions.php";
//print $company_id;
$error_message = "";

if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "Product name exists, no duplicates allowed.";
	}
		if ($error_num == 2){
		$error_message = "Product Updated.";
	}
}
if (!empty($_GET["p"])){
	$product_id = $_GET["p"];
}

$arr_product_info = get_product_info($product_id);
//print_r($arr_task_info );
$product_name = $arr_product_info[0]["product_name"];
$active = $arr_product_info[0]["active"];

if ($active == 1){
	$active_checked = "checked";
}else{
	$active_checked = "";
}

?>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css' />
<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />

<title>Edit Task</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    
	$( "#product_form" ).validate({
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
				<h1>Edit Product</h1>
				<a href = "manage_products.php">All Products</a><br>
				<div class = "error"><?php echo $error_message ?></div>
				
					<table border = "0">
						<tr>
							<td valign="top">
							Edit Product:<form id = "product_form" action = "update_product.php" method = "POST">
							<table class = "form_table">
								<tr>
									<td>Product Name:</td>
									<td><input class = "required" type = "text" name = "product_name" value = "<?php echo $product_name ?>" size = "40"></td>
								</tr>
								
									<td>
									<input type = "hidden" name = "product_id" value = "<?php echo $product_id ?>">
									<input type = "submit" value = "Update Product"></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>Active:</td>
									<td><input type = "checkbox" <?php echo $active_checked ?> name = "active" value = "1"></td>
								</tr>
							</table></form>
						
						</td>
						<td width = "60">
							&nbsp;
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