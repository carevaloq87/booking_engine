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
    return view('welcome');
})->name('welcome');

Auth::routes();
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::match(['get', 'post'], '/login_vla', 'AuthController@loginVLA')->name('login_VLA');\
//Route::get('/home', 'HomeController@index')->name('home');

// Roles
Route::group(
[
    'middleware' => ['auth'],
    'prefix' => 'roles',
], function () {

    Route::get('/', 'RoleController@index')
        ->name('roles.index');

    Route::get('/create','RoleController@create')
        ->name('roles.create');

    Route::get('/show/{role}', 'RoleController@show')
        ->name('roles.show')
        ->where('id', '[0-9]+');

    Route::get('/{role}/edit','RoleController@edit')
        ->name('roles.edit')
        ->where('id', '[0-9]+');

    Route::post('/','RoleController@store')
        ->name('roles.store');

    Route::put('role/{role}', 'RoleController@update')
        ->name('roles.update')
        ->where('id', '[0-9]+');

    Route::delete('/role/{role}', 'RoleController@destroy')
        ->name('roles.destroy')
        ->where('id', '[0-9]+');

});

// Users
Route::group(
[
    'middleware' => ['auth'],
    'prefix' => 'users'
], function () {

    Route::get('/', 'UserController@index')
        ->name('users.index');

    Route::get('/create', 'UserController@create')
        ->name('users.create');

    Route::get('/show/{user}', 'UserController@show')
        ->name('users.show')
        ->where('id', '[0-9]+');

    Route::get('/{user}/edit', 'UserController@edit')
        ->name('users.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'UserController@store')
        ->name('users.store');

    Route::put('user/{user}', 'UserController@update')
        ->name('users.update')
        ->where('id', '[0-9]+');

    Route::delete('/user/{user}', 'UserController@destroy')
        ->name('users.destroy')
        ->where('id', '[0-9]+');

});

Route::group(
[
    'middleware' => ['auth'],
    'prefix' => 'service_providers',
], function () {

    Route::get('/', 'ServiceProvidersController@index')
        ->name('service_providers.service_provider.index');

    Route::get('/create','ServiceProvidersController@create')
        ->name('service_providers.service_provider.create');

    Route::get('/show/{serviceProvider}','ServiceProvidersController@show')
        ->name('service_providers.service_provider.show')
        ->where('id', '[0-9]+');

    Route::get('/{serviceProvider}/edit','ServiceProvidersController@edit')
        ->name('service_providers.service_provider.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'ServiceProvidersController@store')
        ->name('service_providers.service_provider.store');

    Route::put('service_provider/{serviceProvider}', 'ServiceProvidersController@update')
        ->name('service_providers.service_provider.update')
        ->where('id', '[0-9]+');

    Route::delete('/service_provider/{serviceProvider}','ServiceProvidersController@destroy')
        ->name('service_providers.service_provider.destroy')
        ->where('id', '[0-9]+');

});

Route::group(
[
    'middleware' => ['auth'],
    'prefix' => 'services',
], function () {

    Route::get('/', 'ServiceController@index')
        ->name('services.service.index');

    Route::get('/create','ServiceController@create')
        ->name('services.service.create');

    Route::get('/show/{service}','ServiceController@show')
        ->name('services.service.show')
        ->where('id', '[0-9]+');

    Route::get('/{service}/edit','ServiceController@edit')
        ->name('services.service.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'ServiceController@store')
        ->name('services.service.store');

    Route::put('service/{service}', 'ServiceController@update')
        ->name('services.service.update')
        ->where('id', '[0-9]+');

    Route::delete('/service/{service}','ServiceController@destroy')
        ->name('services.service.destroy')
        ->where('id', '[0-9]+');

    Route::get('/getResources/{service}','ServiceController@getResources')
        ->name('services.service.getResources')
        ->where('id', '[0-9]+');

    Route::get('/getByUserServiceProvider', 'ServiceController@getServicesByUserServiceProvider')
        ->name('services.service.getByUserServiceProvider');

    Route::get('/getAvailabilitybyService/{service}', 'ServiceController@getAvailabilityById')
        ->name('services.service.getAvailability')
        ->where('id','[0-9]');
});

Route::group(
[
    'middleware' => ['auth'],
    'prefix' => 'resources',
], function () {

    Route::get('/', 'ResourceController@index')
        ->name('resources.resource.index');

    Route::get('/create','ResourceController@create')
        ->name('resources.resource.create');

    Route::get('/show/{resource}','ResourceController@show')
        ->name('resources.resource.show')
        ->where('id', '[0-9]+');

    Route::get('/{resource}/edit','ResourceController@edit')
        ->name('resources.resource.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'ResourceController@store')
        ->name('resources.resource.store');

    Route::put('resource/{resource}', 'ResourceController@update')
        ->name('resources.resource.update')
        ->where('id', '[0-9]+');

    Route::delete('/resource/{resource}','ResourceController@destroy')
        ->name('resources.resource.destroy')
        ->where('id', '[0-9]+');

    Route::get('/getServices/{resource}','ResourceController@getServices')
        ->name('resources.resource.getResources')
        ->where('id', '[0-9]+');

    Route::get('/getByUserServiceProvider', 'ResourceController@getResourcesByUserServiceProvider')
        ->name('resources.resource.getByUserServiceProvider');

});

Route::group(
[
    'middleware' => ['auth'],
    'prefix' => 'served_by',
], function () {

    Route::get('/', 'ServedByController@index')
        ->name('served_by.served_by.index');

    Route::get('/create','ServedByController@create')
        ->name('served_by.served_by.create');

    Route::get('/show/{servedBy}','ServedByController@show')
        ->name('served_by.served_by.show')
        ->where('id', '[0-9]+');

    Route::get('/{servedBy}/edit','ServedByController@edit')
        ->name('served_by.served_by.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'ServedByController@store')
        ->name('served_by.served_by.store');

    Route::put('served_by/{servedBy}', 'ServedByController@update')
        ->name('served_by.served_by.update')
        ->where('id', '[0-9]+');

    Route::delete('/served_by/{servedBy}','ServedByController@destroy')
        ->name('served_by.served_by.destroy')
        ->where('id', '[0-9]+');

});

Route::group(
[
    'middleware' => ['auth'],
    'prefix' => 'booking_status',
], function () {

    Route::get('/', 'BookingStatusController@index')
        ->name('booking_status.booking_status.index');

    Route::get('/create','BookingStatusController@create')
        ->name('booking_status.booking_status.create');

    Route::get('/show/{bookingStatus}','BookingStatusController@show')
        ->name('booking_status.booking_status.show')
        ->where('id', '[0-9]+');

    Route::get('/{bookingStatus}/edit','BookingStatusController@edit')
        ->name('booking_status.booking_status.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'BookingStatusController@store')
        ->name('booking_status.booking_status.store');

    Route::put('booking_status/{bookingStatus}', 'BookingStatusController@update')
        ->name('booking_status.booking_status.update')
        ->where('id', '[0-9]+');

    Route::delete('/booking_status/{bookingStatus}','BookingStatusController@destroy')
        ->name('booking_status.booking_status.destroy')
        ->where('id', '[0-9]+');

});

Route::group(
[
    'middleware' => ['auth'],
    'prefix' => 'calendar',
], function () {

    Route::get('/service/days/{serviceId}','CalendarController@getServiceDays')
        ->where('serviceId', '[0-9]+');

    Route::get('/service/hours/{serviceId}','CalendarController@getServiceHours')
        ->where('serviceId', '[0-9]+');

    Route::get('/service/adhoc/{serviceId}','CalendarController@getFutureAdhocs')
        ->where('serviceId', '[0-9]+');

    Route::get('/resource/adhoc/{resourceId}','CalendarController@getFutureResourceAdhocs')
        ->where('resourceId', '[0-9]+');

    Route::get('/resource/days/{resourceId}','CalendarController@getResourceDays')
        ->where('resourceId', '[0-9]+');

	Route::get('/resource/hours/{resourceId}','CalendarController@getResourceHours')
        ->where('resourceId', '[0-9]+');

    Route::post('/service/days','CalendarController@storeDays');

    Route::post('/service/hours','CalendarController@storeHours');

    Route::post('/service/adhoc','CalendarController@storeAdhoc');

    Route::post('/service/adhoc/delete','CalendarController@deleteServiceAdhoc');

    Route::post('/resource/adhoc/delete','CalendarController@deleteResourceAdhoc');

    Route::post('/resource/days','CalendarController@storeResourceDays');

    Route::post('/resource/hours','CalendarController@storeResourceHours');

    Route::post('/resource/adhoc','CalendarController@storeResourceAdhoc');
});


Route::group(
[
    'middleware' => ['auth'],
    'prefix' => 'bookings',
], function () {

    Route::get('/', 'BookingController@index')
        ->name('bookings.booking.index');

    Route::post('/', 'BookingController@store')
        ->name('bookings.booking.store');

	Route::get('/service','BookingController@getBookingsByDate');
});
Route::group(
[
    'prefix' => 'clients',
], function () {

    Route::get('/', 'ClientsController@index')
        ->name('clients.client.index');

    Route::get('/create','ClientsController@create')
        ->name('clients.client.create');

    Route::get('/show/{client}','ClientsController@show')
        ->name('clients.client.show')
        ->where('id', '[0-9]+');

    Route::get('/{client}/edit','ClientsController@edit')
        ->name('clients.client.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'ClientsController@store')
        ->name('clients.client.store');

    Route::put('client/{client}', 'ClientsController@update')
        ->name('clients.client.update')
        ->where('id', '[0-9]+');

    Route::delete('/client/{client}','ClientsController@destroy')
        ->name('clients.client.destroy')
        ->where('id', '[0-9]+');

});
