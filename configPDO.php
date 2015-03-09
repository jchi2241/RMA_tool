<?php

try {

	$db = new PDO( 'mysql:host=localhost;dbname=replacement_sheet', 'root', 'mii3Binn' );
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(Exception $e){

	echo $e->getMessage();
	
}

?>