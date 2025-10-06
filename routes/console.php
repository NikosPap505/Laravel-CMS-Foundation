<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule tasks (Laravel 11+ way)
Schedule::command('posts:publish-scheduled')->everyMinute();
Schedule::command('sitemap:generate')->daily();

// Database backups (daily at 1 AM)
Schedule::command('backup:clean')->daily()->at('01:00');
Schedule::command('backup:run')->daily()->at('01:30');

// Process queued jobs (for development)
// In production, use: php artisan queue:work as a daemon
Schedule::command('queue:work --stop-when-empty')->everyMinute();
