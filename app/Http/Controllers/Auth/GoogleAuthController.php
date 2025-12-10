<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth page
     */
    public function redirect(): RedirectResponse
    {
        // Preserve the intended URL if it exists
        // This will be retrieved after Google OAuth callback
        if (session()->has('url.intended')) {
            // Session is already set, no need to do anything
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if guest exists by email
            $guest = Guest::where('email', $googleUser->email)->first();

            if ($guest) {
                // Update Google ID if not set (for existing guests)
                if (!$guest->google_id) {
                    $guest->update([
                        'google_id' => $googleUser->id,
                    ]);
                }
            } else {
                // Create new guest
                $guest = Guest::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(24)), // Random password since they login with Google
                ]);
            }

            // Log the guest in
            Auth::login($guest);

            // Get the intended URL from session (if exists)
            $intendedUrl = session()->pull('url.intended');

            // If there's an intended URL and it's not a staff route, redirect there
            if ($intendedUrl && !str_contains($intendedUrl, '/staff') && !str_contains($intendedUrl, '/admin')) {
                return redirect($intendedUrl);
            }

            // Otherwise, redirect guests to home page
            return redirect()->route('home');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Failed to authenticate with Google. Please try again.');
        }
    }
}
