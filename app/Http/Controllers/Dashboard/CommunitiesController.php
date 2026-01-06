<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_communities')->only(['index', 'show']);
        $this->middleware('permission:create_communities')->only(['create', 'store']);
        $this->middleware('permission:edit_communities')->only(['edit', 'update']);
        $this->middleware('permission:delete_communities')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $communities = Community::orderby('id', 'desc')->get();
        return view('dashboard.realestate.communities.index', compact('communities'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.realestate.communities.create');
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
