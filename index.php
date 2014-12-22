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

<style>
	.close {
		color: red;
		float: left;
	}
</style>

<body>
	<div class="container">
		<div class="page-header"><h1>Request Form</h1></div>
		<div class="row">
			<div class="col-md-8 col-md-offset-1">
				<form method="post" class="form-horizontal">
					<fieldset>
					<div class="form-group">
					  <label class="col-md-4 control-label" for="earlyShip">Waiting for customer's package?</label>
					  <div class="col-md-4">
					  <div class="checkbox">
					    <label for="earlyShip-0">
					      <input type="checkbox" name="earlyShip" value="checked">
					      yes
					    </label>
						</div>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-4 control-label" for="formType">Request form</label>
					  <div class="col-md-4">
					  <div class="radio">
					    <label for="formType-0">
					      <input type="radio" name="formType" value="Sample" checked="checked">
					      Sample
					    </label>
						</div>
					  <div class="radio">
					    <label for="formType-1">
					      <input type="radio" name="formType" value="Replacement">
					      Replacement
					    </label>
						</div>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-4 control-label" for="full_name">Name</label>  
					  <div class="col-md-6">
					  <input name="full_name" type="text" placeholder="Enter name here..." class="form-control input-md" required="">
					    
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-4 control-label" for="email">Email</label>  
					  <div class="col-md-6">
					  <input name="email" type="text" placeholder="Enter email here..." class="form-control input-md" required="">
					    
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-4 control-label" for="shipping_address">Shipping address</label>
					  <div class="col-md-4">                     
					    <textarea class="form-control" name="shipping_address"></textarea>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-4 control-label" for="phone_number">Contact number</label>  
					  <div class="col-md-4">
					  <input name="phone_number" type="text" placeholder="Enter number here..." class="form-control input-md">
					    
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-4 control-label" for="reason">Reason</label>
					  <div class="col-md-4">                     
					    <textarea class="form-control" name="reason"></textarea>
					  </div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="refund_amount">Refund Total</label>
						<div class="col-md-4">
							<div class="input-group">
							  <span class="input-group-addon">$</span>
							  <input name="refund_amount" type="text" class="form-control" >
							</div>
						</div>
					</div>

					<div class="form-group">
					  	<label class="col-md-4 control-label" for="tracking_number">Tracking #</label>  
					  	<div class="col-md-6">
					  		<div class="input-group">
							  	<input name="tracking_number" type="text" placeholder="Enter tracking number here..." class="form-control input-md"> 
							  	<div class="input-group-btn">
									<button id="shipping_carrier" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										FedEx <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#" style="display:none;">FedEx</a></li>
										<li><a href="#">UPS</a></li>
										<li><a href="#">USPS</a></li>
									</ul>
								</div>
							</div>
					  	</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label">Product</label>
						<div class="row">
						  	<div class="col-md-4">
							    <div class="input-group">
							        <input id="product_qty" type="text" class="form-control">
							        <div class="input-group-btn">
								        <button id="product_type" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Contact Sensor <span class="caret"></span></button>
								        <ul class="dropdown-menu" role="menu">
								        	<li><a href="#">Contact Sensor</a></li>
								        	<li><a href="#">Motion Sensor</a></li>
								         	<li><a href="#">Remote Tag</a></li>
								         	<li><a href="#">Smart Switch</a></li>
								          	<li><a href="#">CubeOne</a></li>
								          	<li><a href="#">iCamera (1st Gen)</a></li>
								          	<li><a href="#">iCamera KEEP</a></li>
								          	<li class="divider"></li>
								          	<li><a href="#">Preferred Package</a></li>
								          	<li><a href="#">Premium Package</a></li>
								          	<li><a href="#">Deluxe Package (Apple)</a></li>
								        </ul>
							        </div>
							    </div>
						  	</div>
						  	<div class="col-md-1">
						  		<button id="product_add_btn" class="btn btn-default" type="button">Add</button>
						  	</div>
					  	</div>
					</div>

					<!-- selected products list -->
					<div class="form-group">
						<label class="col-md-4 control-label"></label>
						<div class="row">
							<div class="col-md-4">
								<ul id="product_selected" class="list-group"></ul>
							</div>
						</div>
					</div>

					<!-- Button -->
					<div class="form-group">
					  <label class="col-md-4 control-label" for="submit"></label>
					  <div class="col-md-4">
					    <button type="submit" id="submitForm" name="submit" class="btn btn-primary">Submit</button>
					  </div>
					</div>

					</fieldset>
				</form>
			</div>
		</div>

		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
					<ul id="tables" class="nav nav-tabs">
						<li id="customers" role="presentation" class="active"><a href="#">Customers</a></li>
						<li id="samples" role="presentation"><a href="#">Samples</a></li>
						<li id="replacements" role="presentation"><a href="#">Replacements</a></li>
						<li id="early_ships" role="presentation"><a href="#">Early-Ships</a></li>
						<li id="returns" role="presentation"><a href="#">Returns</a></li>
					</ul>
				
					<br/>

				    <div id="toolbar">
				      <input type="text" id="filter" full_name="filter" placeholder="Search"  />
				    </div>
					<!-- Grid contents -->
					<div id="tablecontent"><h1>Loading...</h1></div>
				
					<!-- Paginator control -->
					<div id="paginator"></div>

				</div>
			</div>
		</div>
	</div>
	
	<script src="js/editablegrid-2.1.0-b13.js"></script>   
	<script src="js/jquery-1.11.1.min.js" ></script>
	<!-- EditableGrid test if jQuery UI is present. If present, a datepicker is automatically used for date type -->
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="js/customers.js" ></script>
	<script src="js/loadgrids.js" ></script>

	<script type="text/javascript">

		var datagrid = new DatabaseGrid();

		window.onload = function() { 
			
	      	$("#filter").keyup(function() {
	          datagrid.editableGrid.filter( $(this).val());

	          // To filter on some columns, you can set an array of column index 
	          //datagrid.editableGrid.filter( $(this).val(), [0,3,5]);
	        });

			//change dropdown button values for shipping_carrier button
			changeDropdownValue($('#shipping_carrier').next().find('a'), '#shipping_carrier');

			//change dropdown button values for product_type button
			changeDropdownValue($('#product_type').next().find('a'), '#product_type');

			//functionality for the add button for products
			var productAdded = {};

			$('#product_add_btn').on('click', function(e) {

				var qty = $('#product_qty').val().trim();
				var productType = $('#product_type').text().trim();

				if(qty !== '') {
					
					if(productAdded[productType] === undefined) {
						productAdded[productType] = parseInt(qty);
						$('#product_selected').append('<li class="list-group-item"><span class="badge">' + qty + '</span>' + productType + '<button type="button" class="close" aria-hidden="true">&times;</button></li>');
					}else {
						productAdded[productType] += parseInt(qty);
						$('#product_selected li:contains(' + productType + ')').find('span').html(productAdded[productType]);
					}

				}else {
					alert('Enter the # of products');
				}

				console.log(productAdded);
				e.preventDefault();
			});

			//functionality for the close button in #product_selected
			$('#product_selected').on('click', '.close', function(e) {

				var productToRemove = $(this).parent().text().replace(/[0-9]/g, '').slice(0, -1);
				console.log('productToRemove: ' + productToRemove);

				//remove from Object
				delete productAdded[productToRemove];

				//remove from DOM
				$(this).parent().remove();
				console.log(productAdded);

				e.preventDefault;
			});
		}; 

		var changeDropdownValue = function(optionsList, button) {
			optionsList.on('click', function(e) {
				optionsList.show();
				$(button).html($(this).html() + ' <span class="caret"></span>');
				$(this).hide();
				e.preventDefault();
			});
		};
	</script>
</body>

</html>