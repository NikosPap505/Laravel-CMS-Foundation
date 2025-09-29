<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\MenuItem;

class ViewServiceProvider extends ServiceProvider
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
        // Χρησιμοποιούμε έναν View Composer για να μοιραστούμε δεδομένα με το public layout.
        View::composer('layouts.public', function ($view) {
            // Παίρνουμε τα στοιχεία του μενού για το header, ταξινομημένα κατά 'order'.
            $headerMenuItems = MenuItem::where('location', 'header')->orderBy('order', 'asc')->get();
            
            // Παίρνουμε τα στοιχεία του μενού για το footer, ταξινομημένα κατά 'order'.
            $footerMenuItems = MenuItem::where('location', 'footer')->orderBy('order', 'asc')->get();

            // Μοιραζόμαστε και τις δύο μεταβλητές με το view.
            $view->with('headerMenuItems', $headerMenuItems)
                 ->with('footerMenuItems', $footerMenuItems);
        });
    }
}