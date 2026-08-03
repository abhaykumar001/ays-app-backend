<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Mobile-only roles — dashboard is admin-only.
        // These roles identify user type on the API side.
        $roleNames = ['Client', 'Agent', 'Owner'];

        foreach ($roleNames as $name) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // All view_* permissions except view_dashboard (dashboard is admin-only)
        $viewPermissions = Permission::where('name', 'like', 'view_%')
            ->where('name', '!=', 'view_dashboard')
            ->get();

        foreach (['Client', 'Agent', 'Owner'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            // givePermissionTo skips already-assigned ones — safe to run multiple times
            $role->givePermissionTo($viewPermissions);
        }

        $this->command->info('Roles seeded and view permissions assigned: ' . implode(', ', $roleNames));
    }
}
