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
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            \App\Http\Middleware\SessionTimeout::class,
            \App\Http\Middleware\PreventBackHistory::class,
        ]);
        $middleware->alias([
            'onboarding'     => \App\Http\Middleware\CheckOnboarding::class,
            'force_password' => \App\Http\Middleware\ForceChangePassword::class,
            'role'           => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'     => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);
    })
    ->withSchedule(function ($schedule) {
        $schedule->command('logs:archive')->monthly();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();