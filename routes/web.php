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

Route::get('/welcome', function () {
    return view('welcome');
})->middleware(['admin']);



//отображение формы аутентификации
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

//POST запрос аутентификации на сайте
Route::post('login', 'Auth\LoginController@login')->name('singin');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
