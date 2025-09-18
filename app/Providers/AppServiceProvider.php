<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AblyService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AblyService::class, function ($app) {
            return new AblyService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
