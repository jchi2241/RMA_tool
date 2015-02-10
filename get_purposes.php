<?php

	include 'configPDO.php';

	$stmt = $db->prepare('SELECT purpose FROM sample_request_purposes ORDER BY id ASC');
	$stmt->execute();

	$array = [];

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

		array_push($array, $row['purpose']);
		
	}

	print_r(json_encode($array, JSON_FORCE_OBJECT));

?>