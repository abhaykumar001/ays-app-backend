<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Maintanance;
use Illuminate\Http\Request;

class MaintananceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_maintanance')->only(['index', 'show']);
        $this->middleware('permission:create_maintanance')->only(['create', 'store']);
        $this->middleware('permission:edit_maintanance')->only(['edit', 'update']);
        $this->middleware('permission:delete_maintanance')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintanances = Maintanance::orderby('id', 'desc')->get();
        return view('dashboard.propertyManagement.maintanance.index', compact('maintanances'));
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
