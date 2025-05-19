<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\PreventBackHistory;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register the admin authentication middleware
        $middleware->appendToGroup('admin', [AdminAuthenticate::class]);
        
        // Add the PreventBackHistory middleware to the web group
        $middleware->appendToGroup('web', [PreventBackHistory::class]);
        
        // Alternatively, if you want it to run on all routes
        // $middleware->append(PreventBackHistory::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();