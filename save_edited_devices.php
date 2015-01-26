<?php

	include 'configPDO.php';

	$json = file_get_contents('php://input');
	$device_array = json_decode($json, true);

	print_r($device_array);

?>