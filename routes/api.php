<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\User\SocialController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Media\ImageController;
use App\Http\Controllers\Api\Media\VideoController;
use App\Http\Controllers\Api\Dictionary\TagController;
use App\Http\Controllers\Api\Auth\RegistrationController;

use App\Http\Controllers\Api\Auth\EmailConfirmationController;
use App\Http\Controllers\Api\Profile\ChangePasswordController;


use App\Http\Controllers\Api\Auth\{
    ForgotPasswordController,
    AuthController
};

use App\Http\Controllers\Api\{
    ProfileController,
    LikesController,
    SubscriptionsController,
    CommentsController,
    MessagesController,
    ShareController
};


//TODO create guard
// guest, user(registered user), user-confirmed(user with confirmed email), pro-user(user-confirmed which paid for this status)
// alpha ver. everyone has pro-user status and need to confirm email

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login'])->name('api.v1.auth.login');
        Route::post('registration', [AuthController::class, 'registration'])->name('api.v1.auth.registration');
        Route::get('info', [AuthController::class, 'info'])->name('api.v1.auth.info');
        Route::post('forgot-password', ForgotPasswordController::class)->name('api.v1.auth.forgot_password');
        Route::get('logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::post('edit', [ProfileController::class, 'edit'])->name('api.v1.profile.edit');
        Route::post('change-password', [ProfileController::class, 'changePassword'])->name('api.v1.profile.change_password');
        Route::delete('delete/{code}', [ProfileController::class, 'delete'])->name('api.v1.profile.delete');
    });

    Route::group(['prefix' => 'comments'], function () {
        Route::post('', [CommentsController::class, 'getList'])->name('api.v1.comments.list');
        Route::post('add', [CommentsController::class, 'add'])->name('api.v1.comments.add');
        Route::delete('{id}', [CommentsController::class, 'delete'])->name('api.v1.comments.delete')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'messages'], function () {
        Route::post('list', [MessagesController::class, 'getList'])->name('api.v1.messages.list');
        Route::post('send', [MessagesController::class, 'send'])->name('api.v1.messages.send');
        Route::delete('delete/{id}', [MessagesController::class, 'delete'])->where('id', '[0-9]+')->name('api.v1.messages.delete');
    });

    Route::post('like', LikesController::class)->name('api.v1.like');
    Route::post('share', ShareController::class)->name('api.v1.share');

    Route::group(['prefix' => 'subscriptions'], function () {
        Route::get('', [SubscriptionsController::class, 'getList'])->name('api.v1.subscribe.subscriptions.list');
        Route::post('subscribe', [SubscriptionsController::class, 'subscribe'])->name('api.v1.subscriptions.subscribe');
    });

    // SubscriptionsController

    // Для всех пользователей
    // Справочники
    Route::prefix('dictionary')->group(function () {
        Route::get('tags', TagController::class)->name('api.v1.dictionary.tags');
    });

    // Лента и просмор поста
    Route::prefix('posts')->group(function () {
        Route::get('', [PostController::class, 'index'])->name('api.v1.posts.index');
        Route::get('{post}', [PostController::class, 'show'])->name('api.v1.posts.show');
    });

   // Auth::routes();

    Route::group(['middleware' => ['auth']], function () {
        // Загрузка видео и изображений
        Route::prefix('media')->group(function () {
            Route::post('upload/video', VideoController::class)->name('api.v1.media.upload.video')->middleware('can:isAuthor');
            Route::post('upload/video-cover', [ImageController::class, 'uploadVideoCover'])->name('api.v1.media.upload.video_cover')->middleware('can:isAuthor');
        });
        // CRUD постов
        Route::apiResource('posts', PostController::class)->except(['index', 'show']);
        Route::get('posts/{post}/edit', [PostController::class, 'edit'])->middleware('can:edit,post')->name('api.v1.posts.edit');
    });

    //public routes
    Route::group(['middleware' => ['web']], function () {
        Route::get('/auth/social/{driver}', [SocialController::class, 'redirectToProvider'])->name('api.v1.social.redirect');
        Route::get('/auth/social/{driver}/callback', [SocialController::class, 'loginWithSocial'])->name('api.v1.social.login');
    });

});







