<?php
	include 'configPDO.php';
	include 'helpers.php';

	session_start();

	if ( isset($_SESSION["email"]) ) {

		$sql = "SELECT id
				FROM users
				WHERE email = :email";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':email', $_SESSION['email']);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$user_id = $result['id'];

	} else {

		echo "no session exists";
		die();

	}

	// if ( isset($_POST["full_name"]) && isset($_POST["email"]) && isset($_POST["address"]) && 
	// 	isset($_POST["city"]) && isset($_POST["state"]) && isset($_POST["zip_postal"]) &&
	// 	isset($_POST["phone_number"]) && isset($_POST["reason"]) && isset($_POST["devices"]) && 
	// 	isset($_POST["country"]) && isset($_POST["ticket_id"])) {

		// $purpose = $_POST["purpose"];
		// $business_name = $_POST["business_name"];
		$full_name = $_POST["full_name"];
		$email = $_POST["email"];
		$address = $_POST["address"];
		$city = $_POST["city"];
		$state = $_POST["state"];
		$zip_postal = $_POST["zip_postal"];
		$country = $_POST["country"];
		$phone_number = $_POST["phone_number"];
		$reason = $_POST["reason"];
		$ticket_id = $_POST["ticket_id"];
		// $special_req = $_POST["special_req"];
		// $refund_amount = $_POST["refund_amount"];
		// $tracking_number = $_POST["tracking_number"];
		// $shipping_carrier = $_POST["shipping_carrier"];

		$devices_data = json_decode($_POST["devices"], true);

	// }

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

		// echo "country: ";
		// print_r($country);

		//insert into customers table
		$customer_result = $db->prepare("	INSERT INTO customers (full_name, email, address, city, state, zip_postal, country, phone_number, created_at, updated_at) 
											VALUES (:full_name, :email, :address, :city, :state, :zip_postal, :country, :phone_number, NULL, NULL)");

		$customer_result->bindParam(':full_name', $full_name);
		$customer_result->bindParam(':email', $email);
		$customer_result->bindParam(':address', $address);
		$customer_result->bindParam(':city', $city);
		$customer_result->bindParam(':state', $state);
		$customer_result->bindParam(':zip_postal', $zip_postal);
		$customer_result->bindParam(':country', $country);
		$customer_result->bindParam(':phone_number', $phone_number);
		$customer_result->execute();

		$customer_id = $db->lastInsertId();

		if ($table == "samples" || $table == "replacements") {

			$request_result = $db->prepare("	INSERT INTO {$table} (ticket_id, user_id, customer_id, reason, rma_id, reference_id, created_at, updated_at) 
												VALUES (:ticket_id, :user_id, :customer_id, :reason, :rma_id, :reference_id, NULL, NULL)");
			$request_result->bindParam(':ticket_id', $ticket_id);
			$request_result->bindParam(':user_id', $user_id);
			$request_result->bindParam(':customer_id', $customer_id);
			$request_result->bindParam(':reason', $reason);
			$request_result->bindParam(':rma_id', $rma_id);
			$request_result->bindParam(':reference_id', $reference_id);
			$request_result->execute();

			$request_id = $db->lastInsertId();

			// if ($table == "samples") {

			// 	//get associated id for purpose
			// 	$sql = "SELECT id, purpose 
			// 			FROM sample_request_purposes";

			// 	$stmt = $db->prepare($sql);
			// 	$stmt->execute();

			// 	$array = $stmt->fetchAll();

			// 	foreach ($array as $value) {

			// 		if ( $value['purpose'] == $purpose ) {
			// 			$purpose_id = $value['id'];
			// 			break;	
			// 		}
						
			// 	}

			// 	$sql = "UPDATE samples
			// 			SET purpose_id = :purpose_id
			// 			WHERE id = :request_id";

			// 	$stmt = $db->prepare($sql);
			// 	$stmt->bindParam(':purpose_id', $purpose_id);
			// 	$stmt->bindParam(':request_id', $request_id);
			// 	$stmt->execute();

			// }

		} elseif ($table == "returns") {

			$stmt = $db->prepare("	INSERT INTO returns (customer_id, reason, special_req, refund_amount, rma_id, reference_id, created_at, updated_at) 
									VALUES (:customer_id, :reason, :special_req, :refund_amount, :rma_id, :reference_id, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':reason', $reason);
			$stmt->bindParam(':special_req', $special_req);
			$stmt->bindParam(':refund_amount', $refund_amount);
			$stmt->bindParam(':rma_id', $rma_id);
			$stmt->bindParam(':reference_id', $reference_id);
			$stmt->execute();

			$request_id = $db->lastInsertId();

		} else {

			echo "no table exists";

		}

		//insert into requested_devices table
		foreach ($devices_data as $device => $qty ) {
				
			$device_id = getDeviceId($device);
			$count = 0;

			while ($count < $qty) {

				$devices_result = $db->prepare("INSERT INTO requested_devices (device_id, {$request_id_col}, customer_id)
												VALUES (:device_id, :request_id, :customer_id)");

				$devices_result->bindParam(':device_id', $device_id);
				$devices_result->bindParam(':request_id', $request_id);
				$devices_result->bindParam(':customer_id', $customer_id);
				$devices_result->execute();

				$count++;

			} 
		}

		//retrieve newly updated set of devices from requested_devices
		$sql = "SELECT COUNT(*), d.name
				FROM requested_devices r
				JOIN devices d ON r.device_id = d.id
				WHERE {$request_id_col} = :request_id AND r.deleted = 0
				GROUP BY d.name
				ORDER BY d.id";

		$retreived_devices_result = $db->prepare($sql);
		$retreived_devices_result->bindParam(':request_id', $request_id);
		$retreived_devices_result->execute();

		$array = [];

		while ($row = $retreived_devices_result->fetch(PDO::FETCH_ASSOC)) {
			array_push($array, $row['COUNT(*)'] . ' ' . $row['name']);
		}

		$devices = implode(", ", $array);

		echo "array: \n";
		print_r($array);
		echo "\n";

		$sql = "UPDATE {$table}
				SET devices = :devices
				WHERE id = :request_id AND deleted = 0";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':devices', $devices);
		$stmt->bindParam(':request_id', $request_id);
		$stmt->execute();

		//temporary hack to display devices in table
		// $retreived_devices_result = $db->prepare("	SELECT d.name
		// 											FROM requested_devices r
		// 											JOIN devices d ON r.device_id = d.id
		// 											WHERE {$request_id_col} = :request_id");
		// $retreived_devices_result->bindParam(':request_id', $request_id);
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
		// 										WHERE id = :request_id");
		// $sample_devices_result->bindParam(':devices', $devices);
		// $sample_devices_result->bindParam(':request_id', $request_id);
		// $sample_devices_result->execute();

		if(isset($_POST["earlyShip"]) && $_POST["earlyShip"] == 'checked') {
			
			$stmt = $db->prepare("	INSERT INTO early_ships (customer_id, sample_id, shipping_carrier, tracking_number, created_at, updated_at) 
									VALUES (:customer_id, :request_id, :shipping_carrier, :tracking_number, NULL, NULL)");
			$stmt->bindParam(':customer_id', $customer_id);
			$stmt->bindParam(':tracking_number', $tracking_number);
			$stmt->bindParam(':shipping_carrier', $shipping_carrier);
			$stmt->bindParam(':request_id', $request_id);

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