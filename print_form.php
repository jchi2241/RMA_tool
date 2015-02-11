<?php

include 'configPDO.php';

// print_r($_GET['request_id']);
// echo "\n";
// print_r($_GET['table']);

$table = $_GET['table'];
$request_id = $_GET['request_id'];
$return =  [];

if ($table == 'samples') {

	$request_id_col = "sample_id";

} elseif ($table == 'replacements') {

	$request_id_col = "replacement_id";

} elseif ($table == 'returns') {

	$request_id_col = "return_id";

} else {
	print_r('check table var');
}

$sql = "SELECT t.reason, t.special_req, t.rma_id, t.reference_id, t.updated_at, c.business_name, c.full_name, c.email, c.address, c.city, c.state, c.zip_postal, c.country, c.phone_number 
		FROM customers c RIGHT JOIN {$table} t ON c.id = t.customer_id
		JOIN requested_devices r ON t.id = r.{$request_id_col}
		WHERE t.id = :request_id AND t.deleted = 0
		LIMIT 1";

$stmt = $db->prepare($sql);
$stmt->bindParam(':request_id', $request_id);
$stmt->execute();

$return['requested_info'] = $stmt->fetch(PDO::FETCH_ASSOC);

if ($table == 'samples') {

	$sql = "SELECT p.purpose
			FROM samples s, sample_request_purposes p
			WHERE p.id = s.purpose_id AND s.id = :request_id
			LIMIT 1";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':request_id', $request_id);
	$stmt->execute();

	$return['purpose'] = $stmt->fetch(PDO::FETCH_ASSOC);
}

$sql = "SELECT COUNT(*), d.name  
	  	FROM requested_devices r
	  	JOIN devices d ON r.device_id = d.id
	  	WHERE r.".$request_id_col." = :request_id AND r.deleted = 0
	  	GROUP BY d.name";

$stmt = $db->prepare($sql);
$stmt->bindParam(':request_id', $request_id);
$stmt->execute();

$requested_devices = [];
$arr = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

		$arr['qty'] = (int) $row['COUNT(*)'];
		$arr['name'] = $row['name'];

		array_push($requested_devices, $arr);
		
}

$return['requested_devices'] = $requested_devices;

print_r(json_encode($return));

?>