$(function() {

    //generate form based on selected table
    console.log('localStorage.table: ', localStorage.table);

    if (localStorage.table === "samples") {

        $('#request_title').text('Sample Request');
        $('#purpose_row').show();
        $('#rma_id_row').hide();

    } else if (localStorage.table === "replacements") {

        $('#request_title').text('Replacement Request');

    } else if (localStorage.table === "returns") {

        $('#request_title').text('Return Request');

    } else {

        console.log('table does not exist');

    }

    $.ajax({
        type: 'GET',
        url: 'print_form.php',
        data: {
            request_id: localStorage.request_id,
            table: localStorage.table
        },
        success: function(response) {

            console.log(response);
            var form_data = JSON.parse(response);
            var requested_info = form_data.requested_info;
            var requested_devices = form_data.requested_devices;
            var qty, name;

            // console.log(form_data);
            // console.log(form_data.requested_devices);

            $('#business_name').html(requested_info.business_name);
            $('#full_name').html(requested_info.full_name);
            $('#address').html(requested_info.address);
            $('#city').html(requested_info.city);
            $('#state').html(requested_info.state);
            $('#zip_postal').html(requested_info.zip_postal);
            $('#country').html(requested_info.country);
            $('#phone_number').html(requested_info.phone_number);
            $('#email').html(requested_info.email);
            $('#reference_id').html(requested_info.reference_id);
            $('#rma_id').html(requested_info.rma_id);
            $('#reason_body').html(requested_info.reason);
            $('#special_req_body').html(requested_info.special_req);

            if (localStorage.table === "samples") {

                $('#purpose').html(form_data.purpose.purpose);

                if (form_data.purpose.purpose === 'Internal Use') {

                    $('#customer_panel_header').text('To be received by: ');

                }
            }

            for (var i = 0; i < requested_devices.length; i++) {
                qty = requested_devices[i]['qty'];
                name = requested_devices[i]['name'];

                $('#products').append('<tr><td>' + qty + '</td><td>' + name + '</td></tr>');
            }

            //make the reasons panel height match the products panel height
            // $('#reason_panel').height($('#products_panel').height());
        },
        error: function() {
            alert('AJAX failed for print_form');
        }
    });

});
