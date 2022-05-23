<?php


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
Route::resource('redeem_report', 'RedeemReportController');
Route::resource('newsletter', 'NewsletterController');
Route::resource('password', 'ChangePasswordController');
Route::resource('client_centre', 'ClientCentreController');

