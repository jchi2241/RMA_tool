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

	var datagrid = new DatabaseGrid('customers');

	$("#filter").keyup(function() {
      datagrid.editableGrid.filter( $(this).val());
    });

    e.preventDefault();
});

$('#samples').on('click', function(e){

	/*$('#tablecontent').html('<h1>Loading...</h1>');*/
	$('#tables').find('.active').toggleClass('active');
	$(this).toggleClass('active');

	DatabaseGrid.prototype.initializeGrid = function(grid) {
		// render for the action column
		grid.setCellRenderer("action", new CellRenderer({ 
			render: function(cell, id) {                 
			      cell.innerHTML+= "<i onclick=\"datagrid.deleteRow("+id+");\" class='fa fa-trash-o' ></i>";
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
		// render for the action column
		grid.setCellRenderer("action", new CellRenderer({ 
			render: function(cell, id) {                 
			      cell.innerHTML+= "<i onclick=\"datagrid.deleteRow("+id+");\" class='fa fa-trash-o' ></i>";
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
		// render for the action column
		grid.setCellRenderer("action", new CellRenderer({ 
			render: function(cell, id) {                 
			      cell.innerHTML+= "<i onclick=\"datagrid.deleteRow("+id+");\" class='fa fa-trash-o' ></i>";
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
		// render for the action column
		grid.setCellRenderer("action", new CellRenderer({ 
			render: function(cell, id) {                 
			      cell.innerHTML+= "<i onclick=\"datagrid.deleteRow("+id+");\" class='fa fa-trash-o' ></i>";
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