<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Ensure admin role has all permissions
        $allPermissions = Permission::all();
        if ($allPermissions->count() > 0) {
            $adminRole->syncPermissions($allPermissions);
            $this->command->info('Admin role updated with ' . $allPermissions->count() . ' permissions.');
        }
        
        // Create admin user if it doesn't exist
        $adminEmail = config('app.admin_email');
        $adminPassword = config('app.admin_password');
        
        if (!$adminEmail || !$adminPassword) {
            $this->command->error('ADMIN_EMAIL and ADMIN_PASSWORD must be set in your .env file!');
            $this->command->error('Add these lines to your .env file:');
            $this->command->error('ADMIN_EMAIL=your-admin@yourdomain.com');
            $this->command->error('ADMIN_PASSWORD=your-secure-password');
            return;
        }
        
        $adminUser = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin User',
                'password' => Hash::make($adminPassword),
            ]
        );
        
        // Assign admin role if not already assigned
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }
        
        $this->command->info('Admin user created/updated successfully!');
        $this->command->info('Email: ' . $adminEmail);
        $this->command->info('Password: [HIDDEN - Set in .env file]');
        $this->command->warn('Please change the password after first login!');
    }
}