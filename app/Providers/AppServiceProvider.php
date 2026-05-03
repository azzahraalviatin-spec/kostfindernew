<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Booking;
use App\Observers\BookingObserver;

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
        \Illuminate\Pagination\Paginator::useBootstrapFive();
        Booking::observe(BookingObserver::class);
        
        // Membagikan settings ke semua view
        \Illuminate\Support\Facades\View::share('siteSettings', \App\Models\Setting::first());
    }
}
