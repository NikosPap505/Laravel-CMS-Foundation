<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
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
        // Ensure SQLite database file exists and is correctly named before sessions start
        try {
            $defaultConnection = Config::get('database.default');
            if ($defaultConnection === 'sqlite') {
                $db = Config::get('database.connections.sqlite.database');

                if ($db && $db !== ':memory:' && !str_contains($db, '://')) {
                    // If no extension and no directory separators (bare name), append .sqlite
                    if (pathinfo($db, PATHINFO_EXTENSION) === '' && !str_contains($db, DIRECTORY_SEPARATOR)) {
                        $db .= '.sqlite';
                        Config::set('database.connections.sqlite.database', $db);
                    }

                    $dir = dirname($db);
                    if (!File::isDirectory($dir)) {
                        File::makeDirectory($dir, 0755, true);
                    }

                    if (!File::exists($db)) {
                        File::put($db, '');
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently ignore to avoid breaking bootstrap in unusual environments
        }

        // If using database sessions but the sessions table doesn't exist yet,
        // fall back to file-based sessions to avoid runtime errors on fresh setups.
        try {
            if (Config::get('session.driver') === 'database') {
                $connection = Config::get('session.connection') ?: Config::get('database.default');
                $table = Config::get('session.table', 'sessions');

                if (! Schema::connection($connection)->hasTable($table)) {
                    Config::set('session.driver', 'file');
                }
            }
        } catch (\Throwable $e) {
            // Ignore any schema/connection issues during bootstrap and keep defaults
        }

        View::composer('layouts.public', function ($view) {
            $headerMenuItems = MenuItem::where('show_in_header', true)->orderBy('order', 'asc')->get();
            $footerMenuItems = MenuItem::where('show_in_footer', true)->orderBy('order', 'asc')->get();

            $view->with('headerMenuItems', $headerMenuItems)
                ->with('footerMenuItems', $footerMenuItems);
        });
    }
}
