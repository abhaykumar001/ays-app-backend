<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_amenities')->only(['index', 'show']);
        $this->middleware('permission:create_amenities')->only(['create', 'store']);
        $this->middleware('permission:edit_amenities')->only(['edit', 'update']);
        $this->middleware('permission:delete_amenities')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accommodations = Accommodation::orderby('id', 'desc')->get();
        return view('dashboard.realestate.accommodations.index', compact('accommodations'));
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
