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

// Public Ferry Tickets Browse (no auth required)
Route::get('/ferry-tickets/browse', \App\Livewire\Visitor\FerryTickets\Browse::class)->name('ferry-tickets.browse');

// Public Beach Activities Browse (no auth required)
Route::get('/beach-activities', \App\Livewire\Visitor\BeachActivities\Browse::class)->name('visitor.beach-activities.browse');

// Public Beach Activity Service Details (no auth required)
Route::get('/beach-activities/service/{service}', \App\Livewire\Visitor\BeachActivities\ServiceDetails::class)->name('visitor.beach-activities.details');

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

    // Ferry Ticket Routes (Auth Required)
    Route::prefix('ferry-tickets')->name('ferry-tickets.')->group(function () {
        Route::get('/create/{schedule}', \App\Livewire\Visitor\FerryTickets\Create::class)->name('create');
        Route::get('/confirmation/{ticket}', \App\Livewire\Visitor\FerryTickets\Confirmation::class)->name('confirmation');
        Route::get('/my-tickets', \App\Livewire\Visitor\FerryTickets\MyTickets::class)->name('my-tickets');
        Route::get('/tickets/{ticket}', \App\Livewire\Visitor\FerryTickets\Show::class)->name('show');
    });

    // Beach Activities Routes (Auth Required)
    Route::prefix('beach-activities')->name('visitor.beach-activities.')->group(function () {
        Route::get('/create', \App\Livewire\Visitor\BeachActivities\Create::class)->name('create');
        Route::get('/confirmation/{booking}', \App\Livewire\Visitor\BeachActivities\Confirmation::class)->name('confirmation');
        Route::get('/my-bookings', \App\Livewire\Visitor\BeachActivities\MyBookings::class)->name('my-bookings');
    });

    // Theme Park Visitor Routes (Auth Required + Must be Checked In)
    Route::prefix('my-theme-park')->name('visitor.theme-park.')->group(function () {
        Route::get('/wallet', \App\Livewire\Visitor\ThemePark\Wallet::class)->middleware('checked_in')->name('wallet');
        Route::get('/activities', \App\Livewire\Visitor\ThemePark\Activities::class)->middleware('checked_in')->name('activities');
        Route::get('/redemptions', \App\Livewire\Visitor\ThemePark\RedemptionHistory::class)->middleware('checked_in')->name('redemptions');
    });
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

    // Ferry Operator Routes
    Route::middleware('role:ferry_operator')->prefix('ferry')->name('ferry.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Ferry\Dashboard::class)->name('dashboard');
        Route::get('/routes', \App\Livewire\Ferry\Routes\Index::class)->name('routes.index');
        Route::get('/schedules', \App\Livewire\Ferry\Schedules\Index::class)->name('schedules.index');
        Route::get('/tickets/validate', \App\Livewire\Ferry\Tickets\Validate::class)->name('tickets.validate');
        Route::get('/tickets/passengers', \App\Livewire\Ferry\Tickets\PassengerList::class)->name('tickets.passengers');
    });

    // Theme Park Management (Manager & Staff)
    Route::middleware('role:theme_park_manager,theme_park_staff')->prefix('theme-park')->name('theme-park.')->group(function () {
        Route::get('/dashboard', \App\Livewire\ThemePark\Dashboard::class)->name('dashboard');

        // Manager and Staff can manage activities (but with different permissions)
        Route::get('/activities', \App\Livewire\ThemePark\Activities\Index::class)->name('activities.index');

        // Staff-only routes
        Route::middleware('role:theme_park_staff')->group(function () {
            Route::get('/schedules', \App\Livewire\ThemePark\Schedules::class)->name('schedules');
        });

        // Manager-only routes
        Route::middleware('role:theme_park_manager')->group(function () {
            Route::get('/zones', \App\Livewire\ThemePark\Zones::class)->name('zones');
            Route::get('/settings', \App\Livewire\ThemePark\Settings::class)->name('settings');
        });

        // Staff can validate tickets
        Route::get('/validate', \App\Livewire\ThemePark\Validate::class)->name('validate');
    });

    // Beach Staff Routes
    Route::middleware('role:beach_staff')->prefix('beach')->name('beach.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Beach\Dashboard::class)->name('dashboard');
        Route::get('/service-settings', \App\Livewire\Beach\ServiceSettings::class)->name('service-settings');
        Route::get('/validate', \App\Livewire\Beach\Validate::class)->name('validate');
        Route::get('/history', \App\Livewire\Beach\BookingHistory::class)->name('history');
        Route::get('/bookings', \App\Livewire\Beach\BookingsCalendar::class)->name('bookings');
    });

    // Administrator Routes
    Route::middleware('role:administrator')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/staff', \App\Livewire\Admin\Staff\Index::class)->name('staff.index');
        Route::get('/hotels', \App\Livewire\Admin\Hotels\Index::class)->name('hotels.index');

        Route::get('/beach/categories', \App\Livewire\Admin\Beach\Categories::class)->name('beach.categories');
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
