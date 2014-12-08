var Customer = Backbone.Model.extend({});

var Customers = Backbone.Collection.extend({
	model: Customer,
	url: "customers.json"
});

var customers = new Customers();

// Define columns
var columns = [{
		name: "ID",
		label: "ID",
		editable: false, 
	    cell: Backgrid.IntegerCell.extend({
	    	orderSeparator: ''
	    })
	}, {
		name: "name",
		label: "Name",
		cell: "string"
	}, {
		name: "email",
		label: "Email",
		cell: Backgrid.EmailCell.extend({})
}];

// Initialize new Grid instance
var grid = new Backgrid.Grid({
	columns: columns,
	collection: customers
});

// Render the grid and attach the root to your HTML document
$("#table").append(grid.render().el);

// Fetch some customers from the url
customers.fetch({reset: true});
