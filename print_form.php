<?php

include 'configPDO.php';

// print_r($_GET['request_id']);
// echo "\n";
// print_r($_GET['table']);

$table = $_GET['table'];
$request_id = (int) $_GET['request_id'];

if ($table == 'samples') {

	$request_id_col = "sample_id";

} elseif ($table == 'replacements') {

	$request_id_col = "replacement_id";

} elseif ($table == 'returns') {

	$request_id_col = "return_id";

} else {
	print_r('check table var');
}

$sql = "SELECT t.reason, t.rma_id, t.reference_id, t.updated_at, c.full_name, c.email, c.shipping_address, c.phone_number 
		FROM customers c RIGHT JOIN {$table} t ON c.id = t.customer_id
		JOIN requested_devices d ON t.id = d.{$request_id_col}
		WHERE t.id = :request_id AND t.deleted = 0
		LIMIT 1";

$stmt = $db->prepare($sql);
$stmt->bindParam(':request_id', $request_id);
$stmt->execute();

$array = [];

$row = $stmt->fetch(PDO::FETCH_ASSOC);

print_r(json_encode($row));

?>