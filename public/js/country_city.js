$(document).on('change', '#bill_country', function () {
    let country_id = this.value;
    if (country_id == '') {
        return false;
    }
    console.log('change in country', this.value);
    $('#bill_city')
        .empty()
        .append(
            $('<option>', {
                text: 'Select City',
                disabled  : 'disabled',
                city: '',
            })
        );
    $('#category_type').css('display', 'block');
    $('#bill_state')
        .empty()
        .append(
            $('<option>', {
                text: 'Select State',
                disabled : 'disabled',
                state: '',
            })
        );
    getStateByCountryId(country_id, 'bill');
});
$(document).on('change', '#bill_state', function () {
    let state_id = this.value;
    if (state_id == '' || state_id == 'Select State') {
        return false;
    }
    console.log('change in state', this.value);
    $('#bill_city')
        .empty()
        .append(
            $('<option>', {
                text: 'Select City',
                disabled : 'disabled',
                city: '',
            })
        );
    getCountryByState(state_id, 'bill');
});

$(document).on('change', '#category_type', function () {
    let category_type = this.value;
    let bill_country = $('#bill_country').val();
    if (bill_country == '') {
        toastr.error('Please Select Country First', 'Notice');
        return false;
    }
    let bill_state = $('#bill_state').val();
    let bill_city = $('#bill_city').val();
    if (bill_city == '') {
        toastr.error('Please Select City', 'Notice');
        return false;
    }
    $('#app-loader').removeClass('hide');
    $.ajax({
        url: `get-taxes/`,
        data: {
            category_type: category_type,
            bill_country: bill_country,
            bill_state: bill_state,
            bill_city: bill_city,
        },
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            $('#app-loader').addClass('hide');
            let taxval = $('#tax').val();
            taxval = +response.amount + +taxval;
            $('#tax').val(taxval);
        },
        error: function (error) {
            console.log(error);
        },
    });
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
});

$(document).on('change', '#ship_country', function () {
    let country_id = this.value;
    if (country_id == '') {
        return false;
    }
    console.log('change in country', this.value);
    $('#ship_city')
        .empty()
        .append(
            $('<option>', {
                text: 'Select City',
                disabled : 'disabled',
                city: '',
            })
        );
    $('#ship_state')
        .empty()
        .append(
            $('<option>', {
                text: 'Select State',
                disabled : 'disabled',
                state: '',
            })
        );
    getStateByCountryId(country_id, 'ship');
});
$(document).on('change', '#ship_state', function () {
    let state_id = this.value;
    if (state_id == '' || state_id == 'Select State') {
        return false;
    }
    console.log('change in state', this.value);
    $('#bill_city')
        .empty()
        .append(
            $('<option>', {
                text: 'Select City',
                disabled : 'disabled',
                city: '',
            })
        );

    getCountryByState(state_id, 'ship');
});

function getStateByCountryId(country_id, type) {
    $('#app-loader').removeClass('hide');
    $.ajax({
        url: `get-states/${country_id}`,
        type: 'GET',
        processData: false,
        contentType: false,
        success: function (response) {
            $('#app-loader').addClass('hide');
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
                            cities.forEach((city) => {
                                $(`#${type}_city`).append(
                                    $('<option>', {
                                        value: city.id,
                                        text: city.name.en,
                                        city: city.name.en,
                                    })
                                );
                            });
                        },
                        error: function (error) {},
                    });
                } else {
                    $(`#${type}_state`).prop('disabled', false);

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
                }
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}

function getCountryByState(state_id, type) {
    $('#app-loader').removeClass('hide');
    $.ajax({
        url: `get-cities-by-state/${state_id}`,
        type: 'GET',
        processData: false,
        contentType: false,
        success: function (response) {
            $('#app-loader').addClass('hide');
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
                                disabled : 'disabled',
                                city: '',
                            })
                        );
                    cities = response.data;
                    cities.forEach((city) => {
                        $(`#${type}_city`).append(
                            $('<option>', {
                                value: city.id,
                                text: city.name.en,
                                city: city.name.en,
                            })
                        );
                    });
                }
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}
