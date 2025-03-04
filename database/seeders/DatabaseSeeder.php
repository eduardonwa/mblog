<?php

namespace Database\Seeders;

use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        
        $adminRole = Role::where('name', 'admin')->first();
        $authorRole = Role::where('name', 'author')->first();

        $adminUser = User::factory()->create([
            'name' => 'eduardo',
            'email' => 'eduardongua@hotmail.com',
            'password' => bcrypt('password'),
        ]);

        $adminUser->assignRole($adminRole);

        User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) use ($authorRole) {
                $user->assignRole($authorRole);
            });

        $users = User::all();

        Category::factory()->count(3)->create();        
        
        Post::factory()
            ->count(10)
            ->create()
            ->each(function ($post) use ($users) {
                $post->update([
                    'user_id' => $users->random()->id,
                    'category_id' => Category::inRandomOrder()->first()->id,
                ]);
        });
    }
}
