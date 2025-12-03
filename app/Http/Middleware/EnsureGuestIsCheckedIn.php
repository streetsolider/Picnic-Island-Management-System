<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuestIsCheckedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access theme park features.');
        }

        // Get the authenticated user
        $user = auth()->user();

        // Check if user has an active checked-in hotel booking
        $hasCheckedInBooking = \App\Models\HotelBooking::where('guest_id', $user->id)
            ->where('status', 'checked_in')
            ->exists();

        if (!$hasCheckedInBooking) {
            return redirect()->route('my-bookings')
                ->with('error', 'You must check in to a hotel before accessing theme park features. Please complete your hotel check-in first.');
        }

        return $next($request);
    }
}
