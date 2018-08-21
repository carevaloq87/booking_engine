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
});

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::get('/home', 'HomeController@index')->name('home');

// Roles
Route::group(
[
    'prefix' => 'roles',
], function () {

    Route::get('/', [
        'uses'=>'RolesController@index',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('roles.role.index');

    Route::get('/create',[
        'uses'=>'RolesController@create',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('roles.role.create');

    Route::get('/show/{role}',[
        'uses'=>'RolesController@show',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('roles.role.show')
         ->where('id', '[0-9]+');

    Route::get('/{role}/edit',[
        'uses'=>'RolesController@edit',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('roles.role.edit')
         ->where('id', '[0-9]+');

    Route::post('/', [
        'uses'=>'RolesController@store',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('roles.role.store');

    Route::put('role/{role}', [
        'uses'=>'RolesController@update',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('roles.role.update')
         ->where('id', '[0-9]+');

    Route::delete('/role/{role}',[
        'uses'=>'RolesController@destroy',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('roles.role.destroy')
         ->where('id', '[0-9]+');

});
// Users
Route::group(
[
    'prefix' => 'users',
], function () {

    Route::get('/', [
        'uses'=>'UsersController@index',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('users.user.index');

    Route::get('/create',[
        'uses'=>'UsersController@create',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('users.user.create');

    Route::get('/show/{user}',[
        'uses'=>'UsersController@show',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('users.user.show')
         ->where('id', '[0-9]+');

    Route::get('/{user}/edit',[
        'uses'=>'UsersController@edit',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('users.user.edit')
         ->where('id', '[0-9]+');

    Route::post('/', [
        'uses'=>'UsersController@store',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('users.user.store');

    Route::put('user/{user}', [
        'uses'=>'UsersController@update',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('users.user.update')
         ->where('id', '[0-9]+');

    Route::delete('/user/{user}',[
        'uses'=>'UsersController@destroy',
        'middleware' => 'roles',
        'roles' =>'Administrator'
        ])
         ->name('users.user.destroy')
         ->where('id', '[0-9]+');

});

Auth::routes();

Route::group(
[
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
    'prefix' => 'services',
], function () {

    Route::get('/', 'ServicesController@index')
         ->name('services.service.index');

    Route::get('/create','ServicesController@create')
         ->name('services.service.create');

    Route::get('/show/{service}','ServicesController@show')
         ->name('services.service.show')
         ->where('id', '[0-9]+');

    Route::get('/{service}/edit','ServicesController@edit')
         ->name('services.service.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'ServicesController@store')
         ->name('services.service.store');

    Route::put('service/{service}', 'ServicesController@update')
         ->name('services.service.update')
         ->where('id', '[0-9]+');

    Route::delete('/service/{service}','ServicesController@destroy')
         ->name('services.service.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'resources',
], function () {

    Route::get('/', 'ResourcesController@index')
         ->name('resources.resource.index');

    Route::get('/create','ResourcesController@create')
         ->name('resources.resource.create');

    Route::get('/show/{resource}','ResourcesController@show')
         ->name('resources.resource.show')
         ->where('id', '[0-9]+');

    Route::get('/{resource}/edit','ResourcesController@edit')
         ->name('resources.resource.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'ResourcesController@store')
         ->name('resources.resource.store');

    Route::put('resource/{resource}', 'ResourcesController@update')
         ->name('resources.resource.update')
         ->where('id', '[0-9]+');

    Route::delete('/resource/{resource}','ResourcesController@destroy')
         ->name('resources.resource.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
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
