<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch all dashboard routes
        $routes = collect(Route::getRoutes())
            ->filter(fn($route) => str_starts_with($route->uri(), 'dashboard'))
            ->map(fn($route) => [
                'uri' => $route->uri(),
                'method' => $route->methods()[0],
            ]);

        // Map module => actions
        $moduleActions = [];

        foreach ($routes as $route) {
            $segments = explode('/', $route['uri']);
            $module = isset($segments[1]) && $segments[1] != '' ? ucfirst($segments[1]) : 'Dashboard';

            $action = match ($route['method']) {
                'GET' => 'view',
                'POST' => 'create',
                'PUT', 'PATCH' => 'edit',
                'DELETE' => 'delete',
                default => 'view',
            };

            if (!isset($moduleActions[$module])) {
                $moduleActions[$module] = [];
            }

            if (!in_array($action, $moduleActions[$module])) {
                $moduleActions[$module][] = $action;
            }
        }

        ksort($moduleActions);

        // Get or create Super Admin role
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

        foreach ($moduleActions as $module => $actions) {
            foreach ($actions as $action) {
                $name = $action . '_' . Str::snake($module);

                // Check if permission exists
                $permission = Permission::where('name', $name)->where('module', $module)->first();

                if (!$permission) {
                    $permission = Permission::create([
                        'name' => $name,
                        'guard_name' => 'web',
                        'module' => $module,
                        'description' => ucfirst($action) . ' permission for ' . $module,
                    ]);

                    // Assign to Super Admin
                    $superAdminRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('All dashboard permissions have been seeded (existing permissions skipped)!');
    }
}
