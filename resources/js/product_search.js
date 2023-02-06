const { round } = require('lodash');
var productsList = [];
var subtotal = 0.0;
let prices = [];
var i = 0;
(function () {
    const client = algoliasearch(process.env.MIX_ALGOLIA_APP_ID, process.env.MIX_ALGOLIA_KEY);
    const index = client.initIndex(process.env.MIX_ALGOLIA_PRODUCTS_INDEX);

    autocomplete(
        '#isbn_search',
        { hint: false },
        {
            source: autocomplete.sources.hits(index, { hitsPerPage: 10 }),
            //value to be displayed in input control after user's suggestion selection
            displayKey: 'title',
            //hash of templates used when rendering dataset
            templates: {
                //'suggestion' templating function used to render a single suggestion
                suggestion: function (suggestion) {
                    const markup = `
                        <div class="algolia-result">
                            <span id="selected-product">
                                ${suggestion.title}
                            </span>
                        </div>
                    `;
                    return markup;
                },

                empty: function (result) {
                    const markup = `
                        <div class="algolia-result">
                            <span>
                                Sorry, we did not find any results for ${result.query}.
                            </span>
                        </div>
                    `;
                    return markup;
                },
            },
        }
    ).on('autocomplete:selected', function (event, suggestion, dataset) {
        $('#isbn_search').val('');
        $('#app-loader').removeClass('hide');
        getSearchPrice(suggestion.isbn13, suggestion.title);
    });

    $('.products-table').on('input', '.products-table__input--num', function () {});

    $('.products-table').on('click', '.btn--remove-item', function () {
        $(this).parent().parent().remove();
        let product_isbn = $(this).attr('isbn');
        productsList = productsList.filter((product) => product.isbn !== product_isbn);
        updateSubtotal(productsList);
    });

    $(document).on('change', '.product_type', function () {
        let product_isbn = $(this).attr('isbn');
        productsList = productsList.map((product) => {
            if (product.isbn == product_isbn) {
                var currencytype = $('#Currency').val();
                let new_price = product.prices[this.value][currencytype];
                var total_price = round(parseFloat(new_price) * parseFloat(product.quantity), 2);
                $(`#${product.isbn}_price`).html(total_price);
                return {
                    ...product,
                    price: new_price,
                    total_price: total_price,
                    selected_product_type: this.value,
                };
            }
            return product;
        });
        //console.log(productsList);
        updateStotal(productsList);
    });

    $(document).on('change', '#Currency', function () {
        let currency = this.value;

        productsList = productsList.map((product) => {
            let new_price = product.prices[product.selected_product_type][currency];
            var total_price = round(parseFloat(new_price) * parseFloat(product.quantity), 2);
            $(`#${product.isbn}_price`).html(total_price);
            return {
                ...product,
                price: new_price,
                total_price: total_price,
            };
        });
        updateStotal(productsList);
    });
    $(document).on('change', '.quantity-product', function () {
        var currencytype = $('#Currency').val();
        //console.log(productsList);
        let product_isbn = $(this).attr('isbn');
        productsList = productsList.map((product) => {
            if (product.isbn == product_isbn) {
                var pro_type = $(`#${product.isbn}_product_type`).val();
                var total_price = round(parseFloat(product.prices[pro_type][currencytype]) * parseFloat(this.value), 2);
                if (isNaN(total_price)) {
                    total_price = 0;
                }
                $(`#${product.isbn}_price`).html(total_price);
                return {
                    ...product,
                    product_type: pro_type,
                    quantity: this.value,
                    total_price: total_price,
                };
            }
            return product;
        });
        updateStotal(productsList);
        // updateDiscount();
        updateGrandTotal();
    });

    $(document).on('change', '#payment_method', function () {
        ////console.log(this.value);
        if (this.value == 'bank') {
            $('#bank').removeClass('hide');
            $('#paypal').addClass('hide');
        } else if (this.value == 'paypal') {
            $('#paypal').removeClass('hide');
            $('#bank').addClass('hide');
        } else {
            $('#bank').addClass('hide');
            $('#paypal').addClass('hide');
        }
    });

    $(document).on('change', '#discount, #shipping, #tax', function () {
        updateGrandTotal();
    });
})();
function updateSubtotal(products) {
    //console.log(products);
    var currencytype = $('#Currency').val();
    subtotal = 0;
    $('#isbn_search').val('');
    products.forEach((product) => {
        subtotal += round(parseFloat(product.prices[product.product_type][currencytype] * product.quantity), 2);
    });
    $('#subtotal').html(round(subtotal, 2));

    //updateDiscount();
    updateGrandTotal();
}

function updateStotal(products) {
    ////console.log(products);
    var currencytype = $('#Currency').val();
    subtotal = 0;

    // //console.log(products);
    $('#isbn_search').val('');
    products.forEach((product) => {
        subtotal += round(
            parseFloat(product.prices[product.selected_product_type][currencytype] * product.quantity),
            2
        );
    });
    //  //console.log(subtotal);
    $('#subtotal').html(round(subtotal, 2));

    //updateDiscount();
    updateGrandTotal();
}

function updateGrandTotal() {
    var grandTotal = [];
    let discount = $('#discount').val();
    let shipping = $('#shipping').val();
    //let tax = $('#tax').val();
    $('#productLists').val(JSON.stringify(productsList));
    grandTotal = parseFloat(subtotal) - parseFloat(discount) + parseFloat(shipping);
    //grandTotal = parseFloat(subtotal) - parseFloat(discount) + parseFloat(shipping) + parseFloat(tax);
    $('#grand-total').html(round(grandTotal, 2));
}

function updateDiscount() {
    total_discount = 0;
    let userDiscountGroup = $('#userDG').html();

    if (userDiscountGroup != '') {
        // //console.log(userDiscountGroup);
        if (subtotal != 0) {
            total_discount = parseFloat(subtotal) * (parseFloat(userDiscountGroup) / 100);
        }
        //  //console.log('totalDiscount:', total_discount);
        $('#discount').val(round(total_discount, 2));
    }
}

function avaibilityCheck(
    product_isbn,
    product_title,
    product_type = 'ebook',
    product_quantity = '1',
    length = 0,
    response
) {
    let price = 0;
    let prod_price,
        searched_product,
        availability,
        totalprice = [];
    prod_price = response;

    $.ajax({
        url: 'api/product-availability/' + product_isbn,
        async: false,
        type: 'GET',
        success: function (response) {
            searched_product = response;
            //console.log(searched_product);
            $('#app-loader').addClass('hide');
            prices = prod_price;
            let filteredProduct;
            availability = searched_product.available;
            if (prices.length != 0 || prices != undefined || prices.data.length != 0) {
                var currencytype = $('#Currency').val();
                price = prices[product_type][currencytype];
                $(`#${searched_product.isbn13}_price`).html(price);
                subtotal += parseFloat(price);
                var product = {
                    title: searched_product['title'],
                    product_type: product_type,
                    isbn: searched_product.isbn13,
                    price: price,
                    prices: prices,
                    quantity: product_quantity,
                    total_price: parseFloat(price) * parseFloat(product_quantity),
                    selected_product_type: product_type,
                    available: availability,
                };
                filteredProduct = productsList.filter((product) => product.isbn == searched_product.isbn13);
                // //console.log(filteredProduct);
            }
            if (filteredProduct.length == 0) {
                productsList.push(product);
                // //console.log(productsList);
                updateSubtotal(productsList);
                var currencytype = $('#Currency').val();

                totalprice = prices[product_type][currencytype] * product_quantity;
                const markup = `
                <tr id="${searched_product.isbn13}_tr">
                    <td>${searched_product.title}</td>
                    <td>
                        <select name="product_type" class="product_type" id="${
                            searched_product.isbn13
                        }_product_type" isbn="${searched_product.isbn13}">
                            <option value="ebook" ${product_type == 'ebook' ? 'selected' : ''}> Ebook </option>
                            <option value="print" ${product_type == 'print' ? 'selected' : ''}> Print </option>
                        </select>
                    </td>
                    <td>${searched_product.isbn13}</td>

                    <td>
                        <input class="products-table__input--num quantity-product" type="number" price="${totalprice}" isbn="${
                    searched_product.isbn13
                }" value="${product_quantity}" min="1">
                        <button type="button" class="btn--remove-item" isbn="${searched_product.isbn13}">X</button>
                    </td>
                    <td id="${searched_product.isbn13}_price">${totalprice}</td>
                </tr>
            `;
                $('#productsTable').append(markup);

                move(i, length);
                i++;
            }
            Promise.resolve(response);
        },
    });
}

function getSearchPrice(product_isbn, product_title, product_type = 'ebook', product_quantity = '1', length = 0) {
    $.ajax({
        url: 'api/product-price/' + 9781838981952,
        type: 'GET',
        success: function (response) {
            avaibilityCheck(product_isbn, product_title, product_type, product_quantity, length, response);
        },
    });
}

function move(i, total_length) {
    var elem = document.getElementById('myBar');
    var width = 100 / (total_length - i);
    elem.style.width = width + '%';
    if (i == total_length - 1) {
        setInterval(
            function() {
                $('#myProgress').addClass('hide');
            }, 1000
        )
    }
}
$(document).on('change', '#fileupload', function () {
    var files = $('#fileupload').prop('files');
    var formData = new FormData();
    formData.append('productCsv', files[0]);

    $.ajax({
        url: '/product-csv',
        type: 'POST',
        data: formData,
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('#myProgress').removeClass('hide');
        },
        success: function (data) {
            asyncCall(data);
        },
        error: function (error) {
            $('#app-loader').addClass('hide');
            var response = $.parseJSON(error.responseText);
            $.each(response.errors, function (key, val) {
                $('#' + key + '_error').text(val[0]);
            });
        },
    });
});

async function asyncCall(data) {
    const array = new Array(data?.length);
    array.fill($.get('api/product-price/' + 9781838981952));
    Promise.all(array)
        .then((response) => {
            response.async = true;
            data.map(async (product) => {
                await getSearchPrice(product.isbn13, null, product.product_type, product.quantity, data.length);
            });
        })
        .catch((e) => {
            console.error(e);
            $('#myProgress').addClass('hide');
        });
}
