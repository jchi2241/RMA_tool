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

	}

	if (!empty($_POST["formType"])) {

		if($_POST["formType"] == "Sample") {

			$table = "samples";
			$request_id_col = "sample_id";
			$rma_id = newRMAId('samples');
			$reference_id = newRefId('samples');

		} elseif ($_POST["formType"] == "Replacement") {

			$table = "replacements";
			$request_id_col = "replacement_id";
			$rma_id = newRMAId('replacements');
			$reference_id = newRefId('replacements');

		} elseif ($_POST["formType"] == "Return") {

			$table = "returns";
			$request_id_col = "return_id";
			$rma_id = newRMAId('returns');
			$reference_id = newRefId('returns');

		} else {

			echo "check your formTypes";

		}


		//insert into customers table
		$customer_result = $db->prepare("	INSERT INTO customers (full_name, email, shipping_address, phone_number, created_at, updated_at) 
											VALUES (:full_name, :email, :shipping_address, :phone_number, NULL, NULL)");

		$customer_result->bindParam(':full_name', $full_name);
		$customer_result->bindParam(':email', $email);
		$customer_result->bindParam(':shipping_address', $shipping_address);
		$customer_result->bindParam(':phone_number', $phone_number);
		$customer_result->execute();

		$customer_id = $db->lastInsertId();

		if ($table == "samples" || $table == "replacements") {

			$request_result = $db->prepare("	INSERT INTO {$table} (customer_id, reason, rma_id, reference_id, created_at, updated_at) 
												VALUES (:customer_id, :reason, :rma_id, :reference_id, NULL, NULL)");
			$request_result->bindParam(':customer_id', $customer_id);
			$request_result->bindParam(':reason', $reason);
			$request_result->bindParam(':rma_id', $rma_id);
			$request_result->bindParam(':reference_id', $reference_id);
			$request_result->execute();

			$table_id = $db->lastInsertId();

		} elseif ($table == "returns") {

			$stmt = $db->prepare("	INSERT INTO returns (customer_id, reason, refund_amount, rma_id, reference_id, created_at, updated_at) 
									VALUES (:customer_id, :reason, :refund_amount, :rma_id, :reference_id, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':reason', $reason);
			$stmt->bindParam(':refund_amount', $refund_amount);
			$stmt->bindParam(':rma_id', $rma_id);
			$stmt->bindParam(':reference_id', $reference_id);
			$stmt->execute();

			$table_id = $db->lastInsertId();

		} else {

			echo "no table exists";

		}

		//insert into requested_devices table
		foreach ($devices_data as $device => $qty ) {
				
			$device_id = getDeviceId($device);
			$count = 0;

			while ($count < $qty) {

				$devices_result = $db->prepare("INSERT INTO requested_devices (device_id, {$request_id_col}, customer_id)
												VALUES (:device_id, :table_id, :customer_id)");

				$devices_result->bindParam(':device_id', $device_id);
				$devices_result->bindParam(':table_id', $table_id);
				$devices_result->bindParam(':customer_id', $customer_id);
				$devices_result->execute();

				$count++;

			} 
		}

		//temporary hack to display devices in table
		// $retreived_devices_result = $db->prepare("	SELECT d.name
		// 											FROM requested_devices r
		// 											JOIN devices d ON r.device_id = d.id
		// 											WHERE {$request_id_col} = :table_id");
		// $retreived_devices_result->bindParam(':table_id', $table_id);
		// $retreived_devices_result->execute();

		// $retreived_devices = $retreived_devices_result->fetchAll(PDO::FETCH_ASSOC);

		// print_r($retreived_devices);

		// $device_arr = array();
		// foreach ($retreived_devices as $device) {
		// 	$device_arr[] = $device['qty'] . ' ' . $device['name'];
		// }

		// $devices = implode(", ", $device_arr);
		// print_r($devices);
		
		// $sample_devices_result = $db->prepare("	UPDATE {$table} 
		// 										SET devices = :devices
		// 										WHERE id = :table_id");
		// $sample_devices_result->bindParam(':devices', $devices);
		// $sample_devices_result->bindParam(':table_id', $table_id);
		// $sample_devices_result->execute();

		if(isset($_POST["earlyShip"]) && $_POST["earlyShip"] == 'checked') {
			
			$stmt = $db->prepare("	INSERT INTO early_ships (customer_id, sample_id, shipping_carrier, tracking_number, created_at, updated_at) 
									VALUES (:customer_id, :table_id, :shipping_carrier, :tracking_number, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':tracking_number', $tracking_number);
			$stmt->bindParam(':shipping_carrier', $shipping_carrier);
			$stmt->bindParam(':table_id', $table_id);

			$stmt->execute();
		}
	}

	//redirect to clear $_POST variables
	//header("Location:./index.php");

			//insert devices into requested_devices
			// foreach ($devices_data as $device => $qty ) {
			// 	$device_id = getDeviceId($device);
			// 	$requested_devices_result = $db->prepare("	INSERT INTO requested_devices (qty, device_id, sample_id, customer_id)
			// 												VALUES (:qty, :device_id, :sample_id, :customer_id)");
			// 	$requested_devices_result->bindParam(':qty', $qty);
			// 	$requested_devices_result->bindParam(':device_id', $device_id);
			// 	$requested_devices_result->bindParam(':sample_id', $sample_id);
			// 	$requested_devices_result->bindParam(':customer_id', $customer_id);
			// 	$requested_devices_result->execute();
			// }

			// foreach ($devices_data as $device => $qty) {
			// 	$device_id = getDeviceId($device);

			// 	$count = 0;
			// 	while ($count < $qty) {
					
			// 		$sql = "INSERT INTO requested_devices (device_id, )
			// 				VALUES ();"

			// 		$requested_devices_result = $db->prepare();

			// 		$count++;
			// 	}
			// }

	//retrieve devices from requested_devices 
	// $retreived_devices_result = $db->prepare("	SELECT r.qty, d.name
	// 											FROM requested_devices r
	// 											JOIN devices d ON r.device_id = d.id
	// 											WHERE sample_id = :sample_id");
	// $retreived_devices_result->bindParam(':sample_id', $sample_id);
	// $retreived_devices_result->execute();

	// //display retrieved devices
	// $retreived_devices = $retreived_devices_result->fetchAll(PDO::FETCH_ASSOC);

	// $device_arr = array();
	// foreach ($retreived_devices as $device) {
	// 	$device_arr[] = $device['qty'] . ' ' . $device['name'];
	// }

	// $devices = implode(", ", $device_arr);

	// print_r($devices);
	
	// $sample_devices_result = $db->prepare("	UPDATE samples 
	// 										SET devices = :devices
	// 										WHERE id = :sample_id");
	// $sample_devices_result->bindParam(':devices', $devices);
	// $sample_devices_result->bindParam(':sample_id', $sample_id);
	// $sample_devices_result->execute();

	
?>