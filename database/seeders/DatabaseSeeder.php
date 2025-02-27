<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $authorRole = Role::firstOrCreate(['name' => 'author']);

        $user = User::factory()->create([
            'name' => 'eduardo',
            'email' => 'eduardo@mail.com',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole($authorRole);
    }
}
