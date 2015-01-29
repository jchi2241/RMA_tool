<?php

	include 'configPDO.php';
	include 'helpers.php';

	$table = $_POST['table'];
	$request_id = $_POST['request_id'];
	$device_array = json_decode($_POST['devices'], true);

	print_r($device_array);

	if ($table == 'samples') {

		//get customer_id
		$customer_id_result = $db->prepare("SELECT s.customer_id
											FROM samples s
											WHERE s.id = :request_id
											LIMIT 1");

		$customer_id_result->bindParam(':request_id', $request_id);
		$customer_id_result->execute();

		$customer_id = $customer_id_result->fetch(PDO::FETCH_ASSOC);
		$customer_id = $customer_id['customer_id'];

		foreach ($device_array as $device) {

			$qty = $device['qty'];
			$device_id = getDeviceId($device['name']);

			//original devices before updating
			$original_devices_result = $db->prepare("	SELECT r.qty, d.name
														FROM requested_devices r
														JOIN devices d ON r.device_id = d.id
														WHERE sample_id = :request_id");
			$original_devices_result->bindParam(':request_id', $request_id);
			$original_devices_result->execute();

			//update existing set of devices
			$stmt = $db->prepare("	UPDATE requested_devices
									SET qty = :qty
									WHERE sample_id = :request_id AND device_id = :device_id");
			
			$stmt->bindParam(':qty', $qty);
			$stmt->bindParam(':device_id', $device_id);
			$stmt->bindParam(':request_id', $request_id);
			$stmt->execute();

			//retrieve newly updated set of devices from requested_devices 
			$retreived_devices_result = $db->prepare("	SELECT r.qty, d.name
														FROM requested_devices r
														JOIN devices d ON r.device_id = d.id
														WHERE sample_id = :request_id");
			$retreived_devices_result->bindParam(':request_id', $request_id);
			$retreived_devices_result->execute();

			$retreived_devices = $retreived_devices_result->fetchAll(PDO::FETCH_ASSOC);
			$original_devices = $original_devices_result->fetchAll(PDO::FETCH_ASSOC);

			//if original set is the same as new set, insert this device, if not, continue to next iteration
			if ($original_devices == $retreived_devices) {	

				$stmt = $db->prepare("	INSERT INTO requested_devices (qty, device_id, sample_id, customer_id)
										VALUES (:qty, :device_id, :request_id, :customer_id)");
				
				$stmt->bindParam(':qty', $qty);
				$stmt->bindParam(':device_id', $device_id);
				$stmt->bindParam(':request_id', $request_id);
				$stmt->bindParam(':customer_id', $customer_id);
				$stmt->execute();

			} else {
				continue;
			}

			//display retrieved devices
			$device_arr = array();
			foreach ($retreived_devices as $device) {
				$device_arr[] = $device['qty'] . ' ' . $device['name'];
			}

			$devices = implode(", ", $device_arr);

			print_r($devices);
			
			$sample_devices_result = $db->prepare("	UPDATE samples 
													SET devices = :devices
													WHERE id = :request_id");
			$sample_devices_result->bindParam(':devices', $devices);
			$sample_devices_result->bindParam(':request_id', $request_id);
			$sample_devices_result->execute();

		}

	} elseif ($table == 'replacements') {

	} elseif ($table == 'returns') {

	} else {
		echo 'no table by that name exists';
	}

?>