<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
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
            'create_posts',
            'edit_own_posts',
            'delete_own_posts',

            // staff
            'feature_posts',
            'edit_any_post',

            // admin
            'delete_any_post',
            'manage_categories'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // rol member (autenticado básico)
        $member = Role::firstOrCreate(['name' => 'member', 'guard_name' => 'web']);
        $member->givePermissionTo([
            'view_posts',
            'view_featured_posts',
            'comment_posts',
            'like_posts',
            'create_posts',
            'edit_own_posts',
            'delete_own_posts'
        ]);

        // rol staff
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->givePermissionTo([
            ...$member->permissions->pluck('name')->toArray(),
            'edit_any_post',
        ]);

        // rol admin
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());
    }
}
