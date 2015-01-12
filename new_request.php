<?php
	include 'configPDO.php';
	include 'rma_id_functions.php';

	// $data = file_get_contents('php://input');

	if (isset($_POST["full_name"]) && isset($_POST["email"]) && isset($_POST["shipping_address"]) && isset($_POST["phone_number"]) && isset($_POST["reason"]) && isset($_POST["shipping_carrier"])) {

		$full_name = $_POST["full_name"];
		$email = $_POST["email"];
		$shipping_address = $_POST["shipping_address"];
		$phone_number = $_POST["phone_number"];
		$reason = $_POST["reason"];
		$refund_amount = $_POST["refund_amount"];
		$tracking_number = $_POST["tracking_number"];
		$shipping_carrier = $_POST["shipping_carrier"];
	}

	if (!empty($_POST["formType"])) {

		//insert customer's info 
		$stmt = $db->prepare(  "INSERT INTO customers (full_name, email, shipping_address, phone_number, created_at, updated_at) 
								VALUES (:full_name, :email, :shipping_address, :phone_number, NULL, NULL)");

		$stmt->bindParam(':full_name', $full_name);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':shipping_address', $shipping_address);
		$stmt->bindParam(':phone_number', $phone_number);
		
		$stmt->execute();

		$customer_id = $db->lastInsertId();

		//insert customer into either samples or replacments table
		if($_POST["formType"] == "Sample") {

			$rma_id = newRMAId('samples');
			$reference_id = newRefId('samples');

			$stmt = $db->prepare("INSERT INTO samples (customer_id, reason, rma_id, reference_id, created_at, updated_at) VALUES (:customer_id, :reason, :rma_id, :reference_id, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':reason', $reason);
			$stmt->bindParam(':rma_id', $rma_id);
			$stmt->bindParam(':reference_id', $reference_id);
			$stmt->execute();
		} elseif ($_POST["formType"] == "Replacement") {

			$rma_id = newRMAId('replacements');
			$reference_id = newRefId('replacements');

			$stmt = $db->prepare("INSERT INTO replacements (customer_id, reason, created_at, updated_at) VALUES (:customer_id, :reason, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':reason', $reason);
			$stmt->execute();
		} elseif ($_POST["formType"] == "Return") {

			$rma_id = newRMAId('returns');
			$reference_id = newRefId('returns');

			$stmt = $db->prepare("INSERT INTO returns (customer_id, reason, refund_amount, created_at, updated_at) VALUES (:customer_id, :reason, :refund_amount, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':reason', $reason);
			$stmt->bindParam(':refund_amount', $refund_amount);
			$stmt->execute();
		} else {
			echo "check your formTypes";
		}

		//insert customer into early_ships table if checkbox is checked.
		if($_POST["earlyShip"] == 'checked') {

			$rma_id = newRMAId('returns');
			
			$stmt = $db->prepare("INSERT INTO early_ships (customer_id, tracking_number, shipping_carrier, created_at, updated_at) VALUES (:customer_id, :tracking_number, :shipping_carrier, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':tracking_number', $tracking_number);
			$stmt->bindParam(':shipping_carrier', $shipping_carrier);

			$stmt->execute();
		}

		//redirect to clear _POST variables
		// header("Location:/projects/rma_1/index.php");

	} else {

		echo "Choose a Form.";

	} 
?>