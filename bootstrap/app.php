<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\isAdminMiddleware;
use App\Http\Middleware\isCoAdminMiddleware;
use App\Http\Middleware\isDepHeadMiddleware;
use App\Http\Middleware\isFacultyMiddleware;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SessionTimeout; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            SessionTimeout::class,
        ]);
        $middleware->alias([
            'isAdmin'       => isAdminMiddleware::class,
            'isCoAdmin'     => isCoAdminMiddleware::class,
            'isDepHead'     => isDepHeadMiddleware::class,
            'isFaculty'     => isFacultyMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
