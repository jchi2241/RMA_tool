<?php

require_once 'connect.php';

$stmt = $db->query("SELECT * FROM customers");

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fp = fopen('customers.json', 'w');
fwrite($fp, json_encode($results, JSON_NUMERIC_CHECK));
fclose($fp);

?>