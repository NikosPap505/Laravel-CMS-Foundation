<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:assign-admin {email : The email of the user to make admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign admin role to a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Find the user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }
        
        // Check if admin role exists, create if not
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Check if user already has admin role
        if ($user->hasRole('admin')) {
            $this->info("User '{$email}' already has admin role.");
            return 0;
        }
        
        // Assign admin role
        $user->assignRole('admin');
        
        $this->info("Admin role assigned to user '{$email}' successfully!");
        
        return 0;
    }
}