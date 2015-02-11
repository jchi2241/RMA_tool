<?php     


/*
 * examples/mysql/loaddata.php
 * 
 * This file is part of EditableGrid.
 * http://editablegrid.net
 *
 * Copyright (c) 2011 Webismymind SPRL
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://editablegrid.net/license
 */
                              


/**
 * This script loads data from the database and returns it to the js
 *
 */
       
require_once('config.php');      
require_once('EditableGrid.php');            

/*
 * fetch_pairs is a simple method that transforms a mysqli_result object in an array.
 * It will be used to generate possible values for some columns.
*/
function fetch_pairs($mysqli,$query){
	if (!($res = $mysqli->query($query)))return FALSE;
	$rows = array();
	while ($row = $res->fetch_assoc()) {
		$first = true;
		$key = $value = null;
		foreach ($row as $val) {
			if ($first) { $key = $val; $first = false; }
			else { $value = $val; break; } 
		}
		$rows[$key] = $value;
	}
	return $rows;
}


// Database connection
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']); 
                    
// create a new EditableGrid object
$grid = new EditableGrid();

/* 
*  Add columns. The first argument of addColumn is the name of the field in the databse. 
*  The second argument is the label that will be displayed in the header
*/

$grid->addColumn('created_at', 'Date', 'date', NULL, false); 
$grid->addColumn('full_name', 'Name', 'string', NULL, false);
$grid->addColumn('devices', 'Devices', 'string', NULL, false);
$grid->addColumn('reason', 'Reason', 'string');
$grid->addColumn('special_req', 'Special Req', 'string');  
$grid->addColumn('refund_amount', 'Refund $', 'string');
$grid->addColumn('rma_id', 'RMA #', 'string');
$grid->addColumn('reference_id', 'Reference #', 'string');  
$grid->addColumn('action', 'Action', 'html', NULL, false, 'id');                                    
                                                                       
$result = $mysqli->query(  'SELECT r.id, r.created_at, r.devices, c.full_name, r.reason, r.special_req, r.refund_amount, r.rma_id, r.reference_id
							FROM returns r
							LEFT JOIN customers c ON c.id = r.customer_id
							WHERE r.deleted = 0
							ORDER BY r.id DESC');
$mysqli->close();

// send data to the browser
$grid->renderJSON($result);

