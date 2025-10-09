<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\MenuItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\Integration\IntegrationManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.public', function ($view) {
            $headerMenuItems = MenuItem::where('show_in_header', true)->orderBy('order', 'asc')->get();
            $footerMenuItems = MenuItem::where('show_in_footer', true)->orderBy('order', 'asc')->get();

            $view->with('headerMenuItems', $headerMenuItems)
                ->with('footerMenuItems', $footerMenuItems);
        });
    }
}
