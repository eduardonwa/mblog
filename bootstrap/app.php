<?php

use App\Http\Middleware\CheckMember;
use Illuminate\Foundation\Application;
use App\Http\Middleware\RedirectIfGuest;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->alias([
            'content.member' => CheckMember::class,
            'redirect.guest' => RedirectIfGuest::class,
            'redirect.to.register' => \App\Http\Middleware\RedirectToRegister::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
