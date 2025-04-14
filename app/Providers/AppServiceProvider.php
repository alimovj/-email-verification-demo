<?php

namespace App\Providers;

use App\Events\UserRegistered;
use Illuminate\Support\ServiceProvider;
use App\Listeners\SendVerificationEmail;

class AppServiceProvider extends ServiceProvider
{



    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
