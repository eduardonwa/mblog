<?php

namespace Database\Seeders;

use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ChannelSeeder;
use Database\Seeders\CategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // En tu DatabaseSeeder
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            ChannelSeeder::class,
        ]);

        // Crear 1 admin
        $adminUser = User::factory()->create([
            'name' => 'eduardo',
            'email' => 'admin@sickofmetal.net',
            'slug' => 'eduardo',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        // Crear 2 usuarios staff
        $staffUsers = User::factory()
            ->count(2)
            ->create()
            ->each(fn($user) => $user->assignRole('staff'));

        // Crear 5 miembros con el rol member
        $member = User::factory()
            ->count(5)
            ->create()
            ->each(fn($user) => $user->assignRole('member'));
        
        // Crear cuenta con member
        User::factory()
            ->create([
                'name' => 'eduardo',
                'email' => 'eduardo@mail.com',
                'slug' => User::generateSlug('eduardo'),
                'password' => bcrypt('pass123456')
            ])->assignRole('member');

        // Crear 1 post featured para el admin        
        Post::factory()
            ->count(1)
            ->create(['user_id' => $adminUser->id, 'featured' => true, 'status' => 'published']);

        // Crear 1 post por staff
        $staffUsers->each(function($staff) {
            Post::factory()
                ->count(2)
                ->create(['user_id' => $staff->id, 'featured' => false]);
        });

        // Crear posts asignados a "members" aleatorios
        Post::factory()
            ->count(20)
            ->state([
                'user_id' => fn() => $member->random()->id,
                'featured' => false,
                'status' => 'published'
            ])
            ->create();
    }
}
