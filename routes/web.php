<?php

use App\Livewire\Home;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Public home page
Route::get('/', Home::class)->name('home');

// Guest (Customer) Routes - Temporarily disabled
// Route::middleware(['auth:web', 'verified'])->group(function () {
//     // Guest Dashboard
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
//
//     // Profile
//     Route::view('profile', 'profile')->name('profile');
// });

// Staff Routes (using staff guard)
Route::middleware(['auth:staff'])->group(function () {
    // Main staff dashboard - redirects to role-specific dashboard
    Route::get('/staff/dashboard', function () {
        return redirect()->route(auth('staff')->user()->getDashboardRoute());
    })->name('staff.dashboard');

    // Hotel Manager Dashboard
    Route::get('/hotel/dashboard', function () {
        return view('dashboard');
    })->middleware('role:hotel_manager')->name('hotel.dashboard');

    // Ferry Operator Dashboard
    Route::get('/ferry/dashboard', function () {
        return view('dashboard');
    })->middleware('role:ferry_operator')->name('ferry.dashboard');

    // Theme Park Staff Dashboard
    Route::get('/theme-park/dashboard', function () {
        return view('dashboard');
    })->middleware('role:theme_park_staff')->name('theme-park.dashboard');

    // Administrator Routes
    Route::middleware('role:administrator')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/staff', \App\Livewire\Admin\Staff\Index::class)->name('staff.index');
        Route::get('/hotels', \App\Livewire\Admin\Hotels\Index::class)->name('hotels.index');
        Route::get('/theme-park', \App\Livewire\Admin\ThemePark\Zones::class)->name('theme-park');
        Route::get('/beach/areas', \App\Livewire\Admin\Beach\Areas::class)->name('beach.areas');

        // Ferry Management
        Route::get('ferry', App\Livewire\Admin\Ferry\Index::class)->name('ferry.index');
    });

    // Profile for staff
    Route::view('/staff/profile', 'profile')->name('staff.profile');
});

require __DIR__ . '/auth.php';
