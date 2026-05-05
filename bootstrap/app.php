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
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (\Throwable $e) {
            try {
                \App\Models\ErrorLog::create([
                    'message'     => $e->getMessage(),
                    'level'       => 'error',
                    'file'        => $e->getFile(),
                    'line'        => $e->getLine(),
                    'stack_trace' => $e->getTraceAsString(),
                    'user_id'     => auth()->check() ? auth()->id() : null,
                ]);
            } catch (\Exception $ex) {}
        });
    })->create();
