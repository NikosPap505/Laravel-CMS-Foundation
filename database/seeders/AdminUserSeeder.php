<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        
        // Create admin user if it doesn't exist
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );
        
        // Assign admin role if not already assigned
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }
        
        $this->command->info('Admin user created/updated successfully!');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: password123');
        $this->command->warn('Please change the password after first login!');
    }
}