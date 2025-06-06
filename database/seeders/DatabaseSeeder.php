<?php

namespace Database\Seeders;

use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
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
        ]);

        // Crear 1 admin
        $adminUser = User::factory()->create([
            'name' => 'eduardo',
            'email' => 'admin@sickofmetal.net',
            'slug' => 'eduardo',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        // Crear 2 staff (como mencionaste)
        $staffUsers = User::factory()
            ->count(2)
            ->create()
            ->each(fn($user) => $user->assignRole('staff'));

        // Crear miembros con el rol member
        $kreators = User::factory()
            ->count(5)
            ->create()
            ->each(fn($user) => $user->assignRole('member'));
        
        // Crear cuenta con member
        User::factory()
            ->create([
                'name' => 'ecoello',
                'email' => 'eduardo@mail.com',
                'slug' => 'ecoello',
                'password' => bcrypt('pass123456')
            ]);

        // Crear posts para admin (5 normales + 3 destacados)
        Post::factory()
            ->count(5)
            ->create(['user_id' => $adminUser->id, 'featured' => false]);
        
        Post::factory()
            ->count(2)
            ->create(['user_id' => $adminUser->id, 'featured' => true, 'status' => 'published']);

        // Crear posts para cada staff (3 normales + 2 destacados por staff)
        $staffUsers->each(function($staff) {
            Post::factory()
                ->count(3)
                ->create(['user_id' => $staff->id, 'featured' => false]);
            
            Post::factory()
                ->count(2)
                ->create(['user_id' => $staff->id, 'featured' => true, 'status' => 'published']);
        });

        // Crear posts asignados a kreators aleatorios
        Post::factory()
            ->count(20)
            ->state([
                'user_id' => fn() => $kreators->random()->id,
                'featured' => false,
                'status' => 'published'
            ])
            ->create();
    }
}
