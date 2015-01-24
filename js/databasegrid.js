/**
 *  highlightRow and highlight are used to show a visual feedback. If the row has been successfully modified, it will be highlighted in green. Otherwise, in red
 */
function highlightRow(rowId, bgColor, after)
{
	var rowSelector = $("#" + rowId);
	rowSelector.css("background-color", bgColor);
	rowSelector.fadeTo("normal", 0.5, function() { 
		rowSelector.fadeTo("fast", 1, function() { 
			rowSelector.css("background-color", '');
		});
	});
}

function highlight(div_id, style) {
	highlightRow(div_id, style == "error" ? "#e5afaf" : style == "warning" ? "#ffcc00" : "#8dc70a");
}
        
/**
   updateCellValue calls the PHP script that will update the database. 
 */
function updateCellValue(editableGrid, rowIndex, columnIndex, oldValue, newValue, row, onResponse)
{   
	if (editableGrid.getColumnName(columnIndex) == 'devices') {
		alert('devices!');
	}

	$.ajax({
		url: 'update.php',
		type: 'POST',
		dataType: "html",
		data: {
			tablename : editableGrid.name,
			id: editableGrid.getRowId(rowIndex), 
			newvalue: editableGrid.getColumnType(columnIndex) == "boolean" ? (newValue ? 1 : 0) : newValue, 
			colname: editableGrid.getColumnName(columnIndex),
			coltype: editableGrid.getColumnType(columnIndex)			
		},
		success: function (response) 
		{ 
			// reset old value if failed then highlight row
			var success = onResponse ? onResponse(response) : (response == "ok" || !isNaN(parseInt(response))); // by default, a sucessfull reponse can be "ok" or a database id 
			if (!success) editableGrid.setValueAt(rowIndex, columnIndex, oldValue);
		    highlight(row.id, success ? "ok" : "error"); 
		},
		error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + errortext); },
		async: true
	});
   
}

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

        if (this.getColumnName(columnIndex) == 'devices') {

        	$.ajax({
			  	url: 'get_requested_devices.php',
			  	data: {

			  	},
			  	success: function(response) {
			  	}
			});

        	$('#myModalLabel').html("Edit devices for " + getValueAt(rowIndex, getColumnID('full_name')) + ", Ref #: " + getValueAt(rowIndex, getColumnID('reference_id')));
        	$('.modal-body').html("<ul id='edit_product_list' class='list-group'></ul>");
        	$('#edit_product_list').html();
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
        
EditableGrid.prototype._rendergrid = function(containerid, className, tableid) {
    with(this) {
        lastSelectedRowIndex = -1;
        _currentPageIndex = getCurrentPageIndex();
        if (typeof table != "undefined" && table != null) {
            var _data = dataUnfiltered == null ? data : dataUnfiltered;
            _renderHeaders();
            var rows = tBody.rows;
            var skipped = 0;
            var displayed = 0;
            var rowIndex = 0;
            for (var i = 0; i < rows.length; i++) {
                if (!_data[i].visible || (pageSize > 0 && displayed >= pageSize)) {
                    if (rows[i].style.display != "none") {
                        rows[i].style.display = "none";
                        rows[i].hidden_by_editablegrid = true
                    }
                } else {
                    if (skipped < pageSize * _currentPageIndex) {
                        skipped++;
                        if (rows[i].style.display != "none") {
                            rows[i].style.display = "none";
                            rows[i].hidden_by_editablegrid = true
                        }
                    } else {
                        displayed++;
                        var cols = rows[i].cells;
                        if (typeof rows[i].hidden_by_editablegrid != "undefined" && rows[i].hidden_by_editablegrid) {
                            rows[i].style.display = "";
                            rows[i].hidden_by_editablegrid = false
                        }
                        rows[i].rowId = getRowId(rowIndex);
                        rows[i].id = _getRowDOMId(rows[i].rowId);
                        for (var j = 0; j < cols.length && j < columns.length; j++) {
                            if (columns[j].renderable) {
                                columns[j].cellRenderer._render(rowIndex, j, cols[j], getValueAt(rowIndex, j))
                            }
                        }
                    }
                    rowIndex++
                }
            }
            table.editablegrid = this;
            if (doubleclick) {
                table.ondblclick = function(e) {
                    this.editablegrid.mouseClicked(e)
                }
            } else {
                table.onclick = function(e) {
                    this.editablegrid.mouseClicked(e)
                }
            }
        } else {
            if (!_$(containerid)) {
                return alert("Unable to get element [" + containerid + "]")
            }
            currentContainerid = containerid;
            currentClassName = className;
            currentTableid = tableid;
            var startRowIndex = 0;
            var endRowIndex = getRowCount();
            if (pageSize > 0) {
                startRowIndex = _currentPageIndex * pageSize;
                endRowIndex = Math.min(getRowCount(), startRowIndex + pageSize)
            }
            this.table = document.createElement("table");
            table.className = className || "editablegrid";
            if (typeof tableid != "undefined") {
                table.id = tableid
            }
            while (_$(containerid).hasChildNodes()) {
                _$(containerid).removeChild(_$(containerid).firstChild)
            }
            _$(containerid).appendChild(table);
            if (caption) {
                var captionElement = document.createElement("CAPTION");
                captionElement.innerHTML = this.caption;
                table.appendChild(captionElement)
            }
            this.tHead = document.createElement("THEAD");
            table.appendChild(tHead);
            var trHeader = tHead.insertRow(0);
            var columnCount = getColumnCount();
            for (var c = 0; c < columnCount; c++) {
                var headerCell = document.createElement("TH");
                var td = trHeader.appendChild(headerCell);
                columns[c].headerRenderer._render(-1, c, td, columns[c].label);
            }
            this.tBody = document.createElement("TBODY");
            table.appendChild(tBody);
            var insertRowIndex = 0;
            for (var i = startRowIndex; i < endRowIndex; i++) {
                var tr = tBody.insertRow(insertRowIndex++);
                tr.rowId = data[i]["id"];
                tr.id = this._getRowDOMId(data[i]["id"]);
                for (j = 0; j < columnCount; j++) {
                    var td = tr.insertCell(j);
                    columns[j].cellRenderer._render(i, j, td, getValueAt(i, j))
                }
            }
            _$(containerid).editablegrid = this;
            if (doubleclick) {
                _$(containerid).ondblclick = function(e) {
                    this.editablegrid.mouseClicked(e)
                }
            } else {
                _$(containerid).onclick = function(e) {
                    this.editablegrid.mouseClicked(e)
                }
            }
        }
        tableRendered(containerid, className, tableid)
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
    	a.setAttribute("data-target", "#myModal");
	}
    return this.render(a, typeof c == "string" && this.column.datatype != "html" ? htmlspecialchars(c, "ENT_NOQUOTES").replace(/\s\s/g, "&nbsp; ") : c)
};



  



