<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Viewing;
use Illuminate\Http\Request;

class ViewingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_viewings')->only(['index']);
        $this->middleware('permission:edit_viewings')->only(['edit', 'update']);
        $this->middleware('permission:delete_viewings')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewings = Viewing::with(['user', 'project', 'unit'])->orderBy('id', 'desc')->get();
        return view('dashboard.viewings.index', compact('viewings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $viewing = Viewing::with(['user', 'project', 'unit'])->findOrFail($id);
        return view('dashboard.viewings.edit', compact('viewing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        Viewing::findOrFail($id)->update($validated);

        return redirect()->route('viewings.index')
            ->with('status', 'success')
            ->with('message', 'Viewing updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Viewing::findOrFail($id)->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Viewing deleted successfully.');
    }
}
