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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = MaintananceRequest::orderby('id', 'desc')->get();
        return view('dashboard.propertyManagement.maintananceRequests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Maintanance::active()->get();
        $units = unit::get();
        $owners = Owner::get();
        return view('dashboard.propertyManagement.maintananceRequests.create', compact('services', 'units', 'owners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
