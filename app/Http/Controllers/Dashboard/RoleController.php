<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\RoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Roles the app depends on structurally — mobile role identification (Agent,
    // Client) or full dashboard access (Super Admin) — so deleting them by
    // accident would break login/permission checks elsewhere. Not editable via
    // this list; just excluded from deletion.
    public const PROTECTED_ROLES = ['Super Admin', 'Agent', 'Client'];

    public function __construct()
    {
        $this->middleware('permission:view_roles')->only(['index', 'show']);
        $this->middleware('permission:create_roles')->only(['create', 'store']);
        $this->middleware('permission:edit_roles')->only(['edit', 'update']);
        $this->middleware('permission:delete_roles')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->where('name', '!=', 'Super Admin')->get();
        $permissionsByModule = Permission::all()->groupBy('module');
        $protectedRoles = self::PROTECTED_ROLES;
        return view('dashboard.roles.index', compact('roles', 'permissionsByModule', 'protectedRoles'));
    }

    public function create()
    {
        $permissionsByModule = Permission::all()->groupBy('module');
        $protectedRoles = self::PROTECTED_ROLES;
        return view('dashboard.roles.index', compact('permissionsByModule', 'protectedRoles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try {
            // Create new role
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            // Assign permissions if provided
            if ($request->has('permissions') && count($request->permissions) > 0) {
                $role->syncPermissions($request->permissions);
            }
            return redirect()
                ->back()
                ->with('status', 'success')
                ->with('message', 'Role created successfully.');
        } catch (\Exception $error) {
            return redirect()
                    ->back()
                    ->with('status', 'error')
                    ->with('message', $error->getMessage());
        }
    }


    public function show(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    public function edit(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json([
            'id'          => $role->id,
            'name'        => $role->name,
            'permissions' => $role->permissions->pluck('id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        $role = Role::findOrFail($id);
        try {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($request->permissions ?? []);
            return redirect()
                    ->back()
                    ->with('status', 'success')
                    ->with('message', 'Role updated successfully.');
        } catch (\Exception $error) {
            return redirect()
                    ->back()
                    ->with('status', 'error')
                    ->with('message', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, self::PROTECTED_ROLES, true)) {
            return redirect()->back()
                ->with('status', 'error')
                ->with('message', "The \"{$role->name}\" role is built into the app and can't be deleted.");
        }

        $role->delete();
        return redirect()->back()->with('status', 'success')->with('message', 'Role deleted successfully.');
    }
}
