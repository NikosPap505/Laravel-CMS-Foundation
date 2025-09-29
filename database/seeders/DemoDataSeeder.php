<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create a writer user
        $writer = User::factory()->create([
            'name' => 'Writer User',
            'email' => 'writer@example.com',
            'password' => Hash::make('password'),
        ]);
        $writer->assignRole('writer');

        // Create some categories
        $cat1 = Category::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);
        $cat2 = Category::factory()->create(['name' => 'Tutorials', 'slug' => 'tutorials']);

        // Create some posts
        Post::factory()->create([
            'category_id' => $cat1->id,
            'title' => 'Welcome to Laravel',
            'slug' => 'welcome-to-laravel',
        ]);

        Post::factory(5)->create([
            'category_id' => $cat2->id,
        ]);
    }
}