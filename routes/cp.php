<?php

use Illuminate\Support\Facades\Route;

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


Route::group(['middleware' => ['admin']], function () {
    Route::get('/', 'DashboardController@index')->name('cp.dashbaord.index');

    Route::group(['prefix' => 'users'], function () {
        Route::get('', 'UsersController@index')->name('cp.users.index');
        Route::delete('destroy/{id}', 'UsersController@destroy')->name('cp.users.destroy')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'admins'], function () {
        Route::get('', 'AdminController@index')->name('cp.admin.index');
        Route::get('create', 'AdminController@create')->name('cp.admin.create');
        Route::post('store', 'AdminController@store')->name('cp.admin.store');
        Route::get('edit/{id}', 'AdminController@edit')->name('cp.admin.edit')->where('id', '[0-9]+');
        Route::put('update', 'AdminController@update')->name('cp.admin.update');
        Route::delete('destroy/{id}', 'AdminController@destroy')->name('cp.admin.destroy')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'datatable'], function () {
        Route::any('users', 'DataTableController@getUsers')->name('cp.datatable.users');
        Route::any('admin', 'DataTableController@getAdmin')->name('cp.datatable.admin');
    });

});
