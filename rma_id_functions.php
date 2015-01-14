<?php

function zerofill ($num, $zerofill = 5)
{
	return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
}


function getLastId($table, $col_name) {

	include 'configPDO.php';

	$stmt = $db->prepare("SELECT {$col_name} FROM {$table} ORDER BY id DESC LIMIT 1");
	$stmt->execute();
	$row = $stmt->fetch();

	return $row[$col_name];
}

function newRefId($table) {

	$col_name = 'reference_id';

	$ref_prefix = substr(getLastId($table, $col_name), 0, 1);
	preg_match('/-\d*/', getLastId($table, $col_name), $ref_num);
	$ref_num = ((int) substr($ref_num[0], 1)) + 1;
	$ref_num = zerofill($ref_num, 4);

	return $ref_prefix . date('Y') . '-' . $ref_num;
}
	

function newRMAId($table) {

	$col_name = 'rma_id';

	preg_match('/\D*/', getLastId($table, $col_name), $rma_prefix);
	preg_match('/-\d*/', getLastId($table, $col_name), $rma_num);
	$rma_num = ((int) substr($rma_num[0], 1)) + 1;
	$rma_num = zerofill($rma_num, 4);

	return $rma_prefix[0] . date('mdy') . '-' . $rma_num;
}

// echo newRefId('samples');
// echo ' ';
// echo newRMAId('samples');

?>