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
		margin-right: 15px;
	}

	#tracking_number_group {
		display: none;
	}

	#refund_amount_group {
		display: none;
	}

	#product_list_header {
		display: none;
	}
</style>

<body>
	<div class="container">
		<div class="page-header"><h1>Create a Request Form</h1></div>

		<div id="message"></div>

		<div class="row">

			<form id="ajaxform" method="post" class="form-horizontal">
				<fieldset>

				<div class="col-md-5">
					<div class="page-header"><h3>Step 1: <small>Choose a form</small></h3></div>
					
					<div class="form-group">
					  <label class="col-md-2 control-label" for="formType"></label>
					  <div class="col-md-4">
					  <div class="radio">
					    <label for="formType-0">
					      <input id="sample_btn" type="radio" name="formType" value="Sample" checked="checked">
					      Sample
					    </label>
						</div>
					  <div class="radio">
					    <label for="formType-1">
					      <input id="replacement_btn" type="radio" name="formType" value="Replacement">
					      Replacement
					    </label>
						</div>
						<div class="radio">
					    <label for="formType-1">
					      <input id="return_btn" type="radio" name="formType" value="Return">
					      Return
					    </label>
						</div>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="earlyShip"></label>
					  <div class="col-md-10">
					  <div class="checkbox">
					    <label for="earlyShip-0">
					      <input id="earlyShip_btn" type="checkbox" name="earlyShip" value="checked">
					      Are you waiting for the customer's package?
					    </label>
						</div>
					  </div>
					</div>
				</div>

				<div class="col-md-7">
					<div class="page-header"><h3>Step 2: <small>Fill out the form</small></h3></div>
					<div class="form-group">
					  <label class="col-md-2 control-label" for="full_name">Name</label>  
					  <div class="col-md-7">
					  <input name="full_name" type="text" placeholder="Full name" class="form-control input-md" required="">
					    
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="email">Email</label>  
					  <div class="col-md-7">
					  <input name="email" type="text" placeholder="example@example.com" class="form-control input-md" required="">
					    
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="shipping_address">Address</label>
					  <div class="col-md-7">                     
					    <textarea class="form-control" name="shipping_address" rows="3" placeholder="123 Example Rd.&#10;City, AB 98765"></textarea>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="phone_number">Phone</label>  
					  <div class="col-md-6">
					  <input name="phone_number" type="text" class="form-control input-md">
					    
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="reason">Reason</label>
					  <div class="col-md-6">                     
					    <textarea class="form-control" name="reason" placeholder="Enter reason for RMA here..."></textarea>
					  </div>
					</div>

					<div id="product_group" class="form-group">
						<label class="col-md-2 control-label">Product</label>
					  	<div class="col-md-6">
						    <div class="input-group">
						        <div class="input-group-btn">
							        <button id="product_type" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
							        <ul id="product_group_list" class="dropdown-menu" role="menu"></ul>
						        </div>
						        <input id="product_qty" type="number" min="1" value="1" class="form-control" placeholder="How many?" required>
						        <div class="input-group-btn">
							  		<button id="product_add_btn" class="btn btn-primary" type="button">Add</button>
							  	</div>
						    </div>
					  	</div>
					  	
					</div>

					<!-- selected products list -->
					<div class="form-group">
						<label class="col-md-2 control-label"></label>
						<div class="col-md-6">
							<ul id="product_list" class="list-group"><li id="product_list_header" class="list-group-item active">Product List</li></ul>
						</div>
					</div>

					<div id="refund_amount_group" class="form-group">
						<label class="col-md-2 control-label" for="refund_amount">Refund Total</label>
						<div class="col-md-4">
							<div class="input-group">
							  <span class="input-group-addon">$</span>
							  <input name="refund_amount" type="text" class="form-control" >
							</div>
						</div>
					</div>

					<div id="tracking_number_group" class="form-group">
					  	<label class="col-md-2 control-label" for="tracking_number">Tracking #</label>  
					  	<div class="col-md-6">
					  		<div class="input-group">
							  	<input name="tracking_number" type="text" placeholder="Enter tracking number here..." class="form-control input-md"> 
							  	<div class="input-group-btn">
									<button id="shipping_carrier" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										FedEx <span class="caret"></span>
									</button>
									<ul id="shipping_carrier_list" class="dropdown-menu" role="menu">
										<li><a href="#">FedEx</a></li>
										<li><a href="#">UPS</a></li>
										<li><a href="#">USPS</a></li>
									</ul>
								</div>
							</div>
					  	</div>
					</div>

					<!-- Button -->
					<div class="form-group">
					  <label class="col-md-2 control-label" for="submit"></label>
					  <div class="col-md-4">
					    <button type="submit" id="submitForm" name="submit" class="btn btn-primary">Submit</button>
					  </div>
					</div>
				</div>
				</fieldset>
			</form>
		</div>

		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading"><h3>Tables</h3></div>
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
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="js/databasegrid.js" ></script>
	<script src="js/loadgrids.js" ></script>

	<script type="text/javascript">

		var datagrid = new DatabaseGrid('customers');

		$("#filter").keyup(function() {
	      datagrid.editableGrid.filter( $(this).val());
	    });

		window.onload = function() { 
			
	      	$("#filter").keyup(function() {
	          datagrid.editableGrid.filter( $(this).val());
	        });

			changeDropdownValue('#tracking_number_group', '#shipping_carrier');
			changeDropdownValue('#product_group', '#product_type');

			//adding products into for #product_list
			var productList = {};

			$('#product_add_btn').on('click', function(e) {

				var qty = $('#product_qty').val().trim();
				var productType = $('#product_type').text().trim();

				if (qty !== '' && parseInt(qty) !== 0) {
					
					if (productList[productType] === undefined) {
						productList[productType] = parseInt(qty);
						$('#product_list').append('<li class="list-group-item"><span class="badge">' + qty + '</span>' + productType + '<button type="button" class="close" aria-hidden="true">&times;</button></li>');
					} else {
						productList[productType] += parseInt(qty);
						$('#product_list li:contains(' + productType + ')').find('span').html(productList[productType]);
					}

					if (!$.isEmptyObject(productList)){
						$('#product_list_header').show();
					}

				} else {
					alert('Enter a number greater than 0');
				}

				console.log(productList);
				e.preventDefault();
			});

			//removing products from #product_list
			$('#product_list').on('click', '.close', function(e) {

				var productToRemove = $(this).parent().text().replace(/[0-9]/g, '').slice(0, -1);
				console.log('productToRemove: ' + productToRemove);

				//remove from Object
				delete productList[productToRemove];

				//remove from DOM
				$(this).parent().remove();

				if($.isEmptyObject(productList)){
					$('#product_list_header').hide();
				}

				console.log(productList);

				e.preventDefault;
			});

			//show appropriate inputs based on what formType is selected
			$('input[name=formType]').on('click', function() {
				
				var formType = $('input:checked').val();

				if(formType === "Sample" || formType === "Replacement"){
					$('#refund_amount_group').hide();
				} else {
					$('#refund_amount_group').show();
				}
			});

			$('#earlyShip_btn').on('click', function() {

				if($(this).prop('checked') === true){
					$('#tracking_number_group').show();
				} else {
					$('#tracking_number_group').hide();
				}	
			});

			//AJAX Post form data to new_request.php
			$('#ajaxform').submit(function(e) {

				var postData = $(this).serializeArray();

				$.ajax({
					type: 'POST',
					url: 'new_request.php',
					data: {
						formType: $('input[name=formType]:checked').val(),
						earlyShip: $('input[name=earlyShip]:checked').val(),
						full_name: $('input[name=full_name]').val().trim(),
						email: $('input[name=email]').val().trim(),
						shipping_address: $('textarea[name=shipping_address]').val().trim(),
						phone_number: $('input[name=phone_number]').val().trim(),
						reason: $('textarea[name=reason]').val().trim(),
						refund_amount: $('input[name=refund_amount]').val().trim(),
						tracking_number: $('input[name=tracking_number]').val().trim(),
						shipping_carrier: $('#shipping_carrier').text().trim(),
						devices: JSON.stringify(productList)
					},
					success: function(data) {
						console.log(data);
						alert(data);
						//alert('successfully added, maybe popup a new window to print page');
						location.reload();
					},
					error: function() {
						alert('post FAILED');
					}
				});

				e.preventDefault();
			});
		}; 

		//fill up #product_list with devices from table 'devices'
		$.get('get_devices.php', function (data) {
			var devices = $.parseJSON(data);
		
			$('#product_type').text(devices[0]);

			var product_group_list = '<li><a href="#" style="display:none;">' + devices[0] + '</a></li>';

			for (var index in devices) {
				if (index !== '0' && devices.hasOwnProperty(index)) {
					product_group_list += '<li><a href="#">' + devices[index] + '</a></li>';
					$('#product_group_list').html(product_group_list);
				}
			}
		});

		var changeDropdownValue = function(parentElement, button) {

			$(parentElement).on('click', 'a', function(e) {
				$(parentElement).find('a').show();
				$(button).html($(this).html() + ' <span class="caret"></span>');
				$(this).hide();
				e.preventDefault();
			});
		};
	</script>
</body>

</html>