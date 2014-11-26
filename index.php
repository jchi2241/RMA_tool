<?php

	require_once 'connectdb.php';

	if (isset($_POST["name"]) && isset($_POST["email"])){

		$name = $_POST["name"];
		$email = $_POST["email"];
	}

	if (isset($_POST["submit"])) {

		if (!empty($_POST["formType"])) {

			//insert customer's info 
			$query = "INSERT INTO customers (name, email) VALUES ('$name', '$email')"; 

			if(!$db->query($query)){
				die('There was an error running the query [' .$db->error .']');
			}

			//insert customer into either samples or replacments table
			if($_POST["formType"] == "Sample"){
				insertLastValue("customers", "samples", "customer_ID");
			} elseif ($_POST["formType"] == "Replacement") {
				insertLastValue("customers", "replacements", "customer_ID");
			} else {
				echo "check your formType values.";
			}

			//insert customer into early_ships table if checkbox is checked.
			if(isset($_POST["earlyShip"])) {

				$result = $db->query("SELECT id FROM customers ORDER BY id DESC LIMIT 1");
				$lastID = mysqli_fetch_array($result);
				
				$insertQuery = "INSERT INTO early_ships (customer_ID) VALUES ('$lastID[0]')";

				if(!$db->query($insertQuery)){
					die('There was an error running the query [' .$db->error .']');
				}
			}

			//redirect to print page after submit
			header("Location:/website/projects/RMA_1/printform.php");

		} else {

			echo "Choose a Form.";

		} 
	}

	function insertLastValue($fromTable, $intoTable, $column) {

		global $db;

		$result = $db->query("SELECT id FROM $fromTable ORDER BY id DESC LIMIT 1");
		$lastID = mysqli_fetch_array($result);

		if(!$db->query("INSERT INTO $intoTable ($column) VALUES ('$lastID[0]')")){
			die('There was an error running the query [' .$db->error .']');
		}

	}

/*	echo "insertLastValue (samples table): " . insertLastValue("customers", "samples", "customer_ID") . "<br />";
	echo "insertLastValue (replacements table): " . insertLastValue("customers", "replacements", "customer_ID") . "<br /><br />";*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title></title>
 
<!-- <link rel="stylesheet" href="css/main.css" type="text/css" /> -->
 
<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!--[if lte IE 7]>
	<script src="js/IE8.js" type="text/javascript"></script><![endif]-->
<!--[if lt IE 7]>
 
	<link rel="stylesheet" type="text/css" media="all" href="css/ie6.css"/><![endif]-->

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

</head>
 
<body id="index" class="home">

	<form method="post">

		<input type="checkbox" name="earlyShip" value="checked"> Early Ship<br />
		<input type="radio" name="formType" value="Sample"> Sample<br />
		<input type="radio" name="formType" value="Replacement"> Replacement<br /><br />

		<label for="name">Name</label>
		<input name="name" type="text" />

		<br /><br />

		<label for="email">Email</label>
		<input name="email" type="email" />

		<br /><br />

		<input id="submitForm" type="submit" name="submit" value="Submit"/>

	</form>


</body>
</html>