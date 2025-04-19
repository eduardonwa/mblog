<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ManageRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:manage
                            {action : create-role|create-permission|assign-permission|delete-role|delete-permission|list-roles|list-permissions|show-permissions}
                            {name? : Name of role/permission}
                            {--permissions=* : Permission to assign (space separated)}
                            {--force : Force delete (no confirmation prompt)}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage roles and permissions (create, assign)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $name = $this->argument('name');

        if (in_array($action, ['create-role', 'create-permission', 'assign-permission', 'delete-role', 'delete-permission', 'show-permissions']) && empty($name)) {
            $this->error('The name argument is required for this action');
            return;
        }

        switch ($action) {
            case 'create-role':
                $this->createRole($name);
                break;
            case 'create-permission':
                $this->createPermission($name);
                break;
            case 'assign-permission':
                $this->assignPermissionsToRole($name);
                break;
            case 'delete-role':
                $this->deleteRole($name);
                break;
            case 'delete-permission':
                $this->deletePermission($name);
                break;
            case 'list-roles':
                return $this->listRoles();
            case 'list-permissions':
                return $this->listPermissions();
            case 'show-permissions':
                return $this->showRolePermissions($this->argument('name'));
            default:
                $this->error(
                    'Invalid action. Available actions: create-role, create-permission, assign-permission, delete-role or delete-permission 
                ');
        }
    }

    private function createRole(string $name): void
    {
        Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        $this->info("✅ Role '$name' created/exists.");
    }

    private function createPermission(string $name): void
    {
        Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        $this->info("✅ Permission '$name' created/exists.");
    }

    private function assignPermissionsToRole(string $roleName): void
    {
        $permissions = $this->option('permissions');

        if (empty($permissions)) {
            $this->error('Please specify at least one action with --permissions="permission1 permission2"');
            return;
        }

        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error("<bg=#8B0000;fg=white> ❌ Role '$roleName' doesn't exists. You should first create it. </>");
            return;
        }

        // Crear permisos si no existen y asignarlos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $role->syncPermissions($permissions);
        $this->info("✅ Permissions assigned to role '$roleName': " . implode(', ', $permissions));
    }

    private function deleteRole(string $name): void
    {
        if (!$this->option('force') && !$this->confirm("⚠️ Are you sure you want to delete '$name' role? This change is irreversible.")) {
            return;
        }

        $role = Role::where('name', $name)->first();

        if (!$role) {
            $this->error("<bg=#8B0000;fg=white> ❌ Role '$name' does not exists. </>");
            return;
        }

        $role->delete();
        $this->info("✅ Role '$name' deleted.");
    }

    private function deletePermission(string $name): void
    {
        if (!$this->option('force') && !$this->confirm("⚠️ Are you sure you want to delete '$name' permission? This will affect all roles that depend on this permission.")) {
            return;
        }

        $permission = Permission::where('name', $name)->first();

        if (!$permission) {
            $this->error("<bg=#8B0000;fg=white> ❌ Permission '$name' does not exists. </>");
            return;
        }

        $permission->delete();
        $this->info("✅ Permission '$name' deleted.");
    }

    private function showRolePermissions(string $roleName): void
    {
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error("<bg=#8B0000;fg=white> ❌ Role '$roleName' does not exists. </>");
            return;
        }

        $this->table(
            ["Role permissions '$roleName'"], 
            $role->permissions->pluck('name')->map(fn ($name) => [$name])->toArray()
        );
    }

    private function listRoles(): void
    {
        $roles = Role::all()->pluck('name');
        $this->table(['Roles'], $roles->map(fn($r) => [$r])->toArray());
    }

    private function listPermissions(): void
    {
        $permissions = Permission::all()->pluck('name');
        $this->table(['Permissions'], $permissions->map(fn($p) => [$p])->toArray());
    }
}
