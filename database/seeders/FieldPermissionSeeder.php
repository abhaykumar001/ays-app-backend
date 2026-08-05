<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

/**
 * Permissions that gate a subset of fields within an existing edit form,
 * rather than a whole route/module. These can't be auto-derived from routes
 * (PermissionSeeder does that for whole-module view/create/edit/delete), so
 * they're defined explicitly here. Purely additive — never edits or removes
 * permissions/roles created elsewhere.
 */
class FieldPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $fieldPermissions = [
            [
                'name' => 'edit_project_pricing',
                'module' => 'Projects',
                'description' => 'Edit only the pricing fields (Starting Price, Price per SqFt), Total Units, and Available Units on a project — not the rest of the project form.',
            ],
            [
                'name' => 'edit_unit_pricing',
                'module' => 'Units',
                'description' => 'Edit only the pricing fields (Price, Price per SqFt), Floor, Availability Status, Is Active, Is Featured, and the unit payment-plan repeater on a unit — not the rest of the unit form.',
            ],
        ];

        foreach ($fieldPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['module' => $permission['module'], 'description' => $permission['description']]
            );
        }

        $this->command->info('Field-level permissions seeded (existing ones skipped): ' . collect($fieldPermissions)->pluck('name')->implode(', '));
    }
}
