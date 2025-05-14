<?php

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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
            'manage_categories',
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
            'feature_posts',
            'edit_any_post',
        ]);

        // rol admin
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial_roles_and_permissions');
    }
};
