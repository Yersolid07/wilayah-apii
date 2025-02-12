<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        // Load API routes
        Route::prefix('api') // Prefix untuk API routes
            ->middleware('api') // Middleware untuk API
            ->group(base_path('routes/api.php')); // File API routes

        // Load web routes
        Route::middleware('web') // Middleware untuk web routes
            ->group(base_path('routes/web.php')); // File web routes
    }
}