$(document).ready(function () {
    $.validator.addMethod(
        'StrongPassword',
        function (value) {
            return /(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/.test(value);
        },
        'Please Enter Strong Password'
    );
    $.validator.addMethod(
        'ValidPNo',
        function (value) {
            return /^([0-9]){10,15}$/.test(value);
        },
        'Please Enter Valid Phone Number'
    );
    // $.validator.addMethod(
    //     'Countryname',
    //     function (value) {
    //         return /^[A-Za-z]+$/.test(value);
    //     },
    //     'Please Enter Valid Country Name'
    // );
    $('#manualOrderForm').validate({
        rules: {
            bill_line1: {
                required: true,
                maxlength: 35,
            },
            bill_line2: {
                maxlength: 200,
            },
            bill_city: {
                required: true,
                maxlength: 200,
            },
            bill_state: {
                maxlength: 200,
            },
            bill_country: {
                required: true,
                // Countryname: true,
            },
            bill_postalCode: {
                required: true,
            },
            bill_telephone: {
                required: true,
                ValidPNo: true,
            },
            ship_line1: {
                required: true,
                maxlength: 35,
            },
            ship_line2: {
                maxlength: 200,
            },
            ship_city: {
                required: true,
                maxlength: 200,
            },
            ship_state: {
                maxlength: 200,
            },
            ship_country: {
                required: true,
                // Countryname: true,
            },
            ship_postalCode: {
                required: true,
            },
            ship_telephone: {
                required: true,
                ValidPNo: true,
            },
            id_bank: {
                required: true,
            },
            id_paypal: {
                required: true,
            },
        },
        highlight: function (element) {
            $(element).addClass('c1');
            $(element).removeClass('c2');
        },
        unhighlight: function (element) {
            $(element).addClass('c2');
        },
        invalidHandler: function (element) {
            var validator = $('#manualOrderForm').validate();
            $('#summary').text(validator.numberOfInvalids() + ' fields are invalid..');
        },
        messages: {
            email: {
                required: 'Email is mandatory',
                email: 'Invalid Email',
            },
            bill_city: {
                required: 'Bill City is mandatory',
                maxlength: 'Bill City Length Should be less than 200',
            },
            bill_state: {
                maxlength: 'Bill State Length Should be less than 200',
            },
            bill_country: {
                required: 'Bill Country is mandatory',
            },
            bill_postalCode: {
                required: 'Bill Postal Code is mandatory',
            },
            bill_telephone: {
                required: 'Bill Telephone is mandatory',
            },
            ship_line1: {
                required: 'Ship Line 1 is mandatory',
                maxlength: 'Ship line 1 Length Should be less than 35',
            },
            ship_line2: {
                maxlength: 'Ship line 2 Length Should be less than 200',
            },
            ship_city: {
                required: 'Ship City is mandatory',
                maxlength: 'Ship City Length Should be less than 200',
            },
            ship_state: {
                maxlength: 'Ship City Length Should be less than 200',
            },
            ship_country: {
                required: 'Ship Country is mandatory',
            },
            ship_postalCode: {
                required: 'Ship Postal Code is mandatory',
            },
            ship_telephone: {
                required: 'Ship Telephone is mandatory',
            },
            id_bank: {
                required: 'Bank ID is mandatory',
            },
            id_paypal: {
                required: 'Paypal ID is mandatory',
            },
        },
    });
});

$('body').on('submit', '#manualOrderForm', function (e) {
    e.preventDefault();
    var emailInput = $('#emailInput').val();
    userEmail = $('#userEmail').text();
    userCurrency = $('#userCurrency').text();
    userFname = $('#userFname').text();
    userLname = $('#userLname').text();
    userDG = $('#userDG').text();
    userBCountry = $('#userBCountry').text();
    userVat = $('#userVat').text();
    userJoinDate = $('#userJoinDate').text();
    grandtotal = $('#grand-total').text();
    // isbnsearch = $('#isbn_search').val();
    productLists = JSON.parse($('#productLists').val());
    if (emailInput == '') {
        toastr.error('Please Enter Email first', 'Error');
        return false;
    }
    function validateEmail($emailInput) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test($emailInput);
    }
    if (!validateEmail(emailInput)) {
        toastr.error('Please Enter Valid Email Address', 'Error');
        return false;
    }
    if (productLists.length == 0) {
        toastr.error('Please Select Atleast One Product', 'Error');
        return false;
    }
    $('#app-loader').removeClass('hide');
    var formData = new FormData(this);

    formData.append('emailInput', emailInput);
    formData.append('userEmail', userEmail);
    formData.append('userCompany', userCompany);
    formData.append('userFname', userFname);
    formData.append('userLname', userLname);
    formData.append('userDG', userDG);
    formData.append('userBCountry', userBCountry);
    formData.append('userVat', userVat);
    formData.append('userJoinDate', userJoinDate);
    formData.append('grandtotal', grandtotal);
    // Display the key/value pairs
    for (var pair of formData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }
    $.ajax({
        url: 'manual-order/',
        type: 'POST',
        data: formData,
        headers: {
            "Access-Control-Allow-Origin": "*"
        },
        processData: false,
        contentType: false,
        success: function (data) {
            $('#app-loader').addClass('hide');
            //return false;
            console.log(data);
            toastr.success('success added', 'Success');
            setTimeout(function () {
                location.reload();
            }, 1000);
        },
        error: function (error) {
            $('#app-loader').addClass('hide');
            console.log(error);
            var response = $.parseJSON(error.responseText);
            $.each(response.errors, function (key, val) {
                $('#' + key + '_error').text(val[0]);
            });
        },
    });
});
