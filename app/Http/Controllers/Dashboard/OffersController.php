<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;
class OffersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_offers')->only('index');
        $this->middleware('permission:create_offers')->only(['store']);
        $this->middleware('permission:edit_offers')->only(['edit','update']);
        $this->middleware('permission:delete_offers')->only('destroy');
    }

    /**
     * Display offers list
     */
    public function index()
    {
        $offers = Offer::latest()->get();

        return view('dashboard.contentManagement.offers.index', compact('offers'));
    }

    /**
     * Store new offer
     */
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['created_by'] = Auth::id();

        // Conditions → JSON
        $data['conditions'] = $request->conditions
            ? array_values(array_filter(explode("\n", $request->conditions)))
            : null;

        Offer::create($data);

        return redirect()
            ->route('offers.index')
            ->with('success', 'Offer created successfully');
    }

    /**
     * Fetch offer data for edit (AJAX)
     */
    public function edit(Offer $offer)
    {
        return response()->json([
            'title'        => $offer->title,
            'description'  => $offer->description,
            'type'         => $offer->type,
            'value'        => $offer->value,
            'percentage'   => $offer->percentage,
            'unit'         => $offer->unit,
            'conditions'   => $offer->conditions
                ? implode("\n", $offer->conditions)
                : '',
            'start_date'   => $offer->start_date?->format('Y-m-d'),
            'end_date'     => $offer->end_date?->format('Y-m-d'),
            'is_featured'  => $offer->is_featured,
            'is_active'    => $offer->is_active,
            'sort_order'   => $offer->sort_order,
        ]);
    }

    /**
     * Update offer
     */
    public function update(Request $request, Offer $offer)
    {
        $data = $this->validatedData($request);

        $data['conditions'] = $request->conditions
            ? array_values(array_filter(explode("\n", $request->conditions)))
            : null;

        $offer->update($data);

        return redirect()
            ->route('offers.index')
            ->with('success', 'Offer updated successfully');
    }

    /**
     * Delete offer
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()
            ->route('offers.index')
            ->with('success', 'Offer deleted successfully');
    }

    /**
     * Validation rules
     */
    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'title'        => ['required','string','max:255'],
            'description'  => ['nullable','string'],
            'type'         => ['required','in:discount,dld_waiver,service_charge_waiver,post_handover,furniture,cashback,custom'],
            'value'        => ['nullable','numeric'],
            'percentage'   => ['nullable','numeric'],
            'unit'         => ['nullable','string','max:50'],
            'start_date'   => ['nullable','date'],
            'end_date'     => ['nullable','date','after_or_equal:start_date'],
            'is_featured'  => ['required','boolean'],
            'is_active'    => ['required','boolean'],
            'sort_order'   => ['required','integer'],
        ]);
    }
}