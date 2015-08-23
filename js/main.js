var replacementsGrid = new DatabaseGrid('replacements');

window.onload = function() {

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

    $("#filter").keyup(function() {
        replacementsGrid.editableGrid.filter($(this).val());
    });

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

            if (!$.isEmptyObject(productList)) {
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

        console.log(productList);

        e.preventDefault();
    });

    //show appropriate inputs based on what formType is selected
    $('input[name=formType]').on('click', function() {
        var formType = $('input:checked').val();

        if (formType === "Sample" || formType === "Replacement") {

            $('#refund_amount_group').hide();

            if (formType === "Sample") {

                $('#sample_purpose_group').show();

                if ($('#purpose').text() === "Customer/Media") {

                    $('#business_name_group').show();

                } else {

                    $('#business_name_group').hide();

                }

            } else {

                $('#sample_purpose_group').hide();
                $('#business_name_group').hide();

            }

        } else {

            $('#refund_amount_group').show();
            $('#business_name_group').hide();

        }

    });

    $('#sample_purpose_group').on('click', 'a', function(e) {

        if ($(this).text() === "Customer/Media") {

            $('#business_name_group').show();

        } else {

            $('#business_name_group').hide();

        }

    });


    $('#earlyShip_btn').on('click', function() {

        if ($(this).prop('checked') === true) {
            $('#tracking_number_group').show();
        } else {
            $('#tracking_number_group').hide();
        }
    });

    //AJAX Post form data to new_request.php
    $('#ajaxform').submit(function(e) {

        $.ajax({
            type: 'POST',
            url: 'new_request.php',
            data: {
                formType: 'Replacement',
                // earlyShip: $('input[name=earlyShip]:checked').val(),
                // purpose: $('#purpose').text().trim(),
                // business_name: $('input[name=business_name]').val().trim(),
                full_name: $('input[name=full_name]').val().trim(),
                email: $('input[name=email]').val().trim(),
                address: $('input[name=address]').val().trim(),
                city: $('input[name=city]').val().trim(),
                state: $('input[name=state]').val().trim(),
                zip_postal: $('input[name=zip_postal]').val().trim(),
                country: $('#country').text().trim(),
                phone_number: $('input[name=phone_number]').val().trim(),
                reason: $('textarea[name=reason]').val().trim(),
                ticket_id: $('input[name=ticket_id]').val().trim(),
                // special_req: $('textarea[name=special_req]').val().trim(),
                // refund_amount: $('input[name=refund_amount]').val().trim(),
                // tracking_number: $('input[name=tracking_number]').val().trim(),
                // shipping_carrier: $('#shipping_carrier').text().trim(),
                devices: JSON.stringify(productList)
            },
            success: function(data) {
                console.log(data);
                alert(data);
                //alert('successfully added, maybe popup a new window to print page');
                location.reload();
            },
            error: function(xhr, textStatus, error) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            }
        });

        e.preventDefault();
    });
};

//fill up products dropdown menu from DB
$.get('get_devices.php', function(data) {
    var devices = $.parseJSON(data);
    var product_group_list = "";

    $('#product_type').text(devices[0]).append('<span class="caret"></span>');

    for (var index in devices) {
        if (devices.hasOwnProperty(index)) {
            product_group_list += '<li><a href="#">' + devices[index] + '</a></li>';
        }
    }

    $('#product_group_list').html(product_group_list);
});

//fill up country dropdown menu from DB
$.get('get_countries.php', function(data) {
    var countries = $.parseJSON(data);
    var country_list = "";

    $('#country').text(countries[0]).append('<span class="caret"></span>');

    for (var index in countries) {
        if (countries.hasOwnProperty(index)) {
            country_list += '<li><a href="#">' + countries[index] + '</a></li>';
        }
    }

    $('#country_list').html(country_list);
});

//fill up purpose dropdown menu from DB
$.get('get_purposes.php', function(data) {
    var purposes = $.parseJSON(data);
    var purpose_list = "";

    $('#purpose').text(purposes[0]).append('<span class="caret"></span>');

    for (var index in purposes) {
        if (purposes.hasOwnProperty(index)) {
            purpose_list += '<li><a href="#">' + purposes[index] + '</a></li>';
        }
    }

    $('#purpose_list').html(purpose_list);
});

//change dropdown menu button to selected for the following buttons
changeDropdownValue("#btn_group_shipping_carrier");
changeDropdownValue("#btn_group_country");
changeDropdownValue("#btn_group_product_type");
changeDropdownValue("#btn_group_purpose");
