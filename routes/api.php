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


Route::group(['prefix' => 'v1'], function() {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('check', 'AuthController@check')->name('api.v1.auth.check');
        Route::post('login', 'AuthController@login')->name('api.v1.auth.login');
        Route::post('registration', 'AuthController@registration')->name('api.v1.auth.registration');
        Route::get('logout', 'AuthController@logout')->name('api.v1.auth.logout');
        Route::get('info', 'AuthController@info')->name('api.v1.auth.info');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::post('add-settings', 'UsersController@addSettings')->name('api.v1.user.add_settings');
        Route::post('subscribe', 'UsersController@subscribe')->name('api.v1.user.subscribe');
        Route::post('unsubscribe', 'UsersController@unsubscribe')->name('api.v1.user.unsubscribe');
        Route::get('delete/{code}', 'UsersController@delete')->name('api.v1.user.delete');
        Route::post('like', 'UsersController@like')->name('api.v1.user.like');
        Route::post('add-comment', 'UsersController@addComment')->name('api.v1.user.add_comment');
        Route::post('send-message', 'UsersController@sendMessage')->name('api.v1.user.send_message');
    });

});




