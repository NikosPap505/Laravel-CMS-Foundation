<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class ResetPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all permissions and roles (fixes 403 errors)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Resetting permissions...');
        
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create permissions
        $permissions = [
            'manage pages',
            'manage menus',
            'manage posts',
            'manage categories',
            'manage users',
        ];
        
        $this->info('Creating permissions...');
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->line("  ✓ {$permission}");
        }
        
        // Create/update admin role
        $this->info('Setting up admin role...');
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());
        $this->line("  ✓ Admin role has " . $adminRole->permissions->count() . " permissions");
        
        // Create/update editor role
        $this->info('Setting up editor role...');
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editorRole->syncPermissions(['manage posts', 'manage categories', 'manage pages']);
        $this->line("  ✓ Editor role has " . $editorRole->permissions->count() . " permissions");
        
        // Ensure admin user has admin role
        $adminUser = User::where('email', config('app.admin_email', 'admin@example.com'))->first();
        if ($adminUser) {
            if (!$adminUser->hasRole('admin')) {
                $adminUser->assignRole('admin');
            }
            $this->info('Admin user verified');
            $this->line("  ✓ {$adminUser->email}");
        }
        
        // Clear all sessions to force re-login
        $this->info('Clearing sessions...');
        \Illuminate\Support\Facades\DB::table('sessions')->truncate();
        $this->line("  ✓ All users must login again");
        
        // Clear caches
        $this->call('cache:clear');
        $this->call('permission:cache-reset');
        
        $this->newLine();
        $this->info('✓ Permissions reset successfully!');
        $this->warn('Important: You must logout and login again to load new permissions.');
        
        return Command::SUCCESS;
    }
}
