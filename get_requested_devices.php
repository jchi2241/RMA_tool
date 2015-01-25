<?php

	include 'configPDO.php';

	$request_id = $_POST['request_id'];
	$table = $_POST['table'];
	$array = [];

	if ($table == 'samples') {

		$stmt = $db->prepare('	SELECT r.qty, d.name
							  	FROM requested_devices r
							  	JOIN devices d ON r.device_id = d.id
							  	WHERE r.sample_id = :request_id
							  	ORDER BY r.id');

		$stmt->bindParam(':request_id', $request_id);
		$stmt->execute();

	} elseif ($table == 'replacements') {

		$stmt = $db->prepare('	SELECT r.qty, d.name
							  	FROM requested_devices r
							  	JOIN devices d ON r.device_id = d.id
							  	WHERE r.replacement_id = :request_id
							  	ORDER BY r.id');

		$stmt->bindParam(':request_id', $request_id);
		$stmt->execute();

	} elseif ($table == 'returns') {
		
		$stmt = $db->prepare('	SELECT r.qty, d.name
							  	FROM requested_devices r
							  	JOIN devices d ON r.device_id = d.id
							  	WHERE r.return_id = :request_id
							  	ORDER BY r.id');

		$stmt->bindParam(':request_id', $request_id);
		$stmt->execute();

	} else {
		print_r('check table var');
	}

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		array_push($array, $row);
	}

	print_r(json_encode($array, JSON_FORCE_OBJECT));
?>