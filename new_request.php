<?php
include 'configPDO.php';

	if (isset($_POST["full_name"]) && isset($_POST["email"]) && isset($_POST["address"]) && isset($_POST["phone_number"])){

		$full_name = $_POST["full_name"];
		$email = $_POST["email"];
		$address = $_POST["address"];
		$phone_number = $_POST["phone_number"];
	}

	if (isset($_POST["submit"])) {

		if (!empty($_POST["formType"])) {

			//insert customer's info 
			$stmt = $db->prepare(  "INSERT INTO customers (full_name, email, address, phone_number, created_at, updated_at) 
									VALUES (:full_name, :email, :address, :phone_number, NULL, NULL)");

			$stmt->bindParam(':full_name', $full_name);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':address', $address);
			$stmt->bindParam(':phone_number', $phone_number);
			$stmt->execute();

			$id = $db->lastInsertId();

			//insert customer into either samples or replacments table
			if($_POST["formType"] == "Sample"){
				$stmt = $db->prepare("INSERT INTO samples (customer_id, created_at) VALUES (:id, NULL)");
				$stmt->bindParam(':id', $id);
				$stmt->execute();
			} elseif ($_POST["formType"] == "Replacement") {
				$stmt = $db->prepare("INSERT INTO replacements (customer_id, created_at) VALUES (:id, NULL)");
				$stmt->bindParam(':id', $id);
				$stmt->execute();
			} else {
				echo "check your formType values";
			}

			//insert customer into early_ships table if checkbox is checked.
			if(isset($_POST["earlyShip"])) {
				$stmt = $db->prepare("INSERT INTO early_ships (customer_id, created_at) VALUES (:id, NULL)");
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