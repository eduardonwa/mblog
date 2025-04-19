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
        // rol member (autenticado básico)
        $member = Role::firstOrCreate(['name' => 'member', 'guard_name' => 'web']);
        $member->givePermissionTo([
            'view_posts',
            'view_featured_posts',
            'comment_posts',
            'like_posts'
        ]);

        // rol content kreator
        $kreator = Role::firstOrCreate(['name' => 'kreator', 'guard_name' => 'web']);
        $kreator->givePermissionTo([
            ...$member->permissions->pluck('name')->toArray(),
            'create_posts',
            'edit_own_posts',
            'delete_own_posts'
        ]);

        // rol staff
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->givePermissionTo([
            ...$kreator->permissions->pluck('name')->toArray(),
            'manage_creator_categories',
            'edit_any_post',
        ]);

        // rol admin
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());
    }
}
