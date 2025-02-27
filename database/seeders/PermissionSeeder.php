<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // crear permisos
        $createPost = Permission::create(['name' => 'create_posts']);
        $editPost = Permission::create(['name' => 'edit_posts']);
        $deletePost = Permission::create(['name' => 'delete_posts']);
            // asignar permisos a roles
        $authorRole = Role::where('name', 'author')->first();
        $authorRole->givePermissionTo([$createPost, $editPost, $deletePost]);
    }
}
