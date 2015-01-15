$('#customers').on('click', function(e){

	//page jumps because panel size isn't fixed. page height decreases
	//to accomodate h1 element. the fix is to fix the panel height if 
	//we still want the 'Loading...' to appear
	/*$('#tablecontent').html('<h1>Loading...</h1>');*/
	$('#tables').find('.active').toggleClass('active');
	$(this).toggleClass('active');

	DatabaseGrid.prototype.fetchGrid = function()  {
		// call the PHP script to get the data
		this.editableGrid.loadJSON("loaddatacustomers.php?db_tablename=customers");
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

	DatabaseGrid.prototype.fetchGrid = function()  {
		// call the PHP script to get the data
		this.editableGrid.loadJSON("loaddatasamples.php?db_tablename=samples");
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

	DatabaseGrid.prototype.fetchGrid = function()  {
		// call the PHP script to get the data
		this.editableGrid.loadJSON("loaddatareplacements.php?db_tablename=replacements");
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

	DatabaseGrid.prototype.fetchGrid = function()  {
		// call the PHP script to get the data
		this.editableGrid.loadJSON("loaddataearly_ships.php?db_tablename=early_ships");
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

	DatabaseGrid.prototype.fetchGrid = function()  {
		// call the PHP script to get the data
		this.editableGrid.loadJSON("loaddatareturns.php?db_tablename=returns");
	};

	var returnsGrid = new DatabaseGrid('returns');

	$("#filter").keyup(function() {
      returnsGrid.editableGrid.filter( $(this).val());
    });

    e.preventDefault();
});