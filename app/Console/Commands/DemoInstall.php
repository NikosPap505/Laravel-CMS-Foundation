<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class DemoInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:install {--admin : Also seed the admin user using ADMIN_EMAIL and ADMIN_PASSWORD from .env}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare the database and seed rich demo content (one-step setup)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Setting up demo environment...');

        // Ensure SQLite file exists if using sqlite
        try {
            $defaultConnection = Config::get('database.default');
            if ($defaultConnection === 'sqlite') {
                $db = Config::get('database.connections.sqlite.database');
                if ($db && $db !== ':memory:' && !str_contains($db, '://')) {
                    // Append .sqlite for bare names
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
            $this->warn('Could not pre-create sqlite database file: '.$e->getMessage());
        }

        // Run migrations
        $this->line('🧱 Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->output->write(Artisan::output());

        // Seed core demo data (DatabaseSeeder calls RolesAndPermissionsSeeder, DemoDataSeeder, ContentSeeder)
        $this->line('🌱 Seeding demo data...');
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder', '--force' => true]);
        $this->output->write(Artisan::output());

        // Optionally seed admin user
        if ($this->option('admin')) {
            $this->line('👤 Seeding admin user...');
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\AdminUserSeeder', '--force' => true]);
            $this->output->write(Artisan::output());
        }

        $this->newLine();
        $this->info('✅ Demo environment is ready!');
        $this->line('Visit the homepage to see demo pages and posts.');
        $this->line('Tip: Run with --admin after setting ADMIN_EMAIL and ADMIN_PASSWORD in .env to create an admin login.');

        return Command::SUCCESS;
    }
}
