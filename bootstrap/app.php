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
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'admin'      => \App\Http\Middleware\AdminMiddleware::class,
            'supervisor' => \App\Http\Middleware\SupervisorMiddleware::class,
        ]);

        $middleware->api(append: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        /**
         * New Feature: global handler for \RuntimeException thrown by services
         * (e.g. "Not enough stock"). Uses \RuntimeException — not the base
         * \Exception — so that Laravel's own AuthenticationException,
         * ValidationException, and HttpException are still handled by their
         * built-in renderers and are not accidentally swallowed here.
         */
        $exceptions->render(function (\RuntimeException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        });
    })->create();
