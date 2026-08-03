<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DesignPhilosophy;
use Illuminate\Http\Request;

class DesignPhilosophyController extends Controller
{
    public function edit()
    {
        $philosophy = DesignPhilosophy::singleton();
        $philosophy->load('allSections');
        return view('dashboard.designPhilosophy.edit', compact('philosophy'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_title'        => 'required|string|max:255',
            'hero_title_accent' => 'required|string|max:255',
            'hero_subtitle'     => 'nullable|string|max:500',
            'quote'             => 'nullable|string|max:1000',
            'hero_image'        => 'nullable|image|max:5120',
        ]);

        $philosophy = DesignPhilosophy::singleton();

        $philosophy->update([
            'hero_title'        => $request->hero_title,
            'hero_title_accent' => $request->hero_title_accent,
            'hero_subtitle'     => $request->hero_subtitle ?: null,
            'quote'             => $request->quote ?: null,
        ]);

        if ($request->hasFile('hero_image')) {
            $philosophy->clearMediaCollection('hero');
            $philosophy->addMediaFromRequest('hero_image')->toMediaCollection('hero');
        }

        return redirect()->route('design-philosophy.edit')
            ->with('status', 'success')
            ->with('message', 'Design Philosophy updated successfully.');
    }
}
