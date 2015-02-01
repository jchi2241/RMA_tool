<?php

	include 'configPDO.php';
	include 'helpers.php';

	$table = $_POST['table'];
	$request_id = $_POST['request_id'];
	$device_array = json_decode($_POST['devices'], true);

	echo 'device_array = ';
	echo "\n";
	print_r($device_array);

	if ($table == 'samples') {

		$request_id_col = "sample_id";

	} elseif ($table == 'replacements') {

		$request_id_col = "replacement_id";

	} elseif ($table == 'returns') {

		$request_id_col = "return_id";

	} else {
		echo 'no table by that name exists';
	}

	//get customer_id
	$customer_id_result = $db->prepare("SELECT t.customer_id
										FROM {$table} t
										WHERE t.id = :request_id AND t.deleted = 0
										LIMIT 1");

	$customer_id_result->bindParam(':request_id', $request_id);
	$customer_id_result->execute();

	$customer_id = $customer_id_result->fetch(PDO::FETCH_ASSOC);
	$customer_id = $customer_id['customer_id'];

	foreach ($device_array as $device) {

		//edited devices
		$qty = $device['qty'];
		$device_id = getDeviceId($device['name']);

		//get device count from db 
		$stmt = $db->prepare("	SELECT COUNT(*)
								FROM requested_devices
								WHERE device_id = :device_id AND {$request_id_col} = :request_id AND deleted = 0");

		$stmt->bindParam(':device_id', $device_id);
		$stmt->bindParam(':request_id', $request_id);
		$stmt->execute();

		$db_count = $stmt->fetch(PDO::FETCH_ASSOC);
		$db_count = (int) $db_count['COUNT(*)'];

		echo '$db_count for '.$device['name'].' = ';
		print_r($db_count);
		echo "\n";

		//match count in db with edited list and update db
		if ($db_count == $qty) {

			continue;

		//if device is not in db
		} elseif ($db_count == 0) {

			$loop_count = 0;

			while ($loop_count < $qty) {

				insertIntoRequestedDevices($request_id_col, $device_id, $request_id, $customer_id);
				$loop_count++;

			}

		//if there are more of the device, insert the difference as rows
		} elseif ($db_count < $qty) {

			$loop_count = 0;

			while ($loop_count < ($qty - $db_count)) {

				insertIntoRequestedDevices($request_id_col, $device_id, $request_id, $customer_id);
				$loop_count++;

			}

		//if there are less of the device, hide it
		} elseif ($db_count > $qty) {

			$rows_to_hide = $db_count - $qty;

			echo "\ndevice id: ";
			print_r($device_id);

			echo "\nrows to hide: ";
			print_r($rows_to_hide);

			echo "\nrequest id: ";
			print_r($request_id);

			echo "\n";

			$stmt = $db->prepare("	UPDATE requested_devices
									SET deleted = 1
									WHERE device_id = :device_id AND {$request_id_col} = :request_id AND deleted = 0
									ORDER BY id DESC
									LIMIT {$rows_to_hide}");

			$stmt->bindParam(':device_id', $device_id);
			$stmt->bindParam(':request_id', $request_id);
			$stmt->execute();

		} else {
			echo "other condition for match/count i didn't account for";
		}

	}

	//determine if a device has been deleted 
	$sql = "SELECT COUNT(*), device_id
			FROM requested_devices
			WHERE {$request_id_col} = :request_id AND deleted = 0
			GROUP BY device_id" ;

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':request_id', $request_id);
	$stmt->execute();

	$db_array = [];
	$arr = [];

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$arr['qty'] = $row['COUNT(*)'];
		$arr['name'] = getDeviceName($row['device_id']);
		array_push($db_array, $arr);
	}

	echo "db_array: \n";
	print_r($db_array);

	$array_diff = [];

	foreach ($db_array as $db_device) {

		$in_device_array = false;

		foreach ($device_array as $edited_device) {

			if ($db_device['name'] == $edited_device['name']) {	
				$in_device_array = true;
			}

		}

		if ($in_device_array == false) {
			array_push($array_diff, $db_device);
		}

	}

	echo "difference in array:  \n";
	print_r($array_diff);

	foreach ($array_diff as $device) {

		$device_id = getDeviceId($device['name']);
		$qty = $device['qty'];

		$sql = "UPDATE requested_devices
				SET deleted = 1
				WHERE device_id = :device_id AND {$request_id_col} = :request_id AND deleted = 0
				ORDER BY id DESC
				LIMIT {$qty}";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':device_id', $device_id);
		$stmt->bindParam(':request_id', $request_id);
		$stmt->execute();

	}

	//display final results in table to user

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

	$sql = "UPDATE {$table}
			SET devices = :devices
			WHERE id = :request_id AND deleted = 0";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':devices', $devices);
	$stmt->bindParam(':request_id', $request_id);
	$stmt->execute();

?>