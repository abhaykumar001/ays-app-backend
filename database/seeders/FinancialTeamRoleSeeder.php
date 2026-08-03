<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Creates (or updates) the "Financial Team" dashboard role only.
 * Does not touch Super Admin, Client, Agent, Owner, or any other role —
 * safe to re-run any time to bring Financial Team's permission set back
 * in line with this list.
 *
 * Scope: edit pricing fields on Projects/Units, full CRUD on project-level
 * Payment Plans and Project Offers (both already separate from the
 * project/unit edit forms). No create/delete on Projects or Units
 * themselves, no access to unrelated modules (content, bookings, users, etc).
 */
class FinancialTeamRoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'Financial Team', 'guard_name' => 'web']);

        $permissionNames = [
            'view_dashboard',

            // Projects — view + pricing fields only (no create/delete, no other fields)
            'view_projects',
            'edit_project_pricing',

            // Units — view + pricing fields only (no create/delete, no other fields)
            'view_units',
            'edit_unit_pricing',

            // Project-level Payment Plans — separate CRUD, outside the project edit form
            'view_payment_plans',
            'create_payment_plans',
            'edit_payment_plans',
            'delete_payment_plans',

            // Project Offers — separate CRUD, includes per-unit price overrides
            'view_project_offers',
            'create_project_offers',
            'edit_project_offers',
            'delete_project_offers',
        ];

        $permissions = Permission::whereIn('name', $permissionNames)->where('guard_name', 'web')->get();

        $missing = collect($permissionNames)->diff($permissions->pluck('name'));
        if ($missing->isNotEmpty()) {
            $this->command->warn(
                'FinancialTeamRoleSeeder: skipping missing permissions (run PermissionSeeder + FieldPermissionSeeder first): '
                . $missing->implode(', ')
            );
        }

        // syncPermissions (not givePermissionTo) so re-running this seeder always
        // leaves Financial Team with exactly this set — safe since it only ever
        // touches the Financial Team role, never any other role's permissions.
        $role->syncPermissions($permissions);

        $this->command->info('Financial Team role seeded with ' . $permissions->count() . ' permissions.');
    }
}
