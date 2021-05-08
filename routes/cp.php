<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    DashboardController,
    AdminController,
    DataTableController,
    SettingsController,
    UsersController,
};

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

Route::group(['prefix' => 'cp'], function () {
    Route::get('', [DashboardController::class, 'index'])->name('cp.dashbaord.index');

    Route::group(['prefix' => 'users'], function () {
        Route::get('', [UsersController::class, 'index'])->name('cp.users.index');
        Route::put('status', [UsersController::class, 'status'])->name('cp.users.status');
    });

    Route::group(['prefix' => 'admins'], function () {
        Route::get('', [AdminController::class, 'index'])->name('cp.admin.index');
        Route::get('create', [AdminController::class, 'create'])->name('cp.admin.create');
        Route::post('create', [AdminController::class, 'store'])->name('cp.admin.store');
        Route::get('edit/{id}', [AdminController::class, 'edit'])->name('cp.admin.edit')->where('id', '[0-9]+');
        Route::put('', [AdminController::class, 'update'])->name('cp.admin.update');
        Route::delete('destroy/{id}', [AdminController::class, 'destroy'])->name('cp.admin.destroy')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('', [SettingsController::class, 'index'])->name('cp.settings.index');
        Route::get('create', [SettingsController::class, 'create'])->name('cp.settings.create');
        Route::post('create', [SettingsController::class, 'store'])->name('cp.settings.store');
        Route::get('edit/{id}', [SettingsController::class, 'edit'])->name('cp.settings.edit')->where('id', '[0-9]+');
        Route::put('', [SettingsController::class, 'update'])->name('cp.settings.update');
        Route::delete('destroy/{id}', [SettingsController::class, 'destroy'])->name('cp.settings.destroy')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'datatable'], function () {
        Route::any('users', [DataTableController::class, 'getUsers'])->name('cp.datatable.users');
        Route::any('admin', [DataTableController::class, 'getAdmin'])->name('cp.datatable.admin');
        Route::any('settings', [DataTableController::class, 'getSettings'])->name('cp.datatable.settings');
    });

});










