<?php

try {

	$db = new PDO( 'mysql:host=localhost;dbname=rma', 'root', 'mii3Binn' );

} catch(Exception $e){

	echo $e->getMessage();
	
}

?>