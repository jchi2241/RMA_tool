<?php

	include 'configPDO.php';

	$request_id = $_POST['request_id'];
	$table = $_POST['table'];
	$array = [];
	$arr = [];

	if ($table == 'samples') {

		$request_id_col = "sample_id";

	} elseif ($table == 'replacements') {

		$request_id_col = "replacement_id";

	} elseif ($table == 'returns') {

		$request_id_col = "return_id";

	} else {
		print_r('check table var');
	}

	$stmt = $db->prepare('	SELECT COUNT(*), d.name  
						  	FROM requested_devices r
						  	JOIN devices d ON r.device_id = d.id
						  	WHERE r.'.$request_id_col.' = :request_id AND r.deleted = 0
						  	GROUP BY d.name');

	$stmt->bindParam(':request_id', $request_id);
	$stmt->execute();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

		$arr['qty'] = (int) $row['COUNT(*)'];
		$arr['name'] = $row['name'];

		array_push($array, $arr);
		
	}

	print_r(json_encode($array));

?>