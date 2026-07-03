<?php

namespace App\Providers;

use App\Models\Approval;
use App\Models\Booking;
use App\Observers\ApprovalObserver;
use App\Observers\BookingObserver;
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
        Booking::observe(BookingObserver::class);
        Approval::observe(ApprovalObserver::class);
    }
}
