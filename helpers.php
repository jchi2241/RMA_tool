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

function getDeviceId($device_name) {

	include 'configPDO.php';

	$stmt = $db->prepare("SELECT id, name FROM devices");
	$stmt->execute();

	$device_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($device_list as $device) {
	   if ($device['name'] == $device_name) {
	   		return $device['id'];
	   } 
	}

	
}

function getDeviceName($device_id) {

	include 'configPDO.php';

	$stmt = $db->prepare("SELECT id, name FROM devices");
	$stmt->execute();

	$device_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($device_list as $device) {
		if ($device['id'] == $device_id) {
			return $device['name'];
		}
	}
}

function insertIntoRequestedDevices($request_id, $device_id, $table_id, $customer_id) {

	include 'configPDO.php';

	$stmt = $db->prepare("  INSERT INTO requested_devices (device_id, {$request_id}, customer_id)
							VALUES (:device_id, :table_id, :customer_id)");

	$stmt->bindParam(':device_id', $device_id);
	$stmt->bindParam(':table_id', $table_id);
	$stmt->bindParam(':customer_id', $customer_id);
	$stmt->execute();

}

?>