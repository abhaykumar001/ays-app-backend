<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UserRequest;
use App\Mail\AccountActivatedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * The only document collections a user can have (broker identity docs).
     * Used to validate the {type} route parameter on the view/download
     * endpoints below.
     */
    private const DOCUMENT_TYPES = ['passport', 'emirates_id'];

    public function __construct()
    {
        $this->middleware('permission:view_user')->only(['index', 'show', 'viewDocument', 'downloadDocument']);
        $this->middleware('permission:create_user')->only(['create', 'store']);
        $this->middleware('permission:edit_user')->only(['edit', 'update', 'toggleStatus', 'approve']);
        $this->middleware('permission:delete_user')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles')->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Super Admin');
        });

        if ($request->query('status') === 'pending') {
            $query->pendingApproval();
        }

        if ($request->filled('role')) {
            $query->role($request->query('role'));
        }

        $users = $query->latest()->get();
        $pendingCount = User::pendingApproval()->count();
        $roles = Role::where('name', '!=', 'Super Admin')->orderBy('name')->get();

        return view('dashboard.users.index', compact('users', 'pendingCount', 'roles'));
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
        $user = User::with('roles')->findOrFail($id);

        $documents = [];
        foreach (self::DOCUMENT_TYPES as $type) {
            $media = $user->getFirstMedia($type);
            $documents[$type] = $media ? [
                'file_name'  => $media->file_name,
                'uploaded_at' => $media->created_at->format('M d, Y'),
            ] : null;
        }

        return view('dashboard.users.show', compact('user', 'documents'));
    }

    /**
     * Stream a broker's identity document inline (opens in the browser).
     */
    public function viewDocument(string $id, string $type)
    {
        $media = $this->findDocumentMedia($id, $type);

        return response()->file($media->getPath(), [
            'Content-Type' => $media->mime_type,
        ]);
    }

    /**
     * Force-download a broker's identity document.
     */
    public function downloadDocument(string $id, string $type)
    {
        $media = $this->findDocumentMedia($id, $type);

        return response()->download($media->getPath(), $media->file_name);
    }

    private function findDocumentMedia(string $id, string $type)
    {
        abort_unless(in_array($type, self::DOCUMENT_TYPES, true), 404);

        $media = User::findOrFail($id)->getFirstMedia($type);

        abort_if(! $media, 404);

        return $media;
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

    /**
     * Approve a pending broker (External Agent) account: activates login and
     * emails them the activation notice. No-op if already approved.
     */
    public function approve(string $id)
    {
        $user = User::findOrFail($id);

        if (! $user->is_approved) {
            $user->is_approved = true;
            $user->approved_at = now();
            $user->save();

            Mail::to($user->email)->send(new AccountActivatedMail($user->name));
        }

        return redirect()->back()->with('status', 'success')->with('message', 'User approved and activation email sent.');
    }
}
