<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileName = fake()->word() . '.jpg';
        $filePath = 'media/' . fake()->uuid() . '.jpg';
        
        return [
            'name' => $fileName,
            'file_name' => fake()->uuid() . '.jpg',
            'mime_type' => 'image/jpeg',
            'path' => $filePath,
            'size' => fake()->numberBetween(1000, 10000000), // 1KB to 10MB
            'alt_text' => fake()->sentence(3),
            'caption' => fake()->sentence(),
        ];
    }

    /**
     * Create a media item for an image
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'mime_type' => 'image/jpeg',
            'name' => fake()->word() . '.jpg',
        ]);
    }

    /**
     * Create a media item for a PDF
     */
    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'mime_type' => 'application/pdf',
            'name' => fake()->word() . '.pdf',
        ]);
    }
}