<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Guest/User authentication routes - Temporarily disabled
// Route::middleware('guest')->group(function () {
//     Volt::route('register', 'pages.auth.register')
//         ->name('register');
//
//     Volt::route('login', 'pages.auth.login')
//         ->name('login');
//
//     Volt::route('forgot-password', 'pages.auth.forgot-password')
//         ->name('password.request');
//
//     Volt::route('reset-password/{token}', 'pages.auth.reset-password')
//         ->name('password.reset');
// });

// Staff authentication routes
Route::middleware('guest:staff')->group(function () {
    Volt::route('staff-login', 'pages.auth.staff-login')
        ->name('staff.login');
});

// Staff logout route
Route::middleware('auth:staff')->group(function () {
    Route::post('staff-logout', function () {
        auth('staff')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('staff.login');
    })->name('staff.logout');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
