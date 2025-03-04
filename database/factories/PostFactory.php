<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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
        $images = glob(public_path('images/seeder/*'));

        if (empty($images)) {
            throw new \Exception('No hay imágenes en la carpeta public/images/seeder.');
        }

        return [
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->word(),
            'extract' => implode(' ', fake()->sentences(3)),
            'body' => implode('\n\n', fake()->paragraphs(3)),
            'thumbnail' => $images[array_rand($images)], 
            'language' => fake()->languageCode(),
            'meta_title' => fake()->sentence(),
            'meta_description' => implode(' ', fake()->sentences(2)),
            'status' => fake()->boolean() ? 'published' : 'draft',
            'featured' => fake()->boolean(),
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
