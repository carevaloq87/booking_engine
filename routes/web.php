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

