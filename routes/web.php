<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManualOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/logout', function () {
    Cookie::queue(Cookie::forget('access_token_development_new', null, '.packtpub.com'));
    Cookie::queue(Cookie::forget('refresh_token_development_new', null, '.packtpub.com'));
    return redirect(env('ADMIN_PORTAL_URL'));
});

Route::get('/{oldAdminPage?}', function ($oldAdminPage = '') {
    return redirect(env('ADMIN_PORTAL_URL') . str_replace('old/', '', $oldAdminPage));
})->where('oldAdminPage', '(old.*)');

Route::group(['middleware' => ['authenticate.user']], function () {
    Route::get('/new-account', 'AccountController@index');

    Route::match(['get', 'post'], '/manual_order', [ManualOrderController::class, 'place_order'])->name('manual_order');

    Route::get('/manual-order', 'ManualOrderController@index');

    Route::post('/new-account', [
        'uses' => 'AccountController@register',
    ]);

    Route::post('/manual-order', [
        'uses' => 'ManualOrderController@place_order',
    ]);

    Route::post('/product-csv', [
        'uses' => 'ManualOrderController@product_csv',
    ]);

    Route::get('/product-csv', [
        'uses' => 'ManualOrderController@product_csv',
    ]);

    Route::get('/download', [
        'uses' => 'ManualOrderController@getDownload',
    ]);

    //state routes
    Route::get('/get-states/{country_id}', 'StateController@getStatesByCountryId');
    Route::get('/get-cities-by-country/{country_id}', 'CityController@getCitiesByCountryId');
    Route::get('/get-cities-by-state/{state_id}', 'CityController@getCitiesByStateId');

    //Tax Route
    Route::post('/get-taxes', 'ManualOrderController@getTax');
});
