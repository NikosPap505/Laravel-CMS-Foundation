<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Laravel', 'color' => '#FF2D20', 'description' => 'Laravel framework related content'],
            ['name' => 'PHP', 'color' => '#777BB4', 'description' => 'PHP programming language'],
            ['name' => 'Web Development', 'color' => '#61DAFB', 'description' => 'General web development topics'],
            ['name' => 'Tutorial', 'color' => '#4CAF50', 'description' => 'Step-by-step tutorials'],
            ['name' => 'Tips', 'color' => '#FF9800', 'description' => 'Quick tips and tricks'],
            ['name' => 'News', 'color' => '#2196F3', 'description' => 'Latest news and updates'],
            ['name' => 'JavaScript', 'color' => '#F7DF1E', 'description' => 'JavaScript programming'],
            ['name' => 'CSS', 'color' => '#1572B6', 'description' => 'CSS styling and design'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag['name']],
                $tag
            );
        }
    }
}