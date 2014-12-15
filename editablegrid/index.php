<?php
	include 'configPDO.php';

	if (isset($_POST["name"]) && isset($_POST["email"])){

		$name = $_POST["name"];
		$email = $_POST["email"];
	}

	if (isset($_POST["submit"])) {

		if (!empty($_POST["formType"])) {

			//insert customer's info 
			$stmt = $db->prepare("INSERT INTO customers (name, email) VALUES (:name, :email)");

			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':email', $email);
			$stmt->execute();

			$id = $db->lastInsertId();

			//insert customer into either samples or replacments table
			if($_POST["formType"] == "Sample"){
				$stmt = $db->prepare("INSERT INTO samples (customer_ID) VALUES (:id)");
				$stmt->bindParam(':id', $id);
				$stmt->execute();
			} elseif ($_POST["formType"] == "Replacement") {
				$stmt = $db->prepare("INSERT INTO replacements (customer_ID) VALUES (:id)");
				$stmt->bindParam(':id', $id);
				$stmt->execute();
			} else {
				echo "check your formType values";
			}

			//insert customer into early_ships table if checkbox is checked.
			if(isset($_POST["earlyShip"])) {
				$stmt = $db->prepare("INSERT INTO early_ships (customer_ID) VALUES (:id)");
				$stmt->bindParam(':id', $id);
				$stmt->execute();
			}

			//redirect to clear _POST variables
			header("Location:/website/projects/RMA_1/editablegrid/index.php");

		} else {

			echo "Choose a Form.";

		} 
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Requests - Home</title>

	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/responsive.css" type="text/css" media="screen">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/css/bootstrap.min.css" type="text/css" media="screen">

    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="css/font-awesome-4.1.0/css/font-awesome.min.css" type="text/css" media="screen">

</head>

<body>
	<div class="container">
		
		<?php include 'new_request.html'; ?>

	</div>

	<div class="row">
		<ul class="nav nav-tabs nav-justified">
			<li role="presentation" class="active"><a href="index.php">Customers</a></li>
			<li role="presentation"><a href="#">Samples</a></li>
			<li role="presentation"><a href="#">Replacements</a></li>
			<li role="presentation"><a href="early_ships.php">Early-Ships</a></li>
		</ul>
	</div>

	<div id="wrap">
	    <div id="toolbar">
	      <input type="text" id="filter" name="filter" placeholder="Search"  />
	    </div>
		<!-- Grid contents -->
		<div id="tablecontent"></div>
	
		<!-- Paginator control -->
		<div id="paginator"></div>
	</div>

	<script src="js/editablegrid-2.1.0-b13.js"></script>   
	<script src="js/jquery-1.11.1.min.js" ></script>
	<!-- EditableGrid test if jQuery UI is present. If present, a datepicker is automatically used for date type -->
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<script src="js/customers.js" ></script>
	<script type="text/javascript">
	    var datagrid = new DatabaseGrid();
		window.onload = function() { 

	        // key typed in the filter field
	      	$("#filter").keyup(function() {
	          datagrid.editableGrid.filter( $(this).val());

	          // To filter on some columns, you can set an array of column index 
	          //datagrid.editableGrid.filter( $(this).val(), [0,3,5]);
	        });
		}; 
	</script>
</body>

</html>