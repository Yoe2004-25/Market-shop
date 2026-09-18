<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Isadmain;
use App\Http\Middleware\EnsureEmailIsVerified; 
use App\Http\Middleware\HandleInertiaRequests; 
use App\Http\Middleware\SQLInjectionProtection; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'Isadmain'=>Isadmain::class , 
            'SQLInjectionProtection'=>SQLInjectionProtection::class , 
            'HandleInertiaRequests'=>HandleInertiaRequests::class, 
            'EnsureEmailIsVerified'=>EnsureEmailIsVerified::class , 
            
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();