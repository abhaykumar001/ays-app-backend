<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Amenity;
use App\Models\Community;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_projects')->only(['index', 'show']);
        $this->middleware('permission:create_projects')->only(['create', 'store']);
        $this->middleware('permission:edit_projects|edit_project_pricing')->only(['edit', 'update', 'destroyMedia']);
        $this->middleware('permission:delete_projects')->only(['destroy']);
    }

    public function index()
    {
        $projects = Project::orderby('id', 'desc')->get();
        return view('dashboard.realestate.projects.index', compact('projects'));
    }

    public function create()
    {
        $amenities      = Amenity::active()->get();
        $accommodations = Accommodation::active()->get();
        $communities    = Community::active()->get();
        return view('dashboard.realestate.projects.create', compact('amenities', 'accommodations', 'communities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'community_id'          => 'required|exists:communities,id',
            'project_status'        => 'required|in:off_plan,ready,under_construction',
            'sales_status'          => 'required|in:available,sold_out,coming_soon',
            'project_code'          => 'nullable|string|max:255|unique:projects,project_code',
            'price_status'          => 'required|in:price,on_request,coming_soon,sold_out',
            'starting_price'        => 'nullable|numeric|min:0',
            'price_per_sqft'        => 'nullable|numeric|min:0',
            'roi'                   => 'nullable|numeric|min:0|max:999.99',
            'total_units'           => 'nullable|integer|min:0',
            'available_units'       => 'nullable|integer|min:0',
            'construction_progress' => 'nullable|integer|min:0|max:100',
            'min_size'              => 'nullable|integer|min:0',
            'max_size'              => 'nullable|integer|min:0',
            'sort_order'            => 'nullable|integer|min:0',
            'launch_date'           => 'nullable|date',
            'handover_date'         => 'nullable|date',
            'on_handover_payment'   => 'nullable|string|max:50',
            'post_handover_payment' => 'nullable|string|max:50',
            'latitude'              => 'nullable|numeric|between:-90,90',
            'longitude'             => 'nullable|numeric|between:-180,180',
            'image'                 => 'nullable|image|max:5120',
            'gallery'               => 'nullable|array|max:10',
            'gallery.*'             => 'nullable|image|max:5120',
            'brochure'              => 'nullable|mimes:pdf|max:102400',
            'floorplan'             => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:102400',
            'payment_plan'          => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:102400',
            'video'                 => 'nullable|mimes:mp4,mov,avi,webm|max:262144',
            'virtual_tour_url'      => 'nullable|url|max:2048',
            'title_description'     => 'nullable|string',
            'quote_description'     => 'nullable|string',
            'materiality_title'     => 'nullable|string|max:255',
            'materiality_description' => 'nullable|string',
            'materiality_images'    => 'nullable|array|max:5',
            'materiality_images.*'  => 'nullable|image|max:5120',
        ], [
            'gallery.max'            => 'You can upload a maximum of 10 gallery images at a time. Save these first, then upload more separately if needed.',
            'materiality_images.max' => 'You can upload a maximum of 5 materiality images at a time. Save these first, then upload more separately if needed.',
        ]);

        $project = Project::create([
            'name'                  => $request->name,
            'project_code'          => $request->project_code ?: null,
            'community_id'          => $request->community_id,
            'sub_community'         => $request->sub_community ?: null,
            'city'                  => $request->city ?: 'Dubai',
            'project_status'        => $request->project_status,
            'sales_status'          => $request->sales_status,
            'is_featured'           => $request->is_featured ?? 0,
            'is_new_launch'         => $request->is_new_launch ?? 0,
            'is_hot_selling'        => $request->is_hot_selling ?? 0,
            'is_active'             => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
            'price_status'          => $request->price_status,
            'starting_price'        => $request->price_status === 'price' && $request->starting_price !== '' ? $request->starting_price : null,
            'price_per_sqft'        => $request->price_per_sqft !== '' ? $request->price_per_sqft : null,
            'total_units'           => $request->total_units !== '' ? $request->total_units : null,
            'available_units'       => $request->available_units !== '' ? $request->available_units : null,
            'roi'                   => $request->roi !== '' ? $request->roi : null,
            'construction_progress' => $request->construction_progress !== '' ? $request->construction_progress : null,
            'bedrooms'              => $request->bedrooms ?: null,
            'bathrooms'             => $request->bathrooms ?: null,
            'min_size'              => $request->min_size !== '' ? $request->min_size : null,
            'max_size'              => $request->max_size !== '' ? $request->max_size : null,
            'launch_date'           => $request->launch_date ?: null,
            'handover'              => $request->handover ?: null,
            'handover_date'         => $request->handover_date ?: null,
            'on_handover_payment'   => $request->on_handover_payment ?: null,
            'post_handover_payment' => $request->post_handover_payment ?: null,
            'sort_order'            => $request->sort_order !== '' ? $request->sort_order : 0,
            'address'               => $request->address ?: null,
            'latitude'              => $request->latitude !== '' && $request->latitude != 0 ? $request->latitude : null,
            'longitude'             => $request->longitude !== '' && $request->longitude != 0 ? $request->longitude : null,
            'short_description'     => $request->short_description ?: null,
            'title_description'     => $request->title_description ?: null,
            'quote_description'     => $request->quote_description ?: null,
            'materiality_title'     => $request->materiality_title ?: null,
            'materiality_description' => $request->materiality_description ?: null,
            'description'           => $request->description ?: null,
            'virtual_tour_url'      => $request->virtual_tour_url ?: null,
            'meta_title'            => $request->meta_title ?: null,
            'meta_keywords'         => $request->meta_keywords ?: null,
            'meta_description'      => $request->meta_description ?: null,
            'user_id'               => auth()->id(),
        ]);

        // Pivot relationships
        if ($request->filled('amenities')) {
            $project->amenities()->sync($request->amenities);
        }
        if ($request->filled('accommodations')) {
            $project->accommodations()->sync($request->accommodations);
        }

        // Media uploads
        if ($request->hasFile('image')) {
            $project->addMediaFromRequest('image')->toMediaCollection('images');
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $project->addMedia($file)->toMediaCollection('images');
            }
        }
        if ($request->hasFile('materiality_images')) {
            foreach ($request->file('materiality_images') as $file) {
                $project->addMedia($file)->toMediaCollection('materiality');
            }
        }
        if ($request->hasFile('brochure')) {
            $project->addMediaFromRequest('brochure')->toMediaCollection('brochures');
        }
        if ($request->hasFile('floorplan')) {
            $project->addMediaFromRequest('floorplan')->toMediaCollection('floorplans');
        }
        if ($request->hasFile('payment_plan')) {
            $project->addMediaFromRequest('payment_plan')->toMediaCollection('payment_plans');
        }
        if ($request->hasFile('video')) {
            $project->addMediaFromRequest('video')->toMediaCollection('videos');
        }

        return redirect()->route('projects.index')
            ->with('status', 'success')
            ->with('message', 'Project created successfully.');
    }

    public function show(string $id)
    {
        $project = Project::with(['amenities', 'accommodations', 'community', 'phases', 'units'])->findOrFail($id);
        return view('dashboard.realestate.projects.show', compact('project'));
    }

    public function edit(string $id)
    {
        $project        = Project::with(['amenities', 'accommodations'])->findOrFail($id);
        $amenities      = Amenity::active()->get();
        $accommodations = Accommodation::active()->get();
        $communities    = Community::active()->get();
        return view('dashboard.realestate.projects.edit', compact('project', 'amenities', 'accommodations', 'communities'));
    }

    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

        // Users with only edit_project_pricing (e.g. Financial Team) can touch
        // pricing + unit-count fields alone — the edit form hides everything
        // else for them, and this must be enforced here too so a crafted
        // request can't change any other field.
        if (!auth()->user()->can('edit_projects')) {
            $request->validate([
                'price_status'     => 'required|in:price,on_request,coming_soon,sold_out',
                'starting_price'   => 'nullable|numeric|min:0',
                'price_per_sqft'   => 'nullable|numeric|min:0',
                'total_units'      => 'nullable|integer|min:0',
                'available_units'  => 'nullable|integer|min:0',
            ]);

            $project->update([
                'price_status'     => $request->price_status,
                'starting_price'   => $request->price_status === 'price' && $request->starting_price !== '' ? $request->starting_price : null,
                'price_per_sqft'   => $request->price_per_sqft !== '' ? $request->price_per_sqft : null,
                'total_units'      => $request->total_units !== '' ? $request->total_units : null,
                'available_units'  => $request->available_units !== '' ? $request->available_units : null,
            ]);

            return redirect()->route('projects.index')
                ->with('status', 'success')
                ->with('message', 'Project pricing updated successfully.');
        }

        $request->validate([
            'name'                  => 'required|string|max:255',
            'community_id'          => 'required|exists:communities,id',
            'project_status'        => 'required|in:off_plan,ready,under_construction',
            'sales_status'          => 'required|in:available,sold_out,coming_soon',
            'project_code'          => 'nullable|string|max:255|unique:projects,project_code,' . $id,
            'price_status'          => 'required|in:price,on_request,coming_soon,sold_out',
            'starting_price'        => 'nullable|numeric|min:0',
            'price_per_sqft'        => 'nullable|numeric|min:0',
            'roi'                   => 'nullable|numeric|min:0|max:999.99',
            'total_units'           => 'nullable|integer|min:0',
            'available_units'       => 'nullable|integer|min:0',
            'construction_progress' => 'nullable|integer|min:0|max:100',
            'min_size'              => 'nullable|integer|min:0',
            'max_size'              => 'nullable|integer|min:0',
            'sort_order'            => 'nullable|integer|min:0',
            'launch_date'           => 'nullable|date',
            'handover_date'         => 'nullable|date',
            'on_handover_payment'   => 'nullable|string|max:50',
            'post_handover_payment' => 'nullable|string|max:50',
            'latitude'              => 'nullable|numeric|between:-90,90',
            'longitude'             => 'nullable|numeric|between:-180,180',
            'image'                 => 'nullable|image|max:5120',
            'gallery'               => 'nullable|array|max:10',
            'gallery.*'             => 'nullable|image|max:5120',
            'brochure'              => 'nullable|mimes:pdf|max:102400',
            'floorplan'             => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:102400',
            'payment_plan'          => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:102400',
            'video'                 => 'nullable|mimes:mp4,mov,avi,webm|max:262144',
            'virtual_tour_url'      => 'nullable|url|max:2048',
            'title_description'     => 'nullable|string',
            'quote_description'     => 'nullable|string',
            'materiality_title'     => 'nullable|string|max:255',
            'materiality_description' => 'nullable|string',
            'materiality_images'    => 'nullable|array|max:5',
            'materiality_images.*'  => 'nullable|image|max:5120',
        ], [
            'gallery.max'            => 'You can upload a maximum of 10 gallery images at a time. Save these first, then upload more separately if needed.',
            'materiality_images.max' => 'You can upload a maximum of 5 materiality images at a time. Save these first, then upload more separately if needed.',
        ]);

        $project->update([
            'name'                  => $request->name,
            'project_code'          => $request->project_code ?: null,
            'community_id'          => $request->community_id,
            'sub_community'         => $request->sub_community ?: null,
            'city'                  => $request->city ?: 'Dubai',
            'project_status'        => $request->project_status,
            'sales_status'          => $request->sales_status,
            'is_featured'           => $request->is_featured ?? 0,
            'is_new_launch'         => $request->is_new_launch ?? 0,
            'is_hot_selling'        => $request->is_hot_selling ?? 0,
            'is_active'             => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
            'price_status'          => $request->price_status,
            'starting_price'        => $request->price_status === 'price' && $request->starting_price !== '' ? $request->starting_price : null,
            'price_per_sqft'        => $request->price_per_sqft !== '' ? $request->price_per_sqft : null,
            'total_units'           => $request->total_units !== '' ? $request->total_units : null,
            'available_units'       => $request->available_units !== '' ? $request->available_units : null,
            'roi'                   => $request->roi !== '' ? $request->roi : null,
            'construction_progress' => $request->construction_progress !== '' ? $request->construction_progress : null,
            'bedrooms'              => $request->bedrooms ?: null,
            'bathrooms'             => $request->bathrooms ?: null,
            'min_size'              => $request->min_size !== '' ? $request->min_size : null,
            'max_size'              => $request->max_size !== '' ? $request->max_size : null,
            'launch_date'           => $request->launch_date ?: null,
            'handover'              => $request->handover ?: null,
            'handover_date'         => $request->handover_date ?: null,
            'on_handover_payment'   => $request->on_handover_payment ?: null,
            'post_handover_payment' => $request->post_handover_payment ?: null,
            'sort_order'            => $request->sort_order !== '' ? $request->sort_order : 0,
            'address'               => $request->address ?: null,
            'latitude'              => $request->latitude !== '' && $request->latitude != 0 ? $request->latitude : null,
            'longitude'             => $request->longitude !== '' && $request->longitude != 0 ? $request->longitude : null,
            'short_description'     => $request->short_description ?: null,
            'title_description'     => $request->title_description ?: null,
            'quote_description'     => $request->quote_description ?: null,
            'materiality_title'     => $request->materiality_title ?: null,
            'materiality_description' => $request->materiality_description ?: null,
            'description'           => $request->description ?: null,
            'virtual_tour_url'      => $request->virtual_tour_url ?: null,
            'meta_title'            => $request->meta_title ?: null,
            'meta_keywords'         => $request->meta_keywords ?: null,
            'meta_description'      => $request->meta_description ?: null,
            'user_id'               => auth()->id(),
        ]);

        // Pivot relationships
        $project->amenities()->sync($request->amenities ?? []);
        $project->accommodations()->sync($request->accommodations ?? []);

        // Media uploads — only replace if a new file is uploaded
        if ($request->hasFile('image')) {
            // Only remove the current main image (first item in the shared 'images'
            // collection), not the whole collection — clearing the collection here
            // used to wipe the gallery too whenever a new main image was uploaded.
            $project->getFirstMedia('images')?->delete();
            $newMainImage = $project->addMediaFromRequest('image')->toMediaCollection('images');
            $newMainImage->order_column = 0;
            $newMainImage->save();
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $project->addMedia($file)->toMediaCollection('images');
            }
        }
        if ($request->hasFile('materiality_images')) {
            foreach ($request->file('materiality_images') as $file) {
                $project->addMedia($file)->toMediaCollection('materiality');
            }
        }
        if ($request->hasFile('brochure')) {
            $project->clearMediaCollection('brochures');
            $project->addMediaFromRequest('brochure')->toMediaCollection('brochures');
        }
        if ($request->hasFile('floorplan')) {
            $project->clearMediaCollection('floorplans');
            $project->addMediaFromRequest('floorplan')->toMediaCollection('floorplans');
        }
        if ($request->hasFile('payment_plan')) {
            $project->clearMediaCollection('payment_plans');
            $project->addMediaFromRequest('payment_plan')->toMediaCollection('payment_plans');
        }
        if ($request->hasFile('video')) {
            $project->clearMediaCollection('videos');
            $project->addMediaFromRequest('video')->toMediaCollection('videos');
        }

        return redirect()->route('projects.index')
            ->with('status', 'success')
            ->with('message', 'Project updated successfully.');
    }

    public function destroyMedia(Project $project, int $media)
    {
        $mediaItem = $project->media()->where('id', $media)->firstOrFail();
        $mediaItem->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('status', 'success')->with('message', 'File removed successfully.');
    }

    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->clearMediaCollection('images');
        $project->clearMediaCollection('materiality');
        $project->clearMediaCollection('brochures');
        $project->clearMediaCollection('floorplans');
        $project->clearMediaCollection('payment_plans');
        $project->clearMediaCollection('videos');
        $project->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Project deleted successfully.');
    }
}
