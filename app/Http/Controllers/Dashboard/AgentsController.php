<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AgentRequest;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_agents')->only(['index', 'show']);
        $this->middleware('permission:create_agents')->only(['create', 'store']);
        $this->middleware('permission:edit_agents')->only(['edit', 'update']);
        $this->middleware('permission:delete_agents')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents = Agent::orderby('id', 'desc')->get();
        return view('dashboard.agents.index', compact('agents'));
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
    public function store(AgentRequest $request)
    {
        $agent = new Agent;
        $agent->first_name = $request->first_name;
        $agent->last_name = $request->last_name;
        $agent->designation = $request->designation;
        $agent->email = $request->email;
        $agent->phone = $request->phone;
        $agent->whatsapp = $request->whatsapp;
        $agent->nationality = $request->nationality;
        $agent->license_number = $request->license_number;
        $agent->license_expiry = $request->license_expiry;
        $agent->notes = $request->notes;
        $agent->is_active = $request->is_active ?? true;
        $agent->user_id = auth()->user()->id;
        $agent->save();
       
        if ($request->hasFile('image')) {
            $agent->addMediaFromRequest('image')->toMediaCollection('images', 'agentFiles');
        }

        return redirect()->route('agents.index')
            ->with('status', 'success')
            ->with('message', 'Agent created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $agent = Agent::findorfail($id);
        return response()->json([
        'name' => $agent->name,
        'designation' => $agent->designation,
        'linkedin_url' => $agent->linkedin_url,
        'status' => $agent->status,
        'image' => $agent->getFirstMediaUrl('images', 'webp'), // full URL if exists
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AgentRequest $request, string $id)
    {
        $agent = Agent::findorfail($id);
        $agent->first_name = $request->first_name;
        $agent->last_name = $request->last_name;
        $agent->designation = $request->designation;
        $agent->email = $request->email;
        $agent->phone = $request->phone;
        $agent->whatsapp = $request->whatsapp;
        $agent->nationality = $request->nationality;
        $agent->license_number = $request->license_number;
        $agent->license_expiry = $request->license_expiry;
        $agent->notes = $request->notes;
        $agent->is_active = $request->is_active;
        $agent->user_id = auth()->user()->id;
        $agent->save();
       
        if ($request->hasFile('image')) {
            $agent->clearMediaCollection('images');
            $agent->addMediaFromRequest('image')->toMediaCollection('images', 'agentFiles');
        }

        return redirect()->route('agents.index')
            ->with('status', 'success')
            ->with('message', 'Agent detail updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $agent = Agent::findorfail($id);
        $agent->clearMediaCollection('images');
        $agent->delete();
        return redirect()->back()->with('status', 'success')->with('message', 'Agent deleted successfully.');
   }
}
