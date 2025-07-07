<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Channel;
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
        $images = glob(public_path('images/albums/*'));

        if (empty($images)) {
            throw new \Exception('No hay imágenes en la carpeta public/albums.');
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
            'status' => 'published',
            'featured' => fake()->boolean(),
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => function (array $attributes) {
                $user = User::find($attributes['user_id']);
                return $user->hasRole('member') ? null : Category::inRandomOrder()->first()->id;
            },
            'channel_id' => Channel::inRandomOrder()->first()->id,
            'published_at' => now()
        ];
    }
}
