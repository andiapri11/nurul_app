<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'siswa' => \App\Http\Middleware\EnsureUserIsSiswa::class,
            'sarpras' => \App\Http\Middleware\EnsureUserIsSarpras::class,
            'kesiswaan' => \App\Http\Middleware\EnsureUserIsKesiswaan::class,
            'finance' => \App\Http\Middleware\EnsureUserIsFinance::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
