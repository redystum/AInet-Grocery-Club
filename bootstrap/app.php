<?php

use App\Http\Middleware\isBoard;
use App\Http\Middleware\isEmployee;
use App\Http\Middleware\isMember;
use App\Http\Middleware\isNotBlocked;
use App\Http\Middleware\isNotEmployee;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'board' => isBoard::class,
            'member' => isMember::class,
            'employee' => isEmployee::class,
            'notEmployee' => isNotEmployee::class,
            'notBlocked' => isNotBlocked::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
