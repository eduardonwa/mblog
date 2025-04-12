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
        $staffRole = Role::where('name', 'is_staff')->first();
        $authorRole = Role::where('name', 'author')->first();

        $adminUser = User::factory()->create([
            'name' => 'eduardo',
            'email' => 'eduardongua@hotmail.com',
            'password' => bcrypt('password'),
        ]);

        $staffUser = User::factory()->create([
            'name' => 'staff_user',
            'email' => 'staff@mail.com',
            'password' => bcrypt('password123')
        ]);

        $adminUser->assignRole($adminRole);
        $staffUser->assignRole($staffRole);

        User::factory()
            ->count(8)
            ->create()
            ->each(function ($user) use ($authorRole) {
                $user->assignRole($authorRole);
            });

        $users = User::all();

        Category::factory()->count(3)->create();        
        
        // 1. crear 15 posts del staff
        Post::factory()
            ->count(5)
            ->create([
                'author_id' => $staffUser->id,
                'category_id' => Category::inRandomOrder()->first()->id,
                'featured' => false
            ]);

        // 2. crear 6 featured del staff
        Post::factory()
            ->count(6)
            ->create([
                'author_id' => $staffUser->id,
                'category_id' => Category::inRandomOrder()->first()->id,
                'featured' => true
            ]);

        // 4. crear 20 posts de usuarios normales (no staff/admin)
        $authUsers = User::whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['admin', 'is_staff']);
        })->get();

        Post::factory()
            ->count(20)
            ->create()
            ->each(function ($post) use ($authUsers) {
                $post->update([
                    'author_id' => $authUsers->random()->id,
                    'category_id' => Category::inRandomOrder()->first()->id,
                    'featured' => false
                ]);
            });
    }
}
