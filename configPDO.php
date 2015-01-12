<?php

try {

	$db = new PDO( 'mysql:host=localhost;dbname=rma', 'root', 'mii3Binn' );
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(Exception $e){

	echo $e->getMessage();
	
}

?>