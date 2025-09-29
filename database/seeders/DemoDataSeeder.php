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
        // Create 5 Categories
        $categories = \App\Models\Category::factory(5)->create();

        // Create 25 Posts, assigning a random category to each
        \App\Models\Post::factory(25)->recycle($categories)->create();

        // Create some standard pages
        \App\Models\Page::factory()->create([
            'title' => 'Home Page',
            'slug' => 'home',
            'content' => '<h1>Welcome to our Website</h1><p>This is the homepage content.</p>',
            'order' => 1,
        ]);
        \App\Models\Page::factory()->create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'order' => 2,
        ]);
        \App\Models\Page::factory()->create([
            'title' => 'Our Services',
            'slug' => 'services',
            'order' => 3,
        ]);
    }
}