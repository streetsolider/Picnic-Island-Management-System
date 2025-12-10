<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureGuestIsCheckedIn;
use App\Http\Middleware\RedirectIfAuthenticated;
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
            'role' => CheckRole::class,
            'guest' => RedirectIfAuthenticated::class,
            'checked_in' => EnsureGuestIsCheckedIn::class,
        ]);

        // Configure authentication redirects based on the route being accessed
        $middleware->redirectGuestsTo(function ($request) {
            // Check if the request is for a staff, admin, ferry, theme-park, or beach route
            if ($request->is('staff/*') ||
                $request->is('staff-login') ||
                $request->is('admin/*') ||
                $request->is('ferry/*') ||
                $request->is('theme-park/*') ||
                $request->is('beach/*')) {
                return route('staff.login');
            }

            // Otherwise, redirect to visitor login
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
