<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['authenticate.user']], function () {

    Route::get('/user-by-email/{email}', 'ManualOrderController@getUserInfo')->where('email', '(.*)');

    Route::get('/product-price/{isbn}', 'ManualOrderController@getProductPrice')->where('isbn', '(.*)');

    Route::get('/product-availability/{isbn}', 'ManualOrderController@getProductAvailability');
});
