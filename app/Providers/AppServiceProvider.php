<?php

namespace App\Providers;

use App\Models\Hotel;
use App\Observers\HotelObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Hotel observer
        Hotel::observe(HotelObserver::class);
    }
}
