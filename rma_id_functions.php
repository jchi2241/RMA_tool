<?php

function getLastRMAId($table) {

	include 'configPDO.php';

	$stmt = $db->prepare("SELECT rma_id FROM {$table} ORDER BY id DESC LIMIT 1");
	$stmt->execute();
	$row = $stmt->fetch();

	return $row['rma_id'];
}

function getLastRefId($table) {

	include 'configPDO.php';

	$stmt = $db->prepare("SELECT reference_id FROM {$table} ORDER BY id DESC LIMIT 1");
	$stmt->execute();
	$row = $stmt->fetch();

	return $row['reference_id'];
}

function newRefId($table) {

	$ref_prefix = substr(getLastRefId($table), 0, 1);
	preg_match('/-\d*/', getLastRefId($table), $ref_num);
	$ref_num = ((int) substr($ref_num[0], 1)) + 1;

	return $ref_prefix . date('Y') . '-' . $ref_num;
}
	

function newRMAId($table) {

	preg_match('/\D*/', getLastRMAId($table), $rma_prefix);
	preg_match('/-\d*/', getLastRMAId($table), $rma_num);
	$rma_num = ((int) substr($rma_num[0], 1)) + 1; 

	return $rma_prefix[0] . date('mdy') . '-' . $rma_num;
}

// echo newRefId('samples');
// echo ' ';
// echo newRMAId('samples');

?>