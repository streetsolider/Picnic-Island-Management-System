<?php

namespace App\Http\Middleware;

use App\Enums\StaffRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Role names to check against
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if staff is authenticated (staff guard)
        if (auth('staff')->check()) {
            $staff = auth('staff')->user();

            // Convert string roles to StaffRole enums
            $allowedRoles = collect($roles)->map(function ($role) {
                return StaffRole::tryFrom($role);
            })->filter()->toArray();

            // Check if staff has any of the allowed roles
            if (!$staff->hasAnyRole($allowedRoles)) {
                abort(403, 'Unauthorized. You do not have permission to access this page.');
            }

            return $next($request);
        }

        // If not authenticated on either guard, redirect to login
        return redirect()->route('login')->with('error', 'Please login to access this page.');
    }
}
