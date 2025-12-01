<?php

use App\Livewire\Home;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Public home page
Route::get('/', Home::class)->name('home');

// Public booking search (no auth required)
Route::get('/booking/search', \App\Livewire\Visitor\Booking\Search::class)->name('booking.search');

// Public hotel rooms page (no auth required)
Route::get('/booking/hotel/{hotel}/rooms', \App\Livewire\Visitor\Booking\HotelRooms::class)->name('booking.hotel.rooms');

// Public room details (no auth required)
Route::get('/booking/room/{room}', \App\Livewire\Visitor\Booking\RoomDetails::class)->name('booking.room.details');

// Guest (Customer) Routes
Route::middleware(['auth:web', 'verified'])->group(function () {
    // Guest Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::view('profile', 'profile')->name('profile');

    // Hotel Booking Routes (Auth Required)
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/create/{room}', \App\Livewire\Visitor\Booking\Create::class)->name('create');
        Route::get('/confirmation/{booking}', \App\Livewire\Visitor\Booking\Confirmation::class)->name('confirmation');
    });

    // My Bookings
    Route::get('/my-bookings', \App\Livewire\Visitor\Booking\MyBookings::class)->name('my-bookings');
});

// Staff Routes (using staff guard)
Route::middleware(['auth:staff'])->group(function () {
    // Main staff dashboard - redirects to role-specific dashboard
    Route::get('/staff/dashboard', function () {
        return redirect()->route(auth('staff')->user()->getDashboardRoute());
    })->name('staff.dashboard');

    // Hotel Manager Routes
    Route::middleware('role:hotel_manager')->prefix('hotel')->name('hotel.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Hotel\Dashboard::class)->name('dashboard');

        // Hotel Management (Specific Hotel)
        Route::get('/{hotel}/manage', \App\Livewire\Hotel\Manage::class)->name('manage');

        // Room Management
        Route::get('/rooms', \App\Livewire\Hotel\Rooms\Index::class)->name('rooms.index');
        Route::get('/rooms/create', \App\Livewire\Hotel\Rooms\Create::class)->name('rooms.create');
        Route::get('/rooms/{room}/edit', \App\Livewire\Hotel\Rooms\Edit::class)->name('rooms.edit');

        // Room Views Management
        Route::get('/views', \App\Livewire\Hotel\Views\Manage::class)->name('views.manage');

        // Amenity Management
        Route::get('/amenities', \App\Livewire\Hotel\Amenities\Manage::class)->name('amenities.manage');
        Route::get('/amenities/categories', \App\Livewire\Hotel\Amenities\Categories::class)->name('amenities.categories');
        Route::get('/amenities/items', \App\Livewire\Hotel\Amenities\Items::class)->name('amenities.items');

        // Policy Management
        Route::get('/policies', \App\Livewire\Hotel\Policies\Manage::class)->name('policies.manage');

        // Pricing Management
        Route::get('/pricing', \App\Livewire\Hotel\Pricing\Manage::class)->name('pricing.manage');

        // Room Images Management
        Route::get('/images', \App\Livewire\Hotel\Images\Manage::class)->name('images.manage');

        // Daily Operations
        Route::get('/operations', \App\Livewire\Hotel\Operations\Dashboard::class)->name('operations.dashboard');

        // Booking Management
        Route::get('/bookings', \App\Livewire\Hotel\Bookings\Index::class)->name('bookings.index');
        Route::get('/bookings/{booking}', \App\Livewire\Hotel\Bookings\Show::class)->name('bookings.show');

        // Room Availability Management (Coming Soon)
        // Route::get('/availability', \App\Livewire\Hotel\Availability\Manage::class)->name('availability.manage');

        // Reports (Coming Soon)
        // Route::get('/reports/occupancy', \App\Livewire\Hotel\Reports\Occupancy::class)->name('reports.occupancy');
        // Route::get('/reports/revenue', \App\Livewire\Hotel\Reports\Revenue::class)->name('reports.revenue');
        // Route::get('/reports/bookings', \App\Livewire\Hotel\Reports\BookingHistory::class)->name('reports.bookings');
    });

    // Ferry Operator Dashboard
    Route::get('/ferry/dashboard', function () {
        return view('dashboard');
    })->middleware('role:ferry_operator')->name('ferry.dashboard');

    // Theme Park Staff Dashboard
    Route::get('/theme-park/dashboard', function () {
        return view('dashboard');
    })->middleware('role:theme_park_staff')->name('theme-park.dashboard');

    // Beach Staff Dashboard
    Route::get('/beach/dashboard', function () {
        return view('dashboard');
    })->middleware('role:beach_staff')->name('beach.dashboard');

    // Administrator Routes
    Route::middleware('role:administrator')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/staff', \App\Livewire\Admin\Staff\Index::class)->name('staff.index');
        Route::get('/hotels', \App\Livewire\Admin\Hotels\Index::class)->name('hotels.index');
        Route::get('/theme-park', \App\Livewire\Admin\ThemePark\Zones::class)->name('theme-park');
        Route::get('/beach/services', \App\Livewire\Admin\Beach\Services::class)->name('beach.services');

        // Ferry Management
        Route::get('ferry', App\Livewire\Admin\Ferry\Index::class)->name('ferry.index');
    });

    // Component Library Demo - Accessible to all staff
    Route::get('/components-demo', \App\Livewire\Admin\ComponentsDemo::class)->name('components-demo');

    // Profile for staff
    Route::view('/staff/profile', 'profile')->name('staff.profile');
});

require __DIR__ . '/auth.php';
