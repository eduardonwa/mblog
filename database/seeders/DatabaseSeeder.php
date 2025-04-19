<?php

namespace Database\Seeders;

use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Spatie\Permission\Models\Role;
use Database\Seeders\InterestSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // En tu DatabaseSeeder
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class
        ]);
        
        // Crear categorías primero
        Category::factory()->count(3)->create();

        // Crear 1 admin
        $adminUser = User::factory()->create([
            'name' => 'eduardo',
            'email' => 'admin@sickofmetal.net',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        // Crear 2 staff (como mencionaste)
        $staffUsers = User::factory()
            ->count(2)
            ->create()
            ->each(fn($user) => $user->assignRole('staff'));

        // Crear miembros con el rol kreator
        $kreators = User::factory()
            ->count(5)
            ->create()
            ->each(fn($user) => $user->assignRole('kreator'));

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
