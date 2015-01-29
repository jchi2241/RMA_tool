<?php
	include 'configPDO.php';
	include 'helpers.php';

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


		// foreach ($devices_data as $device => $quantity) {
		//     $device_string_arr[] = $quantity . ' ' . $device;
		// }

		// $_POST["devices"] = implode(",", $device_string_arr);
		// $devices = $_POST["devices"];

		// print_r($devices_data);
	}

	if (!empty($_POST["formType"])) {

		$customer_result = $db->prepare("	INSERT INTO customers (full_name, email, shipping_address, phone_number, created_at, updated_at) 
								VALUES (:full_name, :email, :shipping_address, :phone_number, NULL, NULL)");

		$customer_result->bindParam(':full_name', $full_name);
		$customer_result->bindParam(':email', $email);
		$customer_result->bindParam(':shipping_address', $shipping_address);
		$customer_result->bindParam(':phone_number', $phone_number);
		
		$customer_result->execute();

		$customer_id = $db->lastInsertId();

		if($_POST["formType"] == "Sample") {

			$rma_id = newRMAId('samples');
			$reference_id = newRefId('samples');

			$sample_result = $db->prepare("	INSERT INTO samples (customer_id, reason, rma_id, reference_id, created_at, updated_at) 
											VALUES (:customer_id, :reason, :rma_id, :reference_id, NULL, NULL)");
			$sample_result->bindParam(':customer_id', $customer_id);
			$sample_result->bindParam(':reason', $reason);
			$sample_result->bindParam(':rma_id', $rma_id);
			$sample_result->bindParam(':reference_id', $reference_id);
			$sample_result->execute();

			$sample_id = $db->lastInsertId();

			//insert devices into requested_devices
			foreach ($devices_data as $device => $qty ) {
				$device_id = getDeviceId($device);
				$requested_devices_result = $db->prepare("	INSERT INTO requested_devices (qty, device_id, sample_id, customer_id)
															VALUES (:qty, :device_id, :sample_id, :customer_id)");
				$requested_devices_result->bindParam(':qty', $qty);
				$requested_devices_result->bindParam(':device_id', $device_id);
				$requested_devices_result->bindParam(':sample_id', $sample_id);
				$requested_devices_result->bindParam(':customer_id', $customer_id);
				$requested_devices_result->execute();
			}

			//retrieve devices from requested_devices 
			$retreived_devices_result = $db->prepare("	SELECT r.qty, d.name
														FROM requested_devices r
														JOIN devices d ON r.device_id = d.id
														WHERE sample_id = :sample_id");
			$retreived_devices_result->bindParam(':sample_id', $sample_id);
			$retreived_devices_result->execute();

			//display retrieved devices
			$retreived_devices = $retreived_devices_result->fetchAll(PDO::FETCH_ASSOC);

			$device_arr = array();
			foreach ($retreived_devices as $device) {
				$device_arr[] = $device['qty'] . ' ' . $device['name'];
			}

			$devices = implode(", ", $device_arr);

			print_r($devices);
			
			$sample_devices_result = $db->prepare("	UPDATE samples 
													SET devices = :devices
													WHERE id = :sample_id");
			$sample_devices_result->bindParam(':devices', $devices);
			$sample_devices_result->bindParam(':sample_id', $sample_id);
			$sample_devices_result->execute();

		} elseif ($_POST["formType"] == "Replacement") {

			$rma_id = newRMAId('replacements');
			$reference_id = newRefId('replacements');

			$stmt = $db->prepare("	INSERT INTO replacements (customer_id, reason, rma_id, reference_id, created_at, updated_at) 
									VALUES (:customer_id, :reason, :rma_id, :reference_id, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':reason', $reason);
			$stmt->bindParam(':rma_id', $rma_id);
			$stmt->bindParam(':reference_id', $reference_id);
			$stmt->execute();

			$replacement_id = $db->lastInsertId();

			//insert devices into requested_devices
			foreach ($devices_data as $device => $qty ) {
				$device_id = getDeviceId($device);
				$requested_devices_result = $db->prepare("	INSERT INTO requested_devices (qty, device_id, replacement_id, customer_id)
															VALUES (:qty, :device_id, :replacement_id, :customer_id)");
				$requested_devices_result->bindParam(':qty', $qty);
				$requested_devices_result->bindParam(':device_id', $device_id);
				$requested_devices_result->bindParam(':replacement_id', $replacement_id);
				$requested_devices_result->bindParam(':customer_id', $customer_id);
				$requested_devices_result->execute();
			}

			//retrieve devices from requested_devices 
			$retreived_devices_result = $db->prepare("	SELECT r.qty, d.name
														FROM requested_devices r
														JOIN devices d ON r.device_id = d.id
														WHERE replacement_id = :replacement_id");
			$retreived_devices_result->bindParam(':replacement_id', $replacement_id);
			$retreived_devices_result->execute();

			//display retrieved devices
			$retreived_devices = $retreived_devices_result->fetchAll(PDO::FETCH_ASSOC);

			$device_arr = array();
			foreach ($retreived_devices as $device) {
				$device_arr[] = $device['qty'] . ' ' . $device['name'];
			}

			$devices = implode(", ", $device_arr);

			print_r($devices);
			
			$sample_devices_result = $db->prepare("	UPDATE replacements 
													SET devices = :devices
													WHERE id = :replacement_id");
			$sample_devices_result->bindParam(':devices', $devices);
			$sample_devices_result->bindParam(':replacement_id', $replacement_id);
			$sample_devices_result->execute();

		} elseif ($_POST["formType"] == "Return") {

			$rma_id = newRMAId('returns');
			$reference_id = newRefId('returns');

			$stmt = $db->prepare("	INSERT INTO returns (customer_id, reason, refund_amount, rma_id, reference_id, created_at, updated_at) 
									VALUES (:customer_id, :reason, :refund_amount, :rma_id, :reference_id, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':reason', $reason);
			$stmt->bindParam(':refund_amount', $refund_amount);
			$stmt->bindParam(':rma_id', $rma_id);
			$stmt->bindParam(':reference_id', $reference_id);
			$stmt->execute();

			$return_id = $db->lastInsertId();

			//insert devices into requested_devices
			foreach ($devices_data as $device => $qty ) {
				$device_id = getDeviceId($device);
				$requested_devices_result = $db->prepare("	INSERT INTO requested_devices (qty, device_id, return_id, customer_id)
															VALUES (:qty, :device_id, :return_id, :customer_id)");
				$requested_devices_result->bindParam(':qty', $qty);
				$requested_devices_result->bindParam(':device_id', $device_id);
				$requested_devices_result->bindParam(':return_id', $return_id);
				$requested_devices_result->bindParam(':customer_id', $customer_id);
				$requested_devices_result->execute();
			}

			//retrieve devices from requested_devices 
			$retreived_devices_result = $db->prepare("	SELECT r.qty, d.name
														FROM requested_devices r
														JOIN devices d ON r.device_id = d.id
														WHERE return_id = :return_id");
			$retreived_devices_result->bindParam(':return_id', $return_id);
			$retreived_devices_result->execute();

			//display retrieved devices
			$retreived_devices = $retreived_devices_result->fetchAll(PDO::FETCH_ASSOC);

			$device_arr = array();
			foreach ($retreived_devices as $device) {
				$device_arr[] = $device['qty'] . ' ' . $device['name'];
			}

			$devices = implode(", ", $device_arr);

			print_r($devices);
			
			$sample_devices_result = $db->prepare("	UPDATE returns 
													SET devices = :devices
													WHERE id = :return_id");
			$sample_devices_result->bindParam(':devices', $devices);
			$sample_devices_result->bindParam(':return_id', $return_id);
			$sample_devices_result->execute();

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