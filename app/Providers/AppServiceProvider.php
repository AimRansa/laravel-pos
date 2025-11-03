<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Hapus atau matikan kode yang memanggil Setting
        // karena kita sekarang gunakan tabel laporan, bukan settings lagi
        /*
        if (! $this->app->runningInConsole()) {
            $settings = Setting::all('key', 'value')
                ->keyBy('key')
                ->transform(function ($setting) {
                    return $setting->value;
                })
                ->toArray();

            config(['settings' => $settings]);
            config(['app.name' => config('settings.app_name')]);
        }
        */

        Paginator::useBootstrap();
    }
}
