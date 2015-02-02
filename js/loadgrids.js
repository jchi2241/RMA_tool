$('#customers').on('click', function(e){

	//page jumps because panel size isn't fixed. page height decreases
	//to accomodate h1 element. the fix is to fix the panel height if 
	//we still want the 'Loading...' to appear
	/*$('#tablecontent').html('<h1>Loading...</h1>');*/
	$('#tables').find('.active').toggleClass('active');
	$(this).toggleClass('active');

	DatabaseGrid.prototype.initializeGrid = function(grid) {
		grid.renderGrid("tablecontent", "testgrid");
	};    

	var customersGrid = new DatabaseGrid('customers');

	$("#filter").keyup(function() {
      customersGrid.editableGrid.filter( $(this).val());
    });

    e.preventDefault();
});

$('#samples').on('click', function(e){

	/*$('#tablecontent').html('<h1>Loading...</h1>');*/
	$('#tables').find('.active').toggleClass('active');
	$(this).toggleClass('active');

	DatabaseGrid.prototype.initializeGrid = function(grid) {

		var self = this;
		// render for the action column
		grid.setCellRenderer("action", new CellRenderer({ 
			render: function(cell, id) {                 
			    var i = document.createElement('i');
				i.className = 'fa fa-trash-o';
				i.onclick = self.deleteRow.bind(self, id);
				cell.appendChild(i);

				var print = document.createElement('i');
				print.className = 'fa fa-print';
				print.onclick = self.printForm.bind(self, id);
				cell.appendChild(print);
			}
		}));

		grid.renderGrid("tablecontent", "testgrid");
	};    

	var samplesGrid = new DatabaseGrid('samples');

	$("#filter").keyup(function() {
      samplesGrid.editableGrid.filter( $(this).val());
    });

    e.preventDefault();
});

$('#replacements').on('click', function(e){

	/*$('#tablecontent').html('<h1>Loading...</h1>');*/
	$('#tables').find('.active').toggleClass('active');
	$(this).toggleClass('active');

	DatabaseGrid.prototype.initializeGrid = function(grid) {

		var self = this;
		// render for the action column
		grid.setCellRenderer("action", new CellRenderer({ 
			render: function(cell, id) {                 
			    var i = document.createElement('i');
				i.className = 'fa fa-trash-o';
				i.onclick = self.deleteRow.bind(self, id);
				cell.appendChild(i);
			}
		}));

		grid.renderGrid("tablecontent", "testgrid");
	};    

	var replacementsGrid = new DatabaseGrid('replacements');

	$("#filter").keyup(function() {
      replacementsGrid.editableGrid.filter( $(this).val());
    });

    e.preventDefault();
});

$('#early_ships').on('click', function(e){

	/*$('#tablecontent').html('<h1>Loading...</h1>');*/
	$('#tables').find('.active').toggleClass('active');
	$(this).toggleClass('active');

	DatabaseGrid.prototype.initializeGrid = function(grid) {

		var self = this;
		// render for the action column
		grid.setCellRenderer("action", new CellRenderer({ 
			render: function(cell, id) {                 
			    var i = document.createElement('i');
				i.className = 'fa fa-trash-o';
				i.onclick = self.deleteRow.bind(self, id);
				cell.appendChild(i);
			}
		}));

		grid.renderGrid("tablecontent", "testgrid");
	};    
    

	var earlyShipGrid = new DatabaseGrid('early_ships');

	$("#filter").keyup(function() {
      earlyShipGrid.editableGrid.filter( $(this).val());
    });

    e.preventDefault();
});

$('#returns').on('click', function(e){

	/*$('#tablecontent').html('<h1>Loading...</h1>');*/
	$('#tables').find('.active').toggleClass('active');
	$(this).toggleClass('active');

	DatabaseGrid.prototype.initializeGrid = function(grid) {

		var self = this;
		// render for the action column
		grid.setCellRenderer("action", new CellRenderer({ 
			render: function(cell, id) {                 
			    var i = document.createElement('i');
				i.className = 'fa fa-trash-o';
				i.onclick = self.deleteRow.bind(self, id);
				cell.appendChild(i);
			}
		}));

		grid.renderGrid("tablecontent", "testgrid");
	};        

	var returnsGrid = new DatabaseGrid('returns');

	$("#filter").keyup(function() {
      returnsGrid.editableGrid.filter( $(this).val());
    });

    e.preventDefault();
});