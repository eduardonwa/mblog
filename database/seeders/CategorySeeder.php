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
        Category::create([
            'name' => 'Entries',
            'slug' => 'entries',
            'description' => 'Thoughts, notes & deeper takes. Things I\'ve been thinking about in relation to metal, culture and the digital world.',
            'parent_id' => null,
        ]);

        $metall = Category::create([
            'name' => 'MetAll',
            'slug' => 'metall',
            'description' => 'Commercial and cultural metal releases: music, movies, music gear, etc. Thoughts about the new stuff.',
            'parent_id' => null,
        ]);

        foreach (['Album Reviews', 'Highlights', 'Other Stuff'] as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'parent_id' => $metall->id,
            ]);
        }
    }
}
