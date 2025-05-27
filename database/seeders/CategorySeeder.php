<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // categorías principales
        $metall = Category::create([
            'name' => 'MetAll',
            'slug' => 'metal',
            'description' => 'Music, movies, reviews, gear... anything related to metal.',
            'parent_id' => null,
        ]);

        foreach (['Album Reviews', 'Old Album Reviews', 'Gearheads'] as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'parent_id' => $metall->id,
            ]);
        }
    }
}
