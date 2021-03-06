<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::post('booking', 'ApiController@storeBooking');
        Route::post('service', 'ApiController@storeService');
        Route::patch('booking/{bo_id}', 'ApiController@updateBooking');
        Route::delete('booking/{bo_id}', 'ApiController@deleteBooking');
        Route::get('/service/{service_id}/availability/{start_date}/{end_date}', 'ApiController@getServiceAvailability');
        Route::get('/service/{services}/booking/{start_date}/{end_date}', 'ApiController@getBookingsByServiceId');
        Route::get('/booking_status/{booking_status}','ApiController@getBookingStatusByName');
        Route::get('/booking_status','ApiController@getAllBookingStatus');
        Route::get('/service','ApiController@getAllServices');
        Route::get('/booking/date/{date}', 'ApiController@getBookingsByDate');
        Route::get('/service/service_provider/{service_provider_name}', 'ApiController@getServiceBySPName');
        Route::get('/booking/{service_provider_name}/{start_date}/{end_date}', 'ApiController@getBookingsBySPNameAndDate');
        Route::get('/booking/orbit_user/{created_by}', 'ApiController@getBookingsByOBUserId');
        Route::get('/booking/{start_date}/{end_date}', 'ApiController@getAllBookingsByDate');
        Route::get('/stats_day/{day}', 'ApiController@getBookingStatsDay');
        Route::get('/stats_period/{startDay}', 'ApiController@getBookingStatsPeriod');
    });
});