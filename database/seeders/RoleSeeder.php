<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Rename the legacy 'Agent' role to 'Internal Agent' in place, so
        // existing user role assignments carry over unchanged. Safe to run
        // repeatedly (and on already-migrated databases) — once renamed,
        // 'Agent' no longer exists and this is a no-op on later runs.
        $legacyAgent = Role::where('name', 'Agent')->where('guard_name', 'web')->first();
        if ($legacyAgent) {
            $legacyAgent->update(['name' => 'Internal Agent']);
        }

        // Mobile-only roles — dashboard is admin-only.
        // These roles identify user type on the API side.
        // 'External Agent' (broker) and 'External Agency' currently get the
        // same viewing access as 'Internal Agent' — permissions may diverge
        // later (External Agency intentionally mirrors External Agent for
        // now, per explicit product decision).
        $roleNames = ['Client', 'Internal Agent', 'External Agent', 'External Agency', 'Owner'];

        foreach ($roleNames as $name) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // All view_* permissions except view_dashboard (dashboard is admin-only)
        $viewPermissions = Permission::where('name', 'like', 'view_%')
            ->where('name', '!=', 'view_dashboard')
            ->get();

        foreach ($roleNames as $roleName) {
            $role = Role::where('name', $roleName)->first();
            // givePermissionTo skips already-assigned ones — safe to run multiple times
            $role->givePermissionTo($viewPermissions);
        }

        $this->command->info('Roles seeded and view permissions assigned: ' . implode(', ', $roleNames));
    }
}
