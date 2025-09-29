<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(rand(2, 4));
        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'content' => '<p>' . implode('</p><p>', $this->faker->paragraphs(15)) . '</p>',
            'order' => 0,
            'meta_title' => $title,
            'meta_description' => $this->faker->paragraph(1),
        ];
    }
}
