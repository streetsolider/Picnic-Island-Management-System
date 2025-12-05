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

            // Always redirect guests to home page (never to staff routes)
            return redirect()->route('home');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Failed to authenticate with Google. Please try again.');
        }
    }
}
