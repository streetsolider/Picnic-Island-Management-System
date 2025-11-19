<?php

use App\Livewire\Home;
use Illuminate\Support\Facades\Route;

// Public home page
Route::get('/', Home::class)->name('home');

// Role-based dashboard routing
Route::middleware(['auth', 'verified'])->group(function () {
    // Main dashboard - redirects to role-specific dashboard
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->getDashboardRoute());
    })->name('dashboard');

    // Visitor Dashboard
    Route::get('/visitor/dashboard', function () {
        return view('dashboard');
    })->middleware('role:visitor')->name('visitor.dashboard');

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

    // Administrator Dashboard
    Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)
        ->middleware('role:administrator')
        ->name('admin.dashboard');

    // Administrator - User Management
    Route::get('/admin/users', \App\Livewire\Admin\Users\Index::class)
        ->middleware('role:administrator')
        ->name('admin.users.index');

    // Profile
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__.'/auth.php';
