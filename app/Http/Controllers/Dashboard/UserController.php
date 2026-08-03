<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_user')->only(['index', 'show']);
        $this->middleware('permission:create_user')->only(['create', 'store']);
        $this->middleware('permission:edit_user')->only(['edit', 'update', 'toggleStatus']);
        $this->middleware('permission:delete_user')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->get();
        return view('dashboard.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('dashboard.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            if ($request->role_id) {
                $role = Role::find($request->role_id);
                $user->assignRole($role->name);
            }

            return redirect()->back()->with('status', 'success')->with('message', 'User created successfully.');
        } catch (\Exception $error) {
            return redirect()
                ->back()
                ->with('status', 'error')
                ->with('message', $error->getMessage());
        }
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
        $user = User::with(['roles'])->findOrFail($id);
        $roles = Role::all();

        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => !empty($request->password)
                    ? bcrypt($request->password)
                    : $user->password,
            ]);

            // Sync Role
            if ($request->role_id) {
                $role = Role::find($request->role_id);
                $user->syncRoles([$role->name]);
            }

            return redirect()->route('user.index')
                ->with('status', 'success')
                ->with('message', 'User updated successfully.');
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
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('status', 'success')->with('message', 'User deleted successfully.');
    }

    /**
     * Toggle a user's active status (e.g. reactivate a self-deleted client account).
     */
    public function toggleStatus(string $id)
    {
        $user = User::findOrFail($id);
        $user->is_active = ! $user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('status', 'success')->with('message', "User {$status} successfully.");
    }
}
