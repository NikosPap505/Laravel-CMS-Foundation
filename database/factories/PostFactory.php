<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(rand(4, 8));
        return [
            'category_id' => \App\Models\Category::factory(),
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'excerpt' => $this->faker->paragraph(2),
            'body' => '<p>' . implode('</p><p>', $this->faker->paragraphs(10)) . '</p>',
            'featured_image' => 'posts/placeholder.jpg', // Θα χρησιμοποιούμε μια εικόνα placeholder
        ];
    }
}
