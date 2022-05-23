<?php

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/cancel', 'ContipayRedirectController@index');
Route::get('/success', 'SuccessRedirectController@index');
Route::get('/pending', 'PendingController@index');
Route::get('/', 'PendingController@show');
Route::resource('topup', 'TopupController');
Route::resource('account', 'AccountController');
Route::resource('default', 'DefaultCurrencyController');
Route::resource('analysis', 'WalletTopupController');
Route::resource('redeem', 'RedeemPointsController');
Route::resource('covert', 'ConvertController');
Route::resource('wallet', 'WalletPaymentController');
Route::resource('purchase', 'PurchasePromotionController');
Route::resource('redeemedProductsLogs', 'RedeemReportController');
