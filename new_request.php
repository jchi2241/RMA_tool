<?php
	include 'configPDO.php';
	include 'rma_id_functions.php';

	if (isset($_POST["full_name"]) && isset($_POST["email"]) && isset($_POST["shipping_address"]) && 
		isset($_POST["phone_number"]) && isset($_POST["reason"]) && isset($_POST["shipping_carrier"]) &&
		isset($_POST["tracking_number"]) && isset($_POST["reason"]) && isset($_POST["devices"])) {

		$full_name = $_POST["full_name"];
		$email = $_POST["email"];
		$shipping_address = $_POST["shipping_address"];
		$phone_number = $_POST["phone_number"];
		$reason = $_POST["reason"];
		$refund_amount = $_POST["refund_amount"];
		$tracking_number = $_POST["tracking_number"];
		$shipping_carrier = $_POST["shipping_carrier"];

		$devices_data = json_decode($_POST["devices"], true);
		$device_string_arr = array();

		foreach ($devices_data as $device => $quantity) {
		    $device_string_arr[] = $quantity . ' ' . $device;
		}

		$_POST["devices"] = implode(",", $device_string_arr);
		$devices = $_POST["devices"];

		print_r($devices);
	}

	if (!empty($_POST["formType"])) {

		$stmt = $db->prepare("	INSERT INTO customers (full_name, email, shipping_address, phone_number, created_at, updated_at) 
								VALUES (:full_name, :email, :shipping_address, :phone_number, NULL, NULL)");

		$stmt->bindParam(':full_name', $full_name);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':shipping_address', $shipping_address);
		$stmt->bindParam(':phone_number', $phone_number);
		
		$stmt->execute();

		$customer_id = $db->lastInsertId();

		if($_POST["formType"] == "Sample") {

			$rma_id = newRMAId('samples');
			$reference_id = newRefId('samples');

			$stmt = $db->prepare("	INSERT INTO samples (customer_id, reason, rma_id, reference_id, devices, created_at, updated_at) 
									VALUES (:customer_id, :reason, :rma_id, :reference_id, :devices, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':reason', $reason);
			$stmt->bindParam(':rma_id', $rma_id);
			$stmt->bindParam(':reference_id', $reference_id);
			$stmt->bindParam(':devices', $devices);
			$stmt->execute();

			$sample_id = $db->lastInsertId();

		} elseif ($_POST["formType"] == "Replacement") {

			$rma_id = newRMAId('replacements');
			$reference_id = newRefId('replacements');

			$stmt = $db->prepare("	INSERT INTO replacements (customer_id, reason, rma_id, reference_id, devices, created_at, updated_at) 
									VALUES (:customer_id, :reason, :rma_id, :reference_id, :devices, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':reason', $reason);
			$stmt->bindParam(':rma_id', $rma_id);
			$stmt->bindParam(':reference_id', $reference_id);
			$stmt->bindParam(':devices', $devices);
			$stmt->execute();

		} elseif ($_POST["formType"] == "Return") {

			$rma_id = newRMAId('returns');
			$reference_id = newRefId('returns');

			$stmt = $db->prepare("	INSERT INTO returns (customer_id, reason, refund_amount, rma_id, reference_id, devices, created_at, updated_at) 
									VALUES (:customer_id, :reason, :refund_amount, :rma_id, :reference_id, :devices, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':reason', $reason);
			$stmt->bindParam(':refund_amount', $refund_amount);
			$stmt->bindParam(':rma_id', $rma_id);
			$stmt->bindParam(':reference_id', $reference_id);
			$stmt->bindParam(':devices', $devices);
			$stmt->execute();

		} else {
			echo "check your formTypes";
		}

		if(isset($_POST["earlyShip"]) && $_POST["earlyShip"] == 'checked') {
			
			$stmt = $db->prepare("	INSERT INTO early_ships (customer_id, sample_id, shipping_carrier, tracking_number, created_at, updated_at) 
									VALUES (:customer_id, :sample_id, :shipping_carrier, :tracking_number, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':tracking_number', $tracking_number);
			$stmt->bindParam(':shipping_carrier', $shipping_carrier);
			$stmt->bindParam(':sample_id', $sample_id);

			$stmt->execute();
		}

	} else {

		echo "Choose a Form.";

	}

	//redirect to clear $_POST variables
	//header("Location:./index.php");
?>