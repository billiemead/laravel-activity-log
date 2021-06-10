<?php

namespace Billiemead\LaravelActivityLog;

use Billiemead\LaravelActivityLog\Listeners\LockoutListener;
use Billiemead\LaravelActivityLog\Listeners\LoginListener;
use Billiemead\LaravelActivityLog\Listeners\LogoutListener;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class   => [
            LoginListener::class
        ],
        Logout::class   => [
            LogoutListener::class
        ],
        Lockout::class => [
            LockoutListener::class
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}