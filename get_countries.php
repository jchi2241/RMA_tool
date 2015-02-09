<?php

	include 'configPDO.php';

	$stmt = $db->prepare('SELECT name FROM countries ORDER BY id ASC');
	$stmt->execute();

	$array = [];

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		array_push($array, $row['name']);
		
	}

	print_r(json_encode($array, JSON_FORCE_OBJECT));

?>