$(document).ready(function () {
    $('body').on('submit', '#ProductCsv', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: '/product-csv',
            type: 'POST',
            data: formData,
            headers: {
                'Access-Control-Allow-Origin': '*',
            },
            processData: false,
            contentType: false,
            success: function (data) {
                $('#app-loader').addClass('hide');
                for (var index = 0; index <= data.length; index++) {
                    1;
                }
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
    const { round } = require('lodash');
    var productsList = [];
    var subtotal = 0.0;
    let prices = [];

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
            let price = 0;
            $('#isbn_search').val('');
            console.log(suggestion);
            $('#app-loader').removeClass('hide');

            let filteredProduct = productsList.filter((product) => product.isbn == suggestion.isbn13);
            if (filteredProduct.length == 0) {
                const markup = `
                <tr id="${suggestion.isbn13}_tr">
                    <td>${suggestion.title}</td>
                    <td>
                        <select name="product_type" class="product_type" id="${suggestion.isbn13}_product_type" isbn="${suggestion.isbn13}">
                            <option value="ebook" selected> Ebook </option>
                            <option value="print"> Print </option>
                        </select>
                    </td>
                    <td>${suggestion.isbn13}</td>

                    <td>
                        <input class="products-table__input--num quantity-product" type="number" price="${price}" isbn="${suggestion.isbn13}" value="1" min="1">
                        <button type="button" class="btn--remove-item" isbn="${suggestion.isbn13}">X</button>
                    </td>
                    <td id="${suggestion.isbn13}_price">${price}</td>
                </tr>
            `;

                $('#productsTable').append(markup);
            }
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
            updateSubtotal(productsList);
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
            updateSubtotal(productsList);
        });
        $(document).on('change', '.quantity-product', function () {
            let product_isbn = $(this).attr('isbn');
            productsList = productsList.map((product) => {
                if (product.isbn == product_isbn) {
                    var total_price = round(parseFloat(product.price) * parseFloat(this.value), 2);
                    if (isNaN(total_price)) {
                        total_price = 0;
                    }
                    $(`#${product.isbn}_price`).html(total_price);
                    return {
                        ...product,
                        quantity: this.value,
                        total_price: total_price,
                    };
                }
                return product;
            });
            updateSubtotal(productsList);
            // updateDiscount();
            updateGrandTotal();
        });

        $(document).on('change', '#payment_method', function () {
            //console.log(this.value);
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

        function updateSubtotal(products) {
            subtotal = 0;
            $('#isbn_search').val('');
            products.forEach((product) => {
                subtotal += round(parseFloat(product.total_price), 2);
            });
            //console.log(subtotal);
            $('#subtotal').html(round(subtotal, 2));

            //updateDiscount();
            updateGrandTotal();
        }

        function updateGrandTotal() {
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
                // console.log(userDiscountGroup);
                if (subtotal != 0) {
                    total_discount = parseFloat(subtotal) * (parseFloat(userDiscountGroup) / 100);
                }
                //  console.log('totalDiscount:', total_discount);
                $('#discount').val(round(total_discount, 2));
            }
        }
    })();
});
