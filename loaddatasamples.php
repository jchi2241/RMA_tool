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
$grid->addColumn('devices', 'Devices', 'string');
$grid->addColumn('reason', 'Reason', 'string');
$grid->addColumn('rma_id', 'RMA #', 'string');
$grid->addColumn('reference_id', 'Reference #', 'string');  
$grid->addColumn('action', 'Action', 'html', NULL, false, 'id');                                    
                                                                       
$result = $mysqli->query(  'SELECT s.created_at, c.full_name, s.devices, s.reason, s.rma_id, s.reference_id
							FROM customers c, samples s
							WHERE c.id = s.customer_id
							ORDER BY s.created_at DESC');
$mysqli->close();

// send data to the browser
$grid->renderJSON($result);

