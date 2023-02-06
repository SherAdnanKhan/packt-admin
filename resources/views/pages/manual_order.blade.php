@extends('layout')

@section('title')
    Create Order
@endsection

@section('customcss')
<style>
    #isbn_search {
        width: 90%;
        margin-bottom: unset;
    }

    .container #button_submit_order {
        margin: 30px;
    }
    #id_paypal,
    #id_bank {
        width: 80%;
    }
    #id_bank-error,
    #id_paypal-error {
        margin: 10px;
    }
    #upload-products {
        float: right
    }
</style>

@endsection

@section('content')
    <div class="container">
            <div class="row">
                <label>E-mail Address</label>
                <input class="input-txt" type="email" id="emailInput" name="email">
                <button type="button" class="btn" id="searchEmailButton">Search</button>
            </div>
    </div>
    <form class="form" id="manualOrderForm" action="{{action('ManualOrderController@place_order')}}" method="POST">
        {{ csrf_field() }}

        <div class="container">
            <div class="row hide" id="errorResponse">
                <p> No user found </p>
            </div>
        </div>

        <p>User Information</p>
        <hr>
        <div class="container user-info-container">
            <div class="brow">
                <div class="col">
                    <p> <strong>Email Address:</strong> <span id="userEmail"> </span> </p>
                </div>
                <div class="col">
                    <p> <strong>Company Name:</strong> <span id="userCompany"> </span> </p>
                </div>
            </div>
            <div class="brow">
                <div class="col">
                    <p> <strong>First Name:</strong> <span id="userFname"> </span> </p>
                </div>
                <div class="col">
                    <p> <strong>Last Name:</strong> <span id="userLname"> </span> </p>
                </div>
            </div>
            <div class="brow">
                <div class="col">
                    <p> <strong>Discount Group:</strong> <span id="userDG"> </span> </p>
                </div>
                <div class="col">
                    <p> <strong>Billing Country:</strong> <span id="userBCountry"> </span> </p>
                </div>
            </div>
            <div class="brow">
                <div class="col">
                    <p> <strong>VAT no:</strong> <span id="userVat"> </span> </p>
                </div>
                <div class="col">
                    <p> <strong>Currency:</strong> <span id="userCurrency"> </span> </p>
                </div>
            </div>
            <div class="brow">
                <div class="col">
                    <p> <strong>Customer Since:</strong> <span id="userJoinDate"> </span> </p>
                </div>
            </div>
        </div>
        <p>Address Information</p>
        <hr>
        <p class="label"> Choose Billing Address </p>
        <div class="addressDiv container">
            <div class="brow" id="billingAddress">

            </div>
        </div>
        <div class="separator separator-dashed my-8"></div>


        @include('partials.addresses')

        {{--
        - Checkmark top right to select billing and shipping
        - If user doesn't have billing and shipping set then display form to input addresses
        --}}
        </br>
        <div class="brow ">
            <div class="col-6 flexDisplay">
                <div class="col-6">
                    <h3> Currency </h3>
                </div>
                <select  class="input-txt" id= "Currency" name="userCurrency">
                    <option value="USD" selected="selected">United States Dollars</option>
                    <option value="EUR">Euro</option>
                    <option value="GBP">United Kingdom Pounds</option>
                    <option value="AUD">Australia Dollars</option>
                    <option value="INR">India Rupees</option>
                </select>
            </div>
        </div>

        <div class="brow">
          <div class="col">
            <p> Orders</p>
          </div>
          <div class="col">
          <a  style ="float:right;margin-left: 20px;" href="/download" class="btn  pull-right"> Download Sample </a> 
            <button type="button" class="btn" id="upload-products">Import Products</button>
            <input  style ="float:right" class="hide" id="fileupload" type="file" name="productCsv" accept=".csv">
          </div>
        </div>
        </h3>
        <hr>

        <div class="container">
            <div class="brow">
                <div class="col" id="p-search-row">
                    <label>ISBN</label>
                    <input class="input-txt" type="text" id="isbn_search" name="isbn_search">
                </div>
            </div>
        </div>

        <table class="products-table" id="productsTable">
            <thead>
            @foreach($vars['product_info'] as $item)
                <th>{{$item['label']}}</th>
            @endforeach

            </thead>
            <tbody>

            </tbody>

            <tfoot>
            @foreach($vars['products_footer_labels'] as $footer)
                <tr>
                    <td class="tfoot-label-cell" colspan="4">{{ $footer['label'] }}</td>
                    @if($footer['id'] == 'subtotal' || $footer['id'] == 'grand-total')
                        <td id="{{ $footer['id'] }}"></td>
                    @else
                        <td><input type="number" id="{{ $footer['id'] }}" name="{{$footer['name']}}" class="products-table__input--num" min="0.00" value="0"></td>
                    @endif
                </tr>
            @endforeach
            </tfoot>
        </table>

        <p>Payment and Shipping Information</p>
        <hr>

        <div class="form-row">
            <div class="form-col">
                <h3>Payment Method</h3>
                <select name="payment_method" id="payment_method">
                    <option value="cash">Cash</option>
                    <option value="bank">Bank</option>
                    <option value="paypal">Paypal</option>
                </select>
            </div>
            <div class="form-col hide" id="paypal">
                <h3>PayPal ID</h3>
                <input type="text" id="id_paypal" name="id_paypal">
            </div>
            <div class="form-col hide" id="bank">
                <h3>Bank ID</h3>
                <input type="text" id="id_bank" name="id_bank">
            </div>
            <div class="form-col">
                <h3>Shipping Method</h3>
                <select name="shipping_method" id="shipping_method">
                    <option value="express">Express</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>
        <input type="hidden" id="productLists" name="productList" value="[]">
        <div class="container">
            <input class="btn" id="button_submit_order" type="submit" name="submit_order" value="Submit Order">
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/algoliasearch@3/dist/algoliasearchLite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>
    <script src="{{asset('js/order_submit.js')}}"> </script>
    <script src="{{asset('js/country_city.js')}}"> </script>
    <script src="{{asset('js/modal.js')}}"> </script>

@endsection
