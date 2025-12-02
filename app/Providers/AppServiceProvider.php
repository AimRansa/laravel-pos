<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        // Fix default varchar length for MySQL older versions
        Schema::defaultStringLength(191);

        // Use Bootstrap style for pagination
        Paginator::useBootstrap();

        // Set timezone to Indonesia
        date_default_timezone_set('Asia/Jakarta');
        config(['app.timezone' => 'Asia/Jakarta']);
        
        // Optional: Carbon bahasa Indonesia
        Carbon::setLocale('id');
    }
}
