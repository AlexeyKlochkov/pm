<?php 
session_start();
//print $company_id;

$_SESSION["user_id"] = "";
//session_destroy();
//print "session is destroyed";
$error_message = "";
date_default_timezone_set('America/Los_Angeles');
if (!empty($_GET["e"])){
	$error_num = $_GET["e"];
	if ($error_num == 1){
		$error_message = "Invalid apollogroup username or password.";
	}
		if ($error_num == 2){
		$error_message = "AD Connection Error.";
	}
		if ($error_num == 3){
		$error_message = "User ID not found in the Streamline system.";
	}
}else{
	$error_message = "Please log-in.";
}


?>

<html>
<head>

<link href='js/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />
<link href='style.css' rel='stylesheet' type='text/css' />
<title>Log In</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){

  });

  </script>


</head>
<body>
<div id = "page">
	<div id = "main">
			<div id = "logo">
				<img src = "logo.png">
			</div>
		<!--container div tag--> 
		<div id="container"> 
			
			<div id="mainContent"> <!--mainContent div tag--> 
				<table width = "100%" border = "0">
					<tr>
						<td width = "50%" valign="top">
				<h1>Log In:</h1>
				<div class = "error"><?php echo $error_message ?></div>
				<form method='post' action="login_dev.php">

				Username: <input type='text' name='username'><br>
				<br>

				<input type='submit' name='submit' value='Submit'><br>
				</form>
				</td>
			</div> <!--end mainContent div tag--> 

		</div>

		<?php 
		include "footer.php";
		?> 

	</div>

</div>


</body>
</html>