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
        $permissions = [
            // básicos (todos los autenticados)
            'view_posts',
            'view_featured_posts',
            'comment_posts',
            'like_posts',

            // creadores
            'create_posts',
            'edit_own_posts',
            'delete_own_posts',

            // staff
            'manage_categories',
            'manage_creator_categories',
            'feature_posts',
            'edit_any_post',

            // admin
            'delete_any_post'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }
}
