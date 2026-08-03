<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\TeamMemberCategory;
use Illuminate\Http\Request;

class TeamMembersController extends Controller
{
    public function index()
    {
        $members = TeamMember::with('category')->orderBy('display_order')->orderBy('id')->get();
        return view('dashboard.contentManagement.teamMembers.index', compact('members'));
    }

    public function create()
    {
        $categories = TeamMemberCategory::orderBy('sort_order')->orderBy('name')->get();
        return view('dashboard.contentManagement.teamMembers.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                     => 'required|string|max:255',
            'team_member_category_id' => 'required|exists:team_member_categories,id',
            'email'                    => 'nullable|email|max:255',
            'phone'                    => 'nullable|string|max:30',
            'languages'                => 'nullable|string|max:255',
            'description'              => 'nullable|string|max:2000',
            'display_order'            => 'nullable|integer|min:0',
            'is_active'                => 'nullable|boolean',
            'image'                    => 'required|image|max:5120',
        ]);

        $member = TeamMember::create([
            'name'                     => $request->name,
            'team_member_category_id' => $request->team_member_category_id,
            'email'                    => $request->email,
            'phone'                    => $request->phone,
            'languages'                => $request->languages,
            'description'              => $request->description,
            'display_order'            => $request->integer('display_order', 0),
            'is_active'                => $request->boolean('is_active', true),
        ]);

        $member->addMediaFromRequest('image')->toMediaCollection('images');

        return redirect()->route('teamMembers.index')
            ->with('status', 'success')
            ->with('message', 'Team member created.');
    }

    public function edit(TeamMember $teamMember)
    {
        $categories = TeamMemberCategory::orderBy('sort_order')->orderBy('name')->get();
        return view('dashboard.contentManagement.teamMembers.edit', compact('teamMember', 'categories'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name'                     => 'required|string|max:255',
            'team_member_category_id' => 'required|exists:team_member_categories,id',
            'email'                    => 'nullable|email|max:255',
            'phone'                    => 'nullable|string|max:30',
            'languages'                => 'nullable|string|max:255',
            'description'              => 'nullable|string|max:2000',
            'display_order'            => 'nullable|integer|min:0',
            'is_active'                => 'nullable|boolean',
            'image'                    => 'nullable|image|max:5120',
        ]);

        $teamMember->update([
            'name'                     => $request->name,
            'team_member_category_id' => $request->team_member_category_id,
            'email'                    => $request->email,
            'phone'                    => $request->phone,
            'languages'                => $request->languages,
            'description'              => $request->description,
            'display_order'            => $request->integer('display_order', 0),
            'is_active'                => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('image')) {
            $teamMember->clearMediaCollection('images');
            $teamMember->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Team member updated.');
    }

    public function destroy(TeamMember $teamMember)
    {
        $teamMember->clearMediaCollection('images');
        $teamMember->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Team member deleted.');
    }
}
