$(document).ready(function () {
    $.validator.addMethod(
        'StrongPasswords',
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
    $('#createOrderForm').validate({
        rules: {
            firstName: {
                required: true,
                maxlength: 35,
            },
            lastName: {
                required: true,
                maxlength: 35,
            },
            email: {
                required: true,
                maxlength: 35,
            },
            currency: {
                required: true,
                maxlength: 35,
            },
            password: {
                required: true,
                StrongPasswords: true,
                minlength: 5,
            },
            passwordConfirmation: {
                required: true,
                minlength: 5,
                equalTo : password,
            },
            companyName: {
                required: true,
                maxlength: 35,
            },
            netsuiteId: {
                required: true,
                maxlength: 35,
            },
            vat: {
                required: true,
                maxlength: 35,
            },
            discountGroup: {
                required: true,
                maxlength: 35,
            },
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
        },
        highlight: function (element) {
            $(element).addClass('c1');
            $(element).removeClass('c2');
        },
        unhighlight: function (element) {
            $(element).addClass('c2');
        },
        invalidHandler: function (element) {
            var validator = $('#createOrderForm').validate();
            $('#summary').text(validator.numberOfInvalids() + ' fields are invalid..');
        },
        messages: {
            firstName: {
                required: 'First Name is mandatory',
                maxlength: 'First Name Length Should be less than 35',
            },
            lastName: {
                required: 'Last Name is mandatory',
                maxlength: 'Last Name Length Should be less than 35',
            },
            email: {
                required: 'Email is mandatory',
                maxlength: 'Email Length Should be less than 35',
            },
            currency: {
                required: 'Currency is mandatory',
            },
            password: {
                required: 'Password is mandatory',
                StrongPassword: true,
                maxlength: 'Password Length Should be greater than 5 words',
            },
            passwordConfirmation: {
                required: 'Password Confirmation is mandatory',
                maxlength: 'Password Confirmation Length Should be greater than 5 words',
                equalTo : "Password Confirmation doesn't match"
            },
            companyName: {
                required: 'Company Name is mandatory',
                maxlength: 'CompanyName 1 Length Should be less than 35',
            },
            netsuiteId: {
                required: 'Net Suite Id is mandatory',
                maxlength: 'Net Suite Id Length Should be less than 35',
            },
            vat: {
                required: 'Vat NO is mandatory',
                maxlength: 'Vat NO line 1 Length Should be less than 35',
            },
            discountGroup: {
                required: 'Discount Group is mandatory',
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
        },
    });

});

$('body').on('submit', '#createOrderForm', function (e) {
    e.preventDefault();
    //console.log(this.actions)
    $('#app-loader').removeClass('hide');
    var formData = new FormData(this);
    $.ajax({
        url: 'new-account/',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            $('#app-loader').addClass('hide');
            console.log(data);
            //return false;
            toastr.success('success added', 'Success');
            setTimeout(function () {
                location.reload();
            }, 1000);
        },
        error: function (error) {
            $('#app-loader').addClass('hide');
            console.log(error.responseJSON);
            toastr.error(error.responseJSON.message, 'Error');
        },
    });
});
