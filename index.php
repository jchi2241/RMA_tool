<?php
	session_start();

	//If not logged in, redirect to log-in page
	if ( !isset($_SESSION['email']) ) {
		header("Location: ./login/");
		die();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Requests - Home</title>

	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/responsive.css" type="text/css" media="screen">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/css/bootstrap.min.css" type="text/css" media="screen">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="css/font-awesome-4.1.0/css/font-awesome.min.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/main.css" type="text/css" media="screen">
</head>
<body>

	<a id="printForm" href="print_form.html" target="_tab"></a>

	<div class="modal fade" id="editDevicesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h3 class="modal-title" id="editDevicesModal_Label"></h3>
	      </div>
	      <div class="modal-body">
        	<div id='edit_product_container' class='row'>
        		<div class="input-group">
					<div id="btn_group_edit_product_type" class="input-group-btn">
						<button id="edit_product_type" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							<span class="caret"></span>
						</button>
						<ul id="edit_product_group_list" class="dropdown-menu" role="menu"></ul>
					</div>
					<input id="edit_product_qty" type="number" min="1" value="1" class="form-control" required>
					<div class="input-group-btn">
						<button id="edit_product_add_btn" class="btn btn-primary" type="button">Add</button>
					</div>
				</div>
        	</div>
        	</br>
        	<div id='edit_product_list_container' class='row'>
        		<ul id='edit_product_list' class='list-group'></ul>
        	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button id="editDevicesModal_Save" type="button" class="btn btn-primary">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="container">
		<div class="page-header"><h1>Product Replacement Sheet</h1></div>

		<div id="message"></div>

		<div class="row">

			<form id="ajaxform" method="post" class="form-horizontal">
				<fieldset>

				<div class="col-md-5">
					<!-- <div class="page-header"><h3>Step 1: <small>Choose a form</small></h3></div>
					
					<div class="form-group">
					  <label class="col-md-3 control-label" for="formType"></label>
					  <div class="col-md-9">
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
					</div> -->

					<!-- <div class="form-group">
					  <label class="col-md-3 control-label" for="earlyShip"></label>
					  <div class="col-md-9">
					  <div class="checkbox">
					    <label for="earlyShip-0">
					      <input id="earlyShip_btn" type="checkbox" name="earlyShip" value="checked">
					      Are you waiting for the customer's package?
					    </label>
						</div>
					  </div>
					</div> -->

					<div class="page-header"><h3>Step 1: <small>Enter Ticket Info</small></h3></div>

					<div class="form-group">
					  <label class="col-md-3 control-label" for="ticket_id">ZD Ticket #</label>
					  <div class="col-md-4">                     
					    <input type="text" class="form-control" name="ticket_id" placeholder="" required/>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-3 control-label" for="purchased_at">Purchased at</label>
					  <div class="col-md-4">                     
					    <input type="text" class="form-control" name="purchased_at" placeholder="" required/>
					  </div>
					</div>

					<div class="page-header"><h3>Step 2: <small>Choose product(s) to replace</small></h3></div>

					<div id="product_group" class="form-group">
						<label class="col-md-3 control-label">Product</label>
					  	<div class="col-md-9">
						    <div class="input-group">
						        <div id="btn_group_product_type" class="input-group-btn">
							        <button id="product_type" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
							        <ul id="product_group_list" class="dropdown-menu" role="menu"></ul>
						        </div>
						        <input id="product_qty" type="number" min="1" value="1" class="form-control" required>
						        <div class="input-group-btn">
							  		<button id="product_add_btn" class="btn btn-primary" type="button">Add</button>
							  	</div>
						    </div>
					  	</div>
					</div>

					<!-- selected products list -->
					<div id="product_list_form_group" class="form-group">
						<label class="col-md-3 control-label"></label>
						<div class="col-md-9">
							<ul id="product_list" class="list-group"></ul>
						</div>
					</div>

					<div class="form-group">
					  <label class="col-md-3 control-label" for="reason">Reason for Replacement</label>
					  <div class="col-md-9">                     
					    <textarea class="form-control" name="reason" placeholder="The reason for this request"></textarea>
					  </div>
					</div>

					<!-- <div class="form-group">
					  <label class="col-md-3 control-label" for="special_req">Any special requirements?</label>
					  <div class="col-md-9">                     
					    <textarea class="form-control" name="special_req" placeholder=""></textarea>
					  </div>
					</div> -->
				</div>

				<div class="col-md-7">
					<div class="page-header"><h3>Step 3: <small>Enter Customer Info</small></h3></div>

					<!-- <div id="sample_purpose_group" class="form-group">
					  <label class="col-md-2 control-label" for="full_name">Purpose</label>  
					  <div class="col-md-6">
						<div id="btn_group_purpose" class="btn-group dropdown">
							<button class="btn btn-default dropdown-toggle" type="button" id="purpose" data-toggle="dropdown" aria-expanded="true">
								<span class="caret"></span>
							</button>
							<ul id="purpose_list" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1"></ul>
						</div>
					  </div>
					</div> -->

					<!-- <div id="business_name_group" class="form-group">
					  <label class="col-md-2 control-label" for="full_name">Business</label>  
					  <div class="col-md-6">
					  <input name="business_name" type="text" class="form-control input-md">
					    
					  </div>
					</div> -->


					<div class="form-group">
					  <label class="col-md-2 control-label" for="full_name">Full Name</label>  
					  <div class="col-md-6">
					  <input name="full_name" type="text" class="form-control input-md" required="">
					    
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="email">Email</label>  
					  <div class="col-md-6">
						<input name="email" type="text" placeholder="example@example.com" class="form-control input-md">
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="address">Address</label>
					  <div class="col-md-6">                     
					    <input name="address" type="text" placeholder="Street Address, Apt/Suite/Unit/Blding/Floor" class="form-control input-md">
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="city">City</label>
					  <div class="col-md-6">                     
					    <input name="city" type="text" class="form-control input-md">
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="zip_postal">State</label>
					  <div class="col-md-6">                     
					    <input name="state" type="text" class="form-control input-md">
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="zip_postal">ZIP/Postal</label>
					  <div class="col-md-6">                     
					    <input name="zip_postal" type="text" class="form-control input-md">
					  </div>
					</div>

					<div id="country_group" class="form-group">
					  <label class="col-md-2 control-label" for="country">Country</label>
					  <div class="col-md-6">                     
					    <div id="btn_group_country" class="btn-group dropdown">
						  <button class="btn btn-default dropdown-toggle" type="button" id="country" data-toggle="dropdown" aria-expanded="true">
						    <!-- <span class="caret"></span> -->
						  </button>
						  <ul id="country_list" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1"></ul>
						</div>
					  </div>
					</div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="phone_number">Phone</label>  
					  <div class="col-md-6">
					  <input name="phone_number" type="text" class="form-control input-md">
					  </div>
					</div>

					<!-- <div id="refund_amount_group" class="form-group">
						<label class="col-md-2 control-label" for="refund_amount">Refund Total</label>
						<div class="col-md-4">
							<div class="input-group">
							  <span class="input-group-addon">$</span>
							  <input name="refund_amount" type="text" class="form-control" >
							</div>
						</div>
					</div> -->

					<!-- <div id="tracking_number_group" class="form-group">
					  	<label class="col-md-2 control-label" for="tracking_number">Tracking</label>  
					  	<div class="col-md-6">
					  		<div class="input-group">
							  	<input name="tracking_number" type="text" placeholder="Enter tracking number here..." class="form-control input-md"> 
							  	<div id="btn_group_shipping_carrier" class="input-group-btn">
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
					</div> -->

					<!-- Button -->
					<div class="form-group">
					  <label class="col-md-2 control-label" for="submit"></label>
					  <div class="col-md-6">
					    <button type="submit" id="submitForm" name="submit" class="btn btn-primary">Submit</button>
					  </div>
					</div>
				</div>
				</fieldset>
			</form>
		</div>
	</div>

	<ul id="tables" class="nav nav-tabs">
		<li id="replacements" role="presentation" class="active"><a href="#">Replacements</a></li>
		<li id="customers" role="presentation"><a href="#">Customers</a></li>
	</ul>

	<br/>

    <div id="toolbar">
      <input type="text" id="filter" full_name="filter" placeholder="Search"  />
    </div>

	<!-- Grid contents -->
	<div id="tablecontent"><h1>Loading...</h1></div>

	<!-- Paginator control -->
	<div id="paginator"></div>

	<script src="js/editablegrid.js"></script>  
	<script src="js/jquery-1.11.1.min.js" ></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="js/databasegrid.js" ></script>
	<script src="js/loadgrids.js" ></script>
	<script src="js/helpers.js"></script>
	<script src="js/main.js"></script>
</body>

</html>