<?php

	require_once 'connectdb.php';

	//select last inserted row
	$query = "SELECT * FROM customers ORDER BY id DESC LIMIT 1"; 

	if(!$result = $db->query($query)){
		die('There was an error running the query [' .$db->error .']');
	}

	$lastRow = mysqli_fetch_array($result);

	//grab the customer ID that was just inserted
	$result = $db->query("SELECT id FROM customers ORDER BY id DESC LIMIT 1");
	$lastID = mysqli_fetch_array($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title></title>
 
<!-- <link rel="stylesheet" href="css/main.css" type="text/css" /> -->
 
<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!--[if lte IE 7]>
	<script src="js/IE8.js" type="text/javascript"></script><![endif]-->
<!--[if lt IE 7]>
 
	<link rel="stylesheet" type="text/css" media="all" href="css/ie6.css"/><![endif]-->

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

</head>
 
<body id="index" class="home">

	<p><b>name:</b> <?php echo $lastRow["name"] ?></p>
	<p><b>email:</b> <?php echo $lastRow["email"] ?></p>

</body>
</html>