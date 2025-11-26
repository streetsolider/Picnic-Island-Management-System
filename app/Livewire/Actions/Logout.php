<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     * Handles both guest (web) and staff guards.
     */
    public function __invoke(): void
    {
        // Logout from staff guard if authenticated as staff
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }

        // Logout from web guard if authenticated as guest
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        Session::invalidate();
        Session::regenerateToken();
    }
}
