<?php

/*
|--------------------------------------------------------------------------
| Affiliate Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Admin

use App\Http\Controllers\AffiliateController;

Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    Route::controller(AffiliateController::class)->group(function () {

        Route::get('/affiliate/employee', 'employee')->name('affiliate.employee.index');
        Route::get('/affiliate/employee/create', 'employee_create')->name('affiliate.employee.create');
        Route::post('/affiliate/employee/create', 'employee_store')->name('affiliate.employee.create');
        Route::get('/affiliate/employee/edit/{id}', 'employee_edit')->name('affiliate.employee.edit');
        Route::post('/affiliate/employee/edit/{id}', 'employee_update')->name('affiliate.employee.update');
        Route::get('/affiliate/employee/destroy/{id}', 'employee_destroy')->name('affiliate.employee.destroy');
        Route::get('/affiliate/employee/update/{id}', 'updateToAgent')->name('affiliate.employee.updateToAgent');

        Route::get('/affiliate/kol', 'kol')->name('affiliate.kol.index');
        Route::get('/affiliate/kol/create', 'kol_create')->name('affiliate.kol.create');
        Route::post('/affiliate/kol/create', 'kol_store')->name('affiliate.kol.create');
        Route::get('/affiliate/kol/edit/{id}', 'kol_edit')->name('affiliate.kol.edit');
        Route::post('/affiliate/kol/edit/{id}', 'kol_update')->name('affiliate.kol.update');
        Route::get('/affiliate/kol/destroy/{id}', 'kol_destroy')->name('affiliate.kol.destroy');
        Route::get('/affiliate/kol/combinations', 'combination')->name('affiliate.kol.combinations');

        Route::get('/affiliate/depot', 'depot')->name('affiliate.depot.index');
        Route::get('/affiliate/depot/create', 'depot_create')->name('affiliate.depot.create');
        Route::post('/affiliate/depot/create', 'depot_store')->name('affiliate.depot.create');
        Route::get('/affiliate/depot/edit/{id}', 'depot_edit')->name('affiliate.depot.edit');
        Route::post('/affiliate/depot/edit/{id}', 'depot_update')->name('affiliate.depot.update');
        Route::get('/affiliate/depot/destroy/{id}', 'depot_destroy')->name('affiliate.depot.destroy');
        Route::get('/affiliate/depot/update/{id}', 'updateToDepot')->name('affiliate.employee.updateToDepot');


        //Route::get('/affiliate', 'index')->name('affiliate.index');
        //Route::post('/affiliate/affiliate_option_store', 'affiliate_option_store')->name('affiliate.store');

        Route::get('/affiliate/configs', 'configs')->name('affiliate.configs');

        Route::get('/affiliate/request_payment', 'requestPayment')->name('affiliate.request_payment');
        Route::get('/affiliate/history_payment', 'historyPayment')->name('affiliate.history_payment');
        Route::post('/affiliate/update_payment/{id}', 'updatePayment')->name('affiliate.update_payment');
        Route::post('/affiliate/cancel_payment/{id}', 'cancelPayment')->name('affiliate.cancel_payment');


        //Route::post('/affiliate/configs/store', 'config_store')->name('affiliate.configs.store');

        /*Route::get('/affiliate/users', 'users')->name('affiliate.users');
        Route::get('/affiliate/verification/{id}', 'show_verification_request')->name('affiliate_users.show_verification_request');

        Route::get('/affiliate/approve/{id}', 'approve_user')->name('affiliate_user.approve');
        Route::get('/affiliate/reject/{id}', 'reject_user')->name('affiliate_user.reject');

        Route::post('/affiliate/approved', 'updateApproved')->name('affiliate_user.approved');

        Route::post('/affiliate/payment_modal', 'payment_modal')->name('affiliate_user.payment_modal');
        Route::post('/affiliate/pay/store', 'payment_store')->name('affiliate_user.payment_store');

        Route::get('/affiliate/payments/show/{id}', 'payment_history')->name('affiliate_user.payment_history');
        Route::get('/refferal/users', 'refferal_users')->name('refferals.users');

        // Affiliate Withdraw Request
        Route::get('/affiliate/withdraw_requests', 'affiliate_withdraw_requests')->name('affiliate.withdraw_requests');
        Route::post('/affiliate/affiliate_withdraw_modal', 'affiliate_withdraw_modal')->name('affiliate_withdraw_modal');
        Route::post('/affiliate/withdraw_request/payment_store', 'withdraw_request_payment_store')->name('withdraw_request.payment_store');
        Route::get('/affiliate/withdraw_request/reject/{id}', 'reject_withdraw_request')->name('affiliate.withdraw_request.reject');

        Route::get('/affiliate/logs', 'affiliate_logs_admin')->name('affiliate.logs.admin');*/

    });
});

//FrontEnd
Route::controller(AffiliateController::class)->group(function () {
    Route::get('/affiliate', 'apply_for_affiliate')->name('affiliate.apply');
    Route::post('/affiliate/store', 'store_affiliate_user')->name('affiliate.store_affiliate_user');
});

Route::group(['middleware' => ['auth']], function(){
    Route::controller(AffiliateController::class)->group(function () {
        Route::get('/affiliate/user', 'user_index')->name('affiliate.user.index');


        Route::get('/affiliate/user/payment_history', 'user_payment_history')->name('affiliate.user.payment_history');
        Route::get('/affiliate/user/withdraw_request_history', 'user_withdraw_request_history')->name('affiliate.user.withdraw_request_history');

        Route::get('/affiliate/payment/settings', 'payment_settings')->name('affiliate.payment_settings');
        Route::post('/affiliate/payment/settings/store', 'payment_settings_store')->name('affiliate.payment_settings_store');

        // Affiliate Withdraw Request
        Route::post('/affiliate/withdraw_request/store', 'withdraw_request_store')->name('affiliate.withdraw_request.store');
    });
});
