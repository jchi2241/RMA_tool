$.getScript("js/helpers.js", function(){
	console.log('helpers.js successfully loaded in databasegrid.js');
});

DatabaseGrid.prototype.initializeGrid = function(grid) {
	grid.renderGrid("tablecontent", "testgrid");
};   

function DatabaseGrid(table) 
{
	var self = this;

	this.editableGrid = new EditableGrid(table, {
		enableSort: true,
	    // define the number of row visible by page
      	pageSize: 10,
      // Once the table is displayed, we update the paginator state
        tableRendered:  function() {  updatePaginator(this); },
   	    tableLoaded: function() { self.initializeGrid(this); },
		modelChanged: function(rowIndex, columnIndex, oldValue, newValue, row) {
   	    	updateCellValue(this, rowIndex, columnIndex, oldValue, newValue, row);
       	}
 	});
	this.fetchGrid(table); 
}

DatabaseGrid.prototype.deleteRow = function(id) 
{
  var self = this;

  if ( confirm('Are you sure you want to delete ' + id )  ) {

        $.ajax({
		url: 'delete.php',
		type: 'POST',
		dataType: "html",
		data: {
			tablename : self.editableGrid.name,
			id: id 
		},
		success: function (response) 
		{ 
			if (response == "ok" ) {
		        self.editableGrid.removeRow(id);
			}
		},
		error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + errortext); },
		async: true
	});
  }
};

DatabaseGrid.prototype.fetchGrid = function(table)  {
	// call a PHP script to get the data
	this.editableGrid.loadJSON("loaddata" + table + ".php?db_tablename=" + table);
};

function updatePaginator(grid, divId)
{
    divId = divId || "paginator";
	var paginator = $("#" + divId).empty();
	var nbPages = grid.getPageCount();

	// get interval
	var interval = grid.getSlidingPageInterval(20);
	if (interval == null) return;
	
	// get pages in interval (with links except for the current page)
	var pages = grid.getPagesInInterval(interval, function(pageIndex, isCurrent) {
		if (isCurrent) return "<span id='currentpageindex'>" + (pageIndex + 1)  +"</span>";
		return $("<a>").css("cursor", "pointer").html(pageIndex + 1).click(function(event) { grid.setPageIndex(parseInt($(this).html()) - 1); });
	});
		
	// "first" link
	var link = $("<a class='nobg'>").html("<i class='fa fa-fast-backward'></i>");
	if (!grid.canGoBack()) link.css({ opacity : 0.4, filter: "alpha(opacity=40)" });
	else link.css("cursor", "pointer").click(function(event) { grid.firstPage(); });
	paginator.append(link);

	// "prev" link
	link = $("<a class='nobg'>").html("<i class='fa fa-backward'></i>");
	if (!grid.canGoBack()) link.css({ opacity : 0.4, filter: "alpha(opacity=40)" });
	else link.css("cursor", "pointer").click(function(event) { grid.prevPage(); });
	paginator.append(link);

	// pages
	for (p = 0; p < pages.length; p++) paginator.append(pages[p]).append(" ");
	
	// "next" link
	link = $("<a class='nobg'>").html("<i class='fa fa-forward'>");
	if (!grid.canGoForward()) link.css({ opacity : 0.4, filter: "alpha(opacity=40)" });
	else link.css("cursor", "pointer").click(function(event) { grid.nextPage(); });
	paginator.append(link);

	// "last" link
	link = $("<a class='nobg'>").html("<i class='fa fa-fast-forward'>");
	if (!grid.canGoForward()) link.css({ opacity : 0.4, filter: "alpha(opacity=40)" });
	else link.css("cursor", "pointer").click(function(event) { grid.lastPage(); });
	paginator.append(link);
}; 

//EditableGrid prototype modifications for this project

EditableGrid.prototype.mouseClicked = function(e) {

    e = e || window.event;

    with(this) {
        var target = e.target || e.srcElement;
        while (target) {
            if (target.tagName == "A" || target.tagName == "TD" || target.tagName == "TH") {
                break
            } else {
                target = target.parentNode
            }
        }
        if (!target || !target.parentNode || !target.parentNode.parentNode || (target.parentNode.parentNode.tagName != "TBODY" && target.parentNode.parentNode.tagName != "THEAD") || target.isEditing) {
            return
        }
        if (target.tagName == "A") {
            return
        }
        var rowIndex = getRowIndex(target.parentNode);
        var columnIndex = target.cellIndex;
        var column = columns[columnIndex];

        var getColumnID = function(columnName) {
        	for (var i in columns) {
        		if (columns[i].name == columnName) {
        			return columns[i].columnIndex;
        		}
        	}
        }

        //if column 'Devices' is clicked, get devices for specific sample request from DB
        if (this.getColumnName(columnIndex) == 'devices') {

        	var self = this;
        	var editProductList;
        	console.log('after init: ', editProductList);

        	$('#edit_product_list').empty();

        	//fill up edit_product_type with devices from devices table in DB
        	$.get('get_devices.php', function (data) {
				var devices = $.parseJSON(data);
			
				$('#edit_product_type').text(devices[0]);

				var edit_product_group_list = '<li><a href="#" style="display:none;">' + devices[0] + '</a></li>';

				for (var index in devices) {
					if (index !== '0' && devices.hasOwnProperty(index)) {
						edit_product_group_list += '<li><a href="#">' + devices[index] + '</a></li>';
						$('#edit_product_group_list').html(edit_product_group_list);
					}
				}
			});

        	$.ajax({
        		type: 'POST',
			  	url: 'get_requested_devices.php',
			  	data: {
			  		table: this.name,
			  		request_id: self.getRowId(rowIndex)
			  	},
			  	success: function(response) {
			  		editProductList = JSON.parse(response);
			  		console.log('from DB: ', editProductList);

			  		for (var product in editProductList) {
			  			if (editProductList.hasOwnProperty(product)) {
			  				$('#edit_product_list').append('<li class="list-group-item"><span class="badge">' 
														+ editProductList[product].qty + '</span>' 
														+ editProductList[product].name 
														+ '<button type="button" class="close" aria-hidden="true">&times;</button></li>');
			  			}
					}

					//adding products into editProductList
					$('#edit_product_add_btn').on('click', function(e) {

						var qty = $('#edit_product_qty').val().trim();
						var productType = $('#edit_product_type').text().trim();
						var in_editProductList = false;

						if (qty !== '' && parseInt(qty) !== 0) {

							for (var product in editProductList) {
								if (editProductList.hasOwnProperty(product)) {
									if (editProductList[product].name === productType) {
										editProductList[product].qty = (parseInt(editProductList[product].qty) + parseInt(qty)).toString() ;
										console.log('product name, qty: ', editProductList[product].name, editProductList[product].qty);
										$('#edit_product_list li:contains(' + productType + ')').find('span').html(editProductList[product].qty);

										in_editProductList = true;
										break;
									}
								}
							}
							
							if (in_editProductList === false) {
								if (editProductList.hasOwnProperty(product)) {

									//add into DOM
									$('#edit_product_list').append('<li class="list-group-item"><span class="badge">' 
														+ qty + '</span>' 
														+ productType 
														+ '<button type="button" class="close" aria-hidden="true">&times;</button></li>');

									in_editProductList = true;
								}

								//add into object
								var new_product_obj = { name: productType, qty: qty };
								editProductList[Object.size(editProductList)] = new_product_obj;
							}
							
						} else {
							alert('Enter a number greater than 0');
						}

						console.log('editProductList after adding product: ', editProductList);
						e.preventDefault();
					});

					//removing products from editProductList
					$('#edit_product_list').on('click', '.close', function(e) {

						var productToRemove = $(this).parent().text().replace(/[0-9]/g, '').slice(0, -1);
						console.log('productToRemove: ' + productToRemove);

						//remove from object
						for (var product in editProductList) {
							if (editProductList.hasOwnProperty(product)) {
								if (editProductList[product].name == productToRemove) {
									delete editProductList[product];
								}
							}
						}

						//remove from DOM
						$(this).parent().remove();

						console.log(editProductList);

						e.preventDefault();
					});
				}
			});

			$('#editDevicesModal *').off();

			$('#editDevicesModal_Save').on('click', function (e) {

				$.ajax({
					type: 'POST',
					url: 'save_edited_devices.php',
					data: JSON.stringify(editProductList),
					success: function (response) {
						console.log(response);
					}
				});

				e.preventDefault();
			});

        	$('#editDevicesModal_Label').html("Devices for " 	+ getValueAt(rowIndex, getColumnID('full_name')) 
        											+ "<h5>(" + getValueAt(rowIndex, getColumnID('reference_id')) + ")</h5>");
        }

        if (column) {
            if (rowIndex > -1 && rowIndex != lastSelectedRowIndex) {
                rowSelected(lastSelectedRowIndex, rowIndex);
                lastSelectedRowIndex = rowIndex;
            }
            if (!column.editable) {
                readonlyWarning(column)
            } else {
                if (rowIndex < 0) {
                    if (column.headerEditor && isHeaderEditable(rowIndex, columnIndex)) {
                        column.headerEditor.edit(rowIndex, columnIndex, target, column.label)
                    }
                } else {
                    if (column.cellEditor && isEditable(rowIndex, columnIndex)) {
                        column.cellEditor.edit(rowIndex, columnIndex, target, getValueAt(rowIndex, columnIndex));
                    }
                }
            }
        }
    }
};
   
CellRenderer.prototype._render = function(d, b, a, c) {
    a.rowIndex = d;
    a.columnIndex = b;
    while (a.hasChildNodes()) {
        a.removeChild(a.firstChild)
    }
    a.isEditing = false;
    if (this.column.isNumerical()) {
        EditableGrid.prototype.addClassName(a, "number")
    }
    if (this.column.datatype == "boolean") {
        EditableGrid.prototype.addClassName(a, "boolean")
    }
    if (this.column.name == 'devices') {
    	a.setAttribute("data-toggle", "modal");
    	a.setAttribute("data-target", "#editDevicesModal");
	}
    return this.render(a, typeof c == "string" && this.column.datatype != "html" ? htmlspecialchars(c, "ENT_NOQUOTES").replace(/\s\s/g, "&nbsp; ") : c)
};



  



