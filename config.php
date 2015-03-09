<?php

	$config = [
	    "db_host" => "localhost",
	    "db_user" => "root",
	    "db_password" => "mii3Binn",
	    "db_name" => "replacement_sheet"
	];

    $mysqli = new mysqli($config['db_host'], $config['db_user'], $config['db_password'], $config['db_name']);

    if (mysqli_connect_errno()) {
        exit('Connect failed: '. mysqli_connect_error());
    }
?>
