<?php

namespace App\Providers;

use App\Events\ForgotPassword;
use App\Events\UserRegistered;
use App\Listeners\SendForgotPasswordNotify;
use SocialiteProviders\Manager\SocialiteWasCalled;
use App\Listeners\SendConfirmationRegistrationNotify;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserRegistered::class => [
            SendConfirmationRegistrationNotify::class,
        ],
        ForgotPassword::class => [
            SendForgotPasswordNotify::class,
        ],
        SocialiteWasCalled::class => [
            'SocialiteProviders\\Facebook\\FacebookExtendSocialite@handle',
            'SocialiteProviders\\VKontakte\\VKontakteExtendSocialite@handle',
            'SocialiteProviders\\Google\\GoogleExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
