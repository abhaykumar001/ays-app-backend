<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Maintanance;
use App\Models\MaintananceRequest;
use App\Models\Owner;
use App\Models\unit;
use Illuminate\Http\Request;

class MaintananceRequestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_maintanance_requests')->only(['index', 'show']);
        $this->middleware('permission:create_maintanance_requests')->only(['create', 'store']);
        $this->middleware('permission:edit_maintanance_requests')->only(['edit', 'update']);
        $this->middleware('permission:delete_maintanance_requests')->only(['destroy']);
    }

    public function index()
    {
        $requests = MaintananceRequest::with(['service', 'owner'])->orderBy('id', 'desc')->get();
        return view('dashboard.propertyManagement.maintananceRequests.index', compact('requests'));
    }

    public function create()
    {
        $services = Maintanance::active()->get();
        $units    = unit::get();
        $owners   = Owner::get();
        return view('dashboard.propertyManagement.maintananceRequests.create', compact('services', 'units', 'owners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id'  => 'required|exists:maintanances,id',
            'owner_id'    => 'nullable|exists:owners,id',
            'unit_id'     => 'nullable|exists:units,id',
            'description' => 'nullable|string',
            'images'      => 'nullable|array',
            'images.*'    => 'image|max:5120',
        ]);

        $maintenance = MaintananceRequest::create([
            'service_id'  => $request->service_id,
            'owner_id'    => $request->owner_id ?: null,
            'unit_id'     => $request->unit_id ?: null,
            'description' => $request->description,
            'status'      => 'pending',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $maintenance->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('maintananceRequests.index')
            ->with('status', 'success')
            ->with('message', 'Maintenance request created successfully.');
    }

    public function edit(string $id)
    {
        $request = MaintananceRequest::with(['service', 'owner', 'unit'])->findOrFail($id);
        return view('dashboard.propertyManagement.maintananceRequests.edit', compact('request'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status'               => 'required|in:pending,in_progress,completed,cancelled',
            'assigned_to'          => 'nullable|string|max:255',
            'completed_at'         => 'nullable|date',
            'materials_used'       => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'images'               => 'nullable|array',
            'images.*'             => 'image|max:5120',
        ]);

        $maintenance = MaintananceRequest::findOrFail($id);
        $maintenance->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $maintenance->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('maintananceRequests.index')
            ->with('status', 'success')
            ->with('message', 'Maintenance request updated successfully.');
    }

    public function destroy(string $id)
    {
        $maintenance = MaintananceRequest::findOrFail($id);
        $maintenance->clearMediaCollection('images');
        $maintenance->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Maintenance request deleted successfully.');
    }
}
