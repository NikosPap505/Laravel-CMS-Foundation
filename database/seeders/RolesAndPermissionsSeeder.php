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

        // create permissions
        Permission::create(['name' => 'manage pages']);
        Permission::create(['name' => 'manage menus']);
        Permission::create(['name' => 'manage posts']);
        Permission::create(['name' => 'manage categories']);
        Permission::create(['name' => 'manage users']);

        // create the 'editor' role and assign permissions
        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo(['manage posts', 'manage categories', 'manage pages']);

        // create the 'admin' role and assign all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
    }
}