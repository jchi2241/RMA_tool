<?php include 'new_request.php'; ?>

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
		
		<h1>New Request</h1>

		<form method="post">

			<input type="checkbox" name="earlyShip" value="checked"> Early Ship<br />
			<input type="radio" name="formType" value="Sample"> Sample<br />
			<input type="radio" name="formType" value="Replacement"> Replacement<br /><br />

			<label for="full_name">Full Name</label>
			<input name="full_name" type="text" />

			<br /><br />

			<label for="email">Email</label>
			<input name="email" type="email" />

			<br /><br />

			<label for="address">Address</label>
			<textarea name="address"></textarea>

			<br /><br />

			<label for="phone_number">Phone Number</label>
			<input name="phone_number" type="tel" />

			<br /><br />

			<input id="submitForm" type="submit" name="submit" value="Submit"/>

		</form>


	</div>

	<div id="tables" class="row">
		<ul class="nav nav-tabs nav-justified">
			<li id="customers" role="presentation" class="active"><a href="#">Customers</a></li>
			<li id="samples" role="presentation"><a href="#">Samples</a></li>
			<li id="replacements" role="presentation"><a href="#">Replacements</a></li>
			<li id="earlyShip" role="presentation"><a href="#">Early-Ships</a></li>
		</ul>
	</div>

	<div id="wrap">
	    <div id="toolbar">
	      <input type="text" id="filter" full_name="filter" placeholder="Search"  />
	    </div>
		<!-- Grid contents -->
		<div id="tablecontent"><h1>Loading...</h1></div>
	
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

		$('#customers').on('click', function(){

			$('#tablecontent').html('<h1>Loading...</h1>');
			$('#tables').find('.active').toggleClass('active');
			$(this).toggleClass('active');

			DatabaseGrid.prototype.fetchGrid = function()  {
				// call the PHP script to get the data
				this.editableGrid.loadJSON("loaddatacustomers.php?db_tablename=customers");
			};

			var datagrid = new DatabaseGrid();

			$("#filter").keyup(function() {
	          datagrid.editableGrid.filter( $(this).val());
	        });
		});

		$('#samples').on('click', function(){

			$('#tablecontent').html('<h1>Loading...</h1>');
			$('#tables').find('.active').toggleClass('active');
			$(this).toggleClass('active');

			DatabaseGrid.prototype.fetchGrid = function()  {
				// call the PHP script to get the data
				this.editableGrid.loadJSON("loaddatasamples.php?db_tablename=samples");
			};

			var samplesGrid = new DatabaseGrid();

			$("#filter").keyup(function() {
	          samplesGrid.editableGrid.filter( $(this).val());
	        });
		});

		$('#replacements').on('click', function(){

			$('#tablecontent').html('<h1>Loading...</h1>');
			$('#tables').find('.active').toggleClass('active');
			$(this).toggleClass('active');

			DatabaseGrid.prototype.fetchGrid = function()  {
				// call the PHP script to get the data
				this.editableGrid.loadJSON("loaddatareplacements.php?db_tablename=replacements");
			};

			var replacementsGrid = new DatabaseGrid();

			$("#filter").keyup(function() {
	          replacementsGrid.editableGrid.filter( $(this).val());
	        });
		});

		$('#earlyShip').on('click', function(){

			$('#tablecontent').html('<h1>Loading...</h1>');
			$('#tables').find('.active').toggleClass('active');
			$(this).toggleClass('active');

			DatabaseGrid.prototype.fetchGrid = function()  {
				// call the PHP script to get the data
				this.editableGrid.loadJSON("loaddataearly_ships.php?db_tablename=early_ships");
			};

			var earlyShipGrid = new DatabaseGrid();

			$("#filter").keyup(function() {
	          earlyShipGrid.editableGrid.filter( $(this).val());
	        });
		});

	</script>
</body>

</html>