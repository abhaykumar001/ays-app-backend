<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_enquiries')->only(['index']);
        $this->middleware('permission:edit_enquiries')->only(['edit', 'update']);
        $this->middleware('permission:delete_enquiries')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enquiries = Enquiry::with(['user', 'project', 'unit'])->orderBy('id', 'desc')->get();
        return view('dashboard.enquiries.index', compact('enquiries'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $enquiry = Enquiry::with(['user', 'project', 'unit'])->findOrFail($id);
        return view('dashboard.enquiries.edit', compact('enquiry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,converted',
        ]);

        Enquiry::findOrFail($id)->update($validated);

        return redirect()->route('enquiries.index')
            ->with('status', 'success')
            ->with('message', 'Enquiry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Enquiry::findOrFail($id)->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Enquiry deleted successfully.');
    }
}
