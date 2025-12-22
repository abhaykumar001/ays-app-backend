<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_permission')->only(['index', 'show']);
        $this->middleware('permission:create_permission')->only(['create', 'store']);
        $this->middleware('permission:edit_permission')->only(['edit', 'update']);
        $this->middleware('permission:delete_permission')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all dashboard routes
        $routes = collect(Route::getRoutes())
            ->filter(fn($route) => str_starts_with($route->uri(), 'dashboard'))
            ->map(fn($route) => [
                'uri' => $route->uri(),
                'method' => $route->methods()[0],
                'name' => $route->getName() ?? $route->uri(),
            ]);

        // Map module => actions
        $moduleActions = [];

        foreach ($routes as $route) {
            // Determine module name
            $segments = explode('/', $route['uri']);
            $module = isset($segments[1]) && $segments[1] != '' ? ucfirst($segments[1]) : 'Dashboard';

            // Map HTTP method to action
            $action = match ($route['method']) {
                'GET' => 'view',
                'POST' => 'create',
                'PUT', 'PATCH' => 'edit',
                'DELETE' => 'delete',
                default => 'view',
            };

            // Initialize module if not exists
            if (!isset($moduleActions[$module])) {
                $moduleActions[$module] = [];
            }

            // Add action if not already added
            if (!in_array($action, $moduleActions[$module])) {
                $moduleActions[$module][] = $action;
            }
        }

        // Sort modules alphabetically
        ksort($moduleActions);
        $permissions = Permission::get();
        return view('dashboard.permission.index', compact('moduleActions', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'module' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $name = $request->action . '_' . Str::snake(str_replace(' ', '_', $request->module));

        // Check if permission already exists for the same module
        $exists = Permission::where('name', $name)
            ->where('module', $request->module)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('status', 'error')
                ->with('message', 'Permission already exists for this module.');
        }

       $permission = Permission::create([
            'name' => $name,
            'guard_name' => 'web',
            'module' => $request->module,
            'description' => $request->description,
        ]);
        // Assign permission to Super Admin role if it exists
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permission);
        }
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Permission added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->back()->with('status', 'success')->with('message', 'Permission deleted successfully.');
    }
}
