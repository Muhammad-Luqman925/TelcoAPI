<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // ✅ tambahkan route api agar /api/... bisa dikenali
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ aktifkan Sanctum untuk API token authentication
        $middleware->api(prepend: [
             \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // kalau kamu ingin tambahkan global middleware lain bisa di sini, contoh:
        // $middleware->web(prepend: [\App\Http\Middleware\VerifyCsrfToken::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // bisa tambahkan custom error handler di sini
    })
    ->create();
