<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        // Create a default Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@aysdeveloper.com'], // change to your preferred email
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password@123'), // change to secure password
            ]
        );

        // Assign Super Admin role to the user
        if (!$superAdmin->hasRole($superAdminRole->name)) {
            $superAdmin->assignRole($superAdminRole);
        }

        $this->command->info('Super Admin user and role created successfully!');
    }
}
