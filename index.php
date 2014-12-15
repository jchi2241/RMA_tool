<?php
	include 'connect.php';
	include 'getcustomers.php';

	if (isset($_POST["name"]) && isset($_POST["email"])){

		$name = $_POST["name"];
		$email = $_POST["email"];
	}

	if (isset($_POST["submit"])) {

		if (!empty($_POST["formType"])) {

			//insert customer's info 
			$stmt = $db->prepare("INSERT INTO customers (name, email) VALUES (:name, :email)");

			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':email', $email);
			$stmt->execute();

			$id = $db->lastInsertId();

			//insert customer into either samples or replacments table
			if($_POST["formType"] == "Sample"){
				$stmt = $db->prepare("INSERT INTO samples (customer_ID) VALUES (:id)");
				$stmt->bindParam(':id', $id);
				$stmt->execute();
			} elseif ($_POST["formType"] == "Replacement") {
				$stmt = $db->prepare("INSERT INTO replacements (customer_ID) VALUES (:id)");
				$stmt->bindParam(':id', $id);
				$stmt->execute();
			} else {
				echo "check your formType values";
			}

			//insert customer into early_ships table if checkbox is checked.
			if(isset($_POST["earlyShip"])) {
				$stmt = $db->prepare("INSERT INTO early_ships (customer_ID) VALUES (:id)");
				$stmt->bindParam(':id', $id);
				$stmt->execute();
			}

			//redirect to clear _POST variables
			header("Location:/website/projects/RMA_1/index.php");

		} else {

			echo "Choose a Form.";

		} 
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Backgrid Test</title>
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/backgrid.js/0.3.5/backgrid.min.css">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/css/bootstrap.min.css">
</head>
<body>

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

	<h1>Customers</h1>
	<div id="table"></div>

	<!-- Libraries -->
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  	<script src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js"></script>
  	<script src="http://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.2/backbone-min.js"></script>
  	<script src="http://cdnjs.cloudflare.com/ajax/libs/backgrid.js/0.3.5/backgrid.min.js"></script>

  	<script src="backgrid_table.js"></script>
  
</body>

</html>