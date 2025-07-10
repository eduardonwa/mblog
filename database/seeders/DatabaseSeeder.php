<?php

namespace Database\Seeders;

use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Channel;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ChannelSeeder;
use Database\Seeders\CategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            ChannelSeeder::class,
        ]);

        // Crear 1 admin
        $adminUser = User::factory()->create([
            'username' => 'eduardo',
            'email' => 'admin@sickofmetal.net',
            'password' => bcrypt('password'),
            'link' => 'https://sickofmetal.net',
            'bio' => 'I wanted to redesign my old blog but created this community project instead. Hope you like it!',
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
        
        // Crear cuenta tipo "member" con un slug repetido 
        User::factory()
            ->create([
                'username' => User::generateUniqueUsername('eduardo'),
                'email' => 'eduardo@mail.com',
                'password' => bcrypt('pass123456')
            ])->assignRole('member');

        // Crear 1 post featured para el admin        
        Post::factory()
            ->count(1)
            ->create([
                'user_id' => $adminUser->id,
                'featured' => true,
                'status' => 'published',
                'channel_id' => null,
            ]);

        // Crear 1 post por staff
        $staffUsers->each(function($staff) {
            Post::factory()
                ->count(2)
                ->create([
                    'user_id' => $staff->id,
                    'featured' => false,
                    'channel_id' => null,
                ]);
        });

        $channels = Channel::all();

        // Crear posts asignados a "members" aleatorios
        Post::factory()
            ->count(20)
            ->state([
                'user_id' => fn() => $member->random()->id,
                'channel_id' => fn() => $channels->random()->id,
                'featured' => false,
                'status' => 'published'
            ])
            ->create();
    }
}
