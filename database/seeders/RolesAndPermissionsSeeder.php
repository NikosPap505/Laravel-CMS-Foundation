<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions (using firstOrCreate to avoid duplicates)
        $permissions = [
            'manage pages',
            'manage menus',
            'manage posts',
            'manage categories',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create the 'editor' role and assign permissions
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editorRole->syncPermissions(['manage posts', 'manage categories', 'manage pages']);

        // create the 'admin' role and assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());
        
        $this->command->info('Roles and permissions created/updated successfully!');
        $this->command->info('Admin role has ' . $adminRole->permissions->count() . ' permissions.');
    }
}