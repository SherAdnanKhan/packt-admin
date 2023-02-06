$('#searchEmailButton').on('click', function () {
    var emailInput = $('#emailInput').val();
    function validateEmail($emailInput) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test($emailInput);
    }
    if (emailInput == '') {
        toastr.error('Please Enter Email first', 'Error');
        return false;
    }
    if (!validateEmail(emailInput)) {
        toastr.error('Please Enter Valid Email Address', 'Error');
        return false;
    }
    const email = $('#emailInput').val();
    if (email != '') {
        $('#searchEmailButton').attr('disabled', true);
        $('#app-loader').removeClass('hide');
        $.ajax({
            type: 'GET',
            url: 'api/user-by-email/' + email,
            success: function (data) {
                $('#searchEmailButton').attr('disabled', false);
                $('#app-loader').addClass('hide');
                if (data.message) {
                    $('#errorResponse').removeClass('hide');
                    $('#userEmail').html('');
                    $('#userCompany').html('');
                    $('#userFname').html('');
                    $('#userLname').html('');
                    $('#userDG').html('');
                    $('#userBCountry').html('');
                    $('#userVat').html('');
                    $('#userCurrency').html('');
                    $('#userJoinDate').html('');
                    $('#shippingAddress').html('');
                    $('#billingAddress').html('');
                    $('#manualOrderForm')[0].reset();
                } else {
                    console.log('Search User Data:', data);
                    $('#errorResponse').addClass('hide');
                    $('#userEmail').html(data.username);
                    $('#userCompany').html(data.companyName);
                    $('#userFname').html(data.firstName);
                    $('#userLname').html(data.lastName);
                    $('#userDG').html(data.discountGroup);
                    //$('#userBCountry').html(data.username);
                    $('#userVat').html(data.vat);
                    //$('#userCurrency').html(data.username);
                    //$('#userJoinDate').html(data.username);
                    $('#shippingAddress').html('');
                    $('#billingAddress').html('');

                    if (data.addresses.length > 1) {
                        var count = 1;
                        data.addresses.forEach((address) => {
                            $('#shippingAddress').append(`
                            <div class="col-4 selectAddress">
                                <label class="option">
                                    <span class="option-control">
                                        <span class="radio">
                                            <input type="radio"
                                                value="${address.id}"
                                                name="shipping_address"
                                                data_city="${address.city}"
                                                data_state="${address.state}"
                                                data_country="${address.country}"
                                                data_line_1="${address.line1}"
                                                data_line_2="${address.line2}"
                                                data_postal_code="${address.postalCode}"
                                                data_telephone="${address.telephone}"
                                                ${address.defaultBilling != undefined ? 'checked' : ''}
                                            />
                                            <span></span>
                                        </span>
                                    </span>
                                    <span class="option-label">
                                        <span class="option-head">
                                            <span class="option-title">
                                                ${address.state}, ${address.state}
                                            </span>
                                        </span>
                                        <span class="option-body">
                                            ${address.line1} ${address.line1}, ${address.city}, ${address.state}, ${
                                address.country
                            }.
                                            ${address.postalCode}, ${address.telephone}
                                        </span>
                                    </span>
                                </label>
                            </div>
                            `);

                            $('#billingAddress').append(`
                            <div class="col-4 selectAddress">
                                <label class="option">
                                    <span class="option-control">
                                        <span class="radio">
                                            <input type="radio"
                                                value="${address.id}"
                                                name="billing_address"
                                                data_city="${address.city}"
                                                data_state="${address.state}"
                                                data_country="${address.country}"
                                                data_line_1="${address.line1}"
                                                data_line_2="${address.line2}"
                                                data_postal_code="${address.postalCode}"
                                                data_telephone="${address.telephone}"
                                                ${address.defaultBilling != undefined ? 'checked' : ''}
                                            />
                                            <span></span>
                                        </span>
                                    </span>
                                    <span class="option-label">
                                        <span class="option-head">
                                            <span class="option-title">
                                                ${address.state}, ${address.state}
                                            </span>
                                        </span>
                                        <span class="option-body">
                                            ${address.line1} ${address.line1}, ${address.city}, ${address.state}, ${
                                address.country
                            }.
                                            ${address.postalCode}, ${address.telephone}
                                        </span>
                                    </span>
                                </label>
                            </div>
                            `);
                            count++;
                            if (address.defaultBilling != undefined) {
                                $('input[name="bill_line1"]').val(address.line1);
                                $('input[name="bill_line2"]').val(address.line2);
                                
                                if(address.country.length>3){
                                    $(`#bill_country option[country=${address.country}]`).prop('selected', true);
                                }
                                else {
                                    $(`#bill_country option[alpha=${address.country}]`).prop('selected', true);
                                }
                                country_id = $('#bill_country').val();
                                getStateByCountryId(country_id, address.state, address.city, 'bill');
                                $('input[name="bill_postalCode"]').val(address.postalCode);
                                $('input[name="bill_telephone"]').val(address.telephone);
                            }
                            if (address.defaultShipping != undefined) {
                                $('input[name="ship_line1"]').val(address.line1);
                                $('input[name="ship_line2"]').val(address.line2);
                                if(address.country.length>3){
                                    $(`#ship_country option[country=${address.country}]`).prop('selected', true);
                                }
                                else {
                                    $(`#ship_country option[alpha=${address.country}]`).prop('selected', true);
                                }
                                country_id = $('#ship_country').val();
                                getStateByCountryId(country_id, address.state, address.city, 'ship');
                                $('input[name="ship_postalCode"]').val(address.postalCode);
                                $('input[name="ship_telephone"]').val(address.telephone);
                            }
                        });
                    }
                }
            },
        });
    }
});

$(document).on('change', 'input[name="billing_address"]', function () {
    const getShippingAddress = $(this).is(':checked');
    const getAllShipAdr = {
        city: $(this).attr('data_city'),
        state: $(this).attr('data_state'),
        country: $(this).attr('data_country'),
        line1: $(this).attr('data_line_1'),
        line2: $(this).attr('data_line_2'),
        postalCode: $(this).attr('data_postal_code'),
        telephone: $(this).attr('data_telephone'),
    };
    if (getShippingAddress) {
        console.log(getAllShipAdr);
        $('input[name="bill_line1"]').val(getAllShipAdr.line1);
        $('input[name="bill_line2"]').val(getAllShipAdr.line2);
        $(`#bill_country option[country=${getAllShipAdr.country}]`).prop('selected', true);
        //$('input[name="bill_country"]').val(getAllShipAdr.country);
        country_id = $('#bill_country').val();
        getStateByCountryId(country_id, getAllShipAdr.state, getAllShipAdr.city, 'bill');
        //$('input[name="bill_city"]').val(getAllShipAdr.city);
        //$('input[name="bill_state"]').val(getAllShipAdr.state);
        $('input[name="bill_postalCode"]').val(getAllShipAdr.postalCode);
        $('input[name="bill_telephone"]').val(getAllShipAdr.telephone);
    }
});

$(document).on('change', 'input[name="shipping_address"]', function () {
    const getShippingAddress = $(this).is(':checked');
    const getAllShipAdr = {
        city: $(this).attr('data_city'),
        state: $(this).attr('data_state'),
        country: $(this).attr('data_country'),
        line1: $(this).attr('data_line_1'),
        line2: $(this).attr('data_line_2'),
        postalCode: $(this).attr('data_postal_code'),
        telephone: $(this).attr('data_telephone'),
    };
    if (getShippingAddress) {
        console.log(getAllShipAdr);
        $('input[name="ship_line1"]').val(getAllShipAdr.line1);
        $('input[name="ship_line2"]').val(getAllShipAdr.line2);
        $(`#ship_country option[country=${getAllShipAdr.country}]`).prop('selected', true);
        //$('input[name="ship_country"]').val(getAllShipAdr.country);
        country_id = $('#ship_country').val();
        getStateByCountryId(country_id, getAllShipAdr.state, getAllShipAdr.city, 'ship');
        //$('input[name="ship_city"]').val(getAllShipAdr.city);
        //$('input[name="ship_state"]').val(getAllShipAdr.state);
        $('input[name="ship_postalCode"]').val(getAllShipAdr.postalCode);
        $('input[name="ship_telephone"]').val(getAllShipAdr.telephone);
    }
});

$(document).on('change', 'input[name="billAsShip"]', function () {
    var elems = document.querySelectorAll('.shipVisible');
    if (this.checked) {
        //console.log($('input[name="billAsShip"]').val());
        //document.getElementsByClassName()
        [].forEach.call(elems, function (el) {
            el.classList.add('hide');
        });
    } else {
        console.log('its unchecked');
        [].forEach.call(elems, function (el) {
            el.classList.remove('hide');
        });
    }
});

function getStateByCountryId(country_id, state, city, type) {
    $.ajax({
        url: `get-states/${country_id}`,
        type: 'GET',
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.success == true) {
                console.log(response.data - length);
                if (response.data - length <= 0) {
                    $(`#${type}_state`).prop('disabled', true);
                    $.ajax({
                        url: `get-cities-by-country/${country_id}`,
                        type: 'GET',
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            console.log(response);
                            cities = response.data;
                            $(`#${type}_city`)
                                .empty()
                                .append(
                                    $('<option>', {
                                        text: 'Select City',
                                        disabled  : 'disabled',
                                        city: '',
                                    })
                                );
                            cities.forEach((city) => {
                                $(`#${type}_city`).append(
                                    $('<option>', {
                                        value: city.id,
                                        text: city.name.en,
                                        city: city.name.en,
                                    })
                                );
                            });
                            $(`#${type}_city option[city=${city}]`).prop('selected', true);
                            return;
                        },
                        error: function (error) {},
                    });
                } else {
                    $(`#${type}_state`).prop('disabled', false);
                    $(`#${type}_state`)
                        .empty()
                        .append(
                            $('<option>', {
                                text: 'Select State',
                                disabled  : 'disabled',
                                city: '',
                            })
                        );
                    states = response.data;

                    states.forEach((state) => {
                        $(`#${type}_state`).append(
                            $('<option>', {
                                value: state.id,
                                text: state.name.en,
                                state: state.name.en,
                            })
                        );
                    });
                    $(`#${type}_state option[state=${state}]`).prop('selected', true);

                    state_id = $(`#${type}_state`).val();
                    getCountryByState(state_id, city, type);
                    return;
                }
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}

function getCountryByState(state_id, city, type) {
    $.ajax({
        url: `get-cities-by-state/${state_id}`,
        type: 'GET',
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            if (response.success == true) {
                response.data;
                if (response.data - length == 0) {
                    $(`#${type}_city`).prop('disabled', true);
                } else {
                    $(`#${type}_city`).prop('disabled', false);
                    $(`#${type}_city`)
                        .empty()
                        .append(
                            $('<option>', {
                                text: 'Select City',
                                disabled  : 'disabled',
                                city: '',
                            })
                        );
                    cities = response.data;
                    cities.forEach((city) => {
                        console.log(city.name.en);
                        $(`#${type}_city`).append(
                            $('<option>', {
                                value: city.id,
                                text: city.name.en,
                                city: city.name.en,
                            })
                        );
                    });
                    $(`#${type}_city option[city=${city}]`).prop('selected', true);
                    return;
                }
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}
