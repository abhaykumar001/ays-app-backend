<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Amenity;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_units')->only(['index', 'show']);
        $this->middleware('permission:create_units')->only(['create', 'store']);
        $this->middleware('permission:edit_units|edit_unit_pricing')->only(['edit', 'update']);
        $this->middleware('permission:delete_units')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $units = $project->units()
            ->orderByDesc('id')
            ->get();

        return view(
            'dashboard.realestate.units.index',
            compact('project', 'units')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create(Project $project)
    {
        $amenities = Amenity::active()->get();
        $accommodations = Accommodation::active()->get();
        $phases = $project->phases()->get();

        return view(
            'dashboard.realestate.units.create',
            compact('project', 'amenities', 'accommodations', 'phases')
        );
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'accommodation_id'    => 'required|exists:accommodations,id',
            'project_phase_id'    => 'nullable|exists:phases,id',
            'unit_number'         => 'nullable|string|max:255|unique:units,unit_number',
            'unit_type'           => 'nullable|string|max:255',
            'bedrooms'            => 'required|string|max:100',
            'bathrooms'           => 'required|string|max:100',
            'parking'             => 'nullable|integer|min:0|max:255',
            'size_sqft'           => 'required|string|max:100',
            'plot_size_sqft'      => 'nullable|integer|min:0',
            'price_status'        => 'required|in:price,on_request,coming_soon,sold_out',
            'price'               => 'nullable|numeric|min:0',
            'price_per_sqft'      => 'nullable|string|max:100',
            'floor'               => 'nullable|string|max:100',
            'view'                => 'nullable|string|max:255',
            'availability_status' => 'required|in:available,reserved,sold',
            'description'         => 'nullable|string',
            'image'               => 'nullable|image|max:5120',
            'gallery.*'           => 'nullable|image|max:5120',
            'floorplan'           => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'payment_plan'        => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'video'               => 'nullable|mimes:mp4,mov,avi,webm|max:262144',

            'payment_plans'                              => 'nullable|array',
            'payment_plans.*.name'                        => 'required_with:payment_plans|string|max:255',
            'payment_plans.*.tentative_sale_date'          => 'nullable|date',
            'payment_plans.*.milestones'                   => 'required_with:payment_plans|array|min:1',
            'payment_plans.*.milestones.*.month_offset'    => 'required|integer|min:0',
            'payment_plans.*.milestones.*.percent'         => 'required|numeric|min:0|max:100',
            'payment_plans.*.milestones.*.is_amount_manual' => 'nullable|boolean',
            'payment_plans.*.milestones.*.amount'          => 'nullable|numeric|min:0',
        ]);

        $unit = $project->units()->create([
            'project_phase_id'    => $request->project_phase_id ?: null,
            'accommodation_id'    => $request->accommodation_id,
            'unit_number'         => $request->unit_number ?: null,
            'unit_type'           => $request->unit_type ?: null,
            'title'               => $request->title,
            'slug'                => Str::slug($project->slug . '-' . $request->title . '-' . uniqid()),
            'bedrooms'            => $request->bedrooms,
            'bathrooms'           => $request->bathrooms,
            'parking'             => $request->parking !== '' ? $request->parking : null,
            'size_sqft'           => $request->size_sqft,
            'plot_size_sqft'      => $request->plot_size_sqft !== '' ? $request->plot_size_sqft : null,
            'price_status'        => $request->price_status,
            'price'               => $request->price_status === 'price' && $request->price !== '' ? $request->price : null,
            'price_per_sqft'      => $request->price_per_sqft !== '' ? $request->price_per_sqft : null,
            'floor'               => $request->floor !== '' ? $request->floor : null,
            'view'                => $request->view ?: null,
            'availability_status' => $request->availability_status,
            'description'         => $request->description ?: null,
            'meta_title'          => $request->meta_title ?: null,
            'meta_keywords'       => $request->meta_keywords ?: null,
            'meta_description'    => $request->meta_description ?: null,
            'is_featured'         => $request->is_featured ?? 0,
            'is_active'           => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
            'user_id'             => auth()->id(),
        ]);

        if ($request->filled('amenities')) {
            $unit->amenities()->sync($request->amenities);
        }

        $this->syncPaymentPlans($unit, $request->input('payment_plans', []));

        if ($request->hasFile('image')) {
            $unit->addMediaFromRequest('image')->toMediaCollection('images');
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $unit->addMedia($file)->toMediaCollection('images');
            }
        }
        if ($request->hasFile('floorplan')) {
            $unit->addMediaFromRequest('floorplan')->toMediaCollection('floorplans');
        }
        if ($request->hasFile('payment_plan')) {
            $unit->addMediaFromRequest('payment_plan')->toMediaCollection('payment_plans');
        }
        if ($request->hasFile('video')) {
            $unit->addMediaFromRequest('video')->toMediaCollection('videos');
        }

        return redirect()->route('projects.units.index', $project)
            ->with('status', 'success')
            ->with('message', 'Unit created successfully.');
    }

    public function edit(Project $project, string $id)
    {
        $unit           = \App\Models\unit::findOrFail($id);
        $amenities      = Amenity::active()->get();
        $accommodations = Accommodation::active()->get();
        $phases         = $project->phases()->get();

        return view('dashboard.realestate.units.edit', compact('project', 'unit', 'amenities', 'accommodations', 'phases'));
    }

    public function update(Request $request, Project $project, string $id)
    {
        $unit = \App\Models\unit::findOrFail($id);

        // Users with only edit_unit_pricing (e.g. Financial Team) can touch price
        // fields, floor, availability/active/featured status, and the payment-plan
        // repeater alone — the edit form hides everything else for them, and this
        // must be enforced here too so a crafted request can't change any other field.
        if (!auth()->user()->can('edit_units')) {
            $request->validate([
                'price_status'         => 'required|in:price,on_request,coming_soon,sold_out',
                'price'                => 'nullable|numeric|min:0',
                'price_per_sqft'       => 'nullable|string|max:100',
                'floor'                => 'nullable|string|max:100',
                'availability_status'  => 'required|in:available,reserved,sold',

                'payment_plans'                              => 'nullable|array',
                'payment_plans.*.name'                        => 'required_with:payment_plans|string|max:255',
                'payment_plans.*.tentative_sale_date'          => 'nullable|date',
                'payment_plans.*.milestones'                   => 'required_with:payment_plans|array|min:1',
                'payment_plans.*.milestones.*.month_offset'    => 'required|integer|min:0',
                'payment_plans.*.milestones.*.percent'         => 'required|numeric|min:0|max:100',
                'payment_plans.*.milestones.*.is_amount_manual' => 'nullable|boolean',
                'payment_plans.*.milestones.*.amount'          => 'nullable|numeric|min:0',
            ]);

            $unit->update([
                'price_status'         => $request->price_status,
                'price'                => $request->price_status === 'price' && $request->price !== '' ? $request->price : null,
                'price_per_sqft'       => $request->price_per_sqft !== '' ? $request->price_per_sqft : null,
                'floor'                => $request->floor !== '' ? $request->floor : null,
                'availability_status'  => $request->availability_status,
                'is_active'            => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
                'is_featured'          => $request->is_featured ?? 0,
            ]);

            $this->syncPaymentPlans($unit, $request->input('payment_plans', []));

            return redirect()->route('projects.units.index', $project)
                ->with('status', 'success')
                ->with('message', 'Unit pricing updated successfully.');
        }

        $request->validate([
            'title'               => 'required|string|max:255',
            'accommodation_id'    => 'required|exists:accommodations,id',
            'project_phase_id'    => 'nullable|exists:phases,id',
            'unit_number'         => 'nullable|string|max:255|unique:units,unit_number,' . $id,
            'unit_type'           => 'nullable|string|max:255',
            'bedrooms'            => 'required|string|max:100',
            'bathrooms'           => 'required|string|max:100',
            'parking'             => 'nullable|integer|min:0|max:255',
            'size_sqft'           => 'required|string|max:100',
            'plot_size_sqft'      => 'nullable|integer|min:0',
            'price_status'        => 'required|in:price,on_request,coming_soon,sold_out',
            'price'               => 'nullable|numeric|min:0',
            'price_per_sqft'      => 'nullable|string|max:100',
            'floor'               => 'nullable|string|max:100',
            'view'                => 'nullable|string|max:255',
            'availability_status' => 'required|in:available,reserved,sold',
            'description'         => 'nullable|string',
            'image'               => 'nullable|image|max:5120',
            'gallery.*'           => 'nullable|image|max:5120',
            'floorplan'           => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'payment_plan'        => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'video'               => 'nullable|mimes:mp4,mov,avi,webm|max:262144',

            'payment_plans'                              => 'nullable|array',
            'payment_plans.*.name'                        => 'required_with:payment_plans|string|max:255',
            'payment_plans.*.tentative_sale_date'          => 'nullable|date',
            'payment_plans.*.milestones'                   => 'required_with:payment_plans|array|min:1',
            'payment_plans.*.milestones.*.month_offset'    => 'required|integer|min:0',
            'payment_plans.*.milestones.*.percent'         => 'required|numeric|min:0|max:100',
            'payment_plans.*.milestones.*.is_amount_manual' => 'nullable|boolean',
            'payment_plans.*.milestones.*.amount'          => 'nullable|numeric|min:0',
        ]);

        $unit->update([
            'project_phase_id'    => $request->project_phase_id ?: null,
            'accommodation_id'    => $request->accommodation_id,
            'unit_number'         => $request->unit_number ?: null,
            'unit_type'           => $request->unit_type ?: null,
            'title'               => $request->title,
            'bedrooms'            => $request->bedrooms,
            'bathrooms'           => $request->bathrooms,
            'parking'             => $request->parking !== '' ? $request->parking : null,
            'size_sqft'           => $request->size_sqft,
            'plot_size_sqft'      => $request->plot_size_sqft !== '' ? $request->plot_size_sqft : null,
            'price_status'        => $request->price_status,
            'price'               => $request->price_status === 'price' && $request->price !== '' ? $request->price : null,
            'price_per_sqft'      => $request->price_per_sqft !== '' ? $request->price_per_sqft : null,
            'floor'               => $request->floor !== '' ? $request->floor : null,
            'view'                => $request->view ?: null,
            'availability_status' => $request->availability_status,
            'description'         => $request->description ?: null,
            'meta_title'          => $request->meta_title ?: null,
            'meta_keywords'       => $request->meta_keywords ?: null,
            'meta_description'    => $request->meta_description ?: null,
            'is_featured'         => $request->is_featured ?? 0,
            'is_active'           => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
            'user_id'             => auth()->id(),
        ]);

        if ($request->filled('amenities')) {
            $unit->amenities()->sync($request->amenities);
        }

        $this->syncPaymentPlans($unit, $request->input('payment_plans', []));

        if ($request->hasFile('image')) {
            $unit->clearMediaCollection('images');
            $unit->addMediaFromRequest('image')->toMediaCollection('images');
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $unit->addMedia($file)->toMediaCollection('images');
            }
        }
        if ($request->hasFile('floorplan')) {
            $unit->clearMediaCollection('floorplans');
            $unit->addMediaFromRequest('floorplan')->toMediaCollection('floorplans');
        }
        if ($request->hasFile('payment_plan')) {
            $unit->clearMediaCollection('payment_plans');
            $unit->addMediaFromRequest('payment_plan')->toMediaCollection('payment_plans');
        }
        if ($request->hasFile('video')) {
            $unit->clearMediaCollection('videos');
            $unit->addMediaFromRequest('video')->toMediaCollection('videos');
        }

        return redirect()->route('projects.units.index', $project)
            ->with('status', 'success')
            ->with('message', 'Unit updated successfully.');
    }

    private function syncPaymentPlans(\App\Models\unit $unit, array $plans): void
    {
        $unit->paymentPlans()->delete();

        foreach ($plans as $planIndex => $plan) {
            $paymentPlan = $unit->paymentPlans()->create([
                'name'                 => $plan['name'],
                'tentative_sale_date'  => $plan['tentative_sale_date'] ?? null,
                'sort_order'           => $planIndex,
            ]);

            foreach ($plan['milestones'] as $milestoneIndex => $milestone) {
                $paymentPlan->milestones()->create([
                    'month_offset'     => $milestone['month_offset'],
                    'percent'          => $milestone['percent'],
                    'is_amount_manual' => filter_var($milestone['is_amount_manual'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'amount'           => $milestone['amount'] ?? null,
                    'sort_order'       => $milestoneIndex,
                ]);
            }
        }
    }

    public function destroy(Project $project, string $id)
    {
        $unit = \App\Models\unit::findOrFail($id);
        $unit->clearMediaCollection('images');
        $unit->clearMediaCollection('floorplans');
        $unit->clearMediaCollection('payment_plans');
        $unit->clearMediaCollection('videos');
        $unit->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Unit deleted successfully.');
    }
}
