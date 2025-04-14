<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
app()->afterBootstrapping(
    \Illuminate\Foundation\Bootstrap\BootProviders::class,
    function () {
        
        app(\Illuminate\Console\Scheduling\Schedule::class)
            ->command(DeleteUnverifiedUsers::class)
            ->daily(); 
    }
);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


