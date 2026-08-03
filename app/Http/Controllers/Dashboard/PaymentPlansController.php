<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PaymentPlan;
use App\Models\Project;
use Illuminate\Http\Request;

class PaymentPlansController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_payment_plans')->only(['index']);
        $this->middleware('permission:create_payment_plans')->only(['store']);
        $this->middleware('permission:edit_payment_plans')->only(['edit', 'update']);
        $this->middleware('permission:delete_payment_plans')->only(['destroy']);
    }

    public function index(Project $project)
    {
        $paymentPlans = $project->paymentPlans()
            ->orderByDesc('id')
            ->get();

        return view(
            'dashboard.realestate.paymentPlans.index',
            compact('project', 'paymentPlans')
        );
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'payment_breakdown' => 'nullable|array',
            'payment_breakdown.*.name' => 'required_with:payment_breakdown|string|max:255',
            'payment_breakdown.*.percentage' => 'required_with:payment_breakdown|string|max:255',
            'installments' => 'nullable|array',
            'installments.*.due_date' => 'required_with:installments|date',
            'installments.*.amount' => 'required_with:installments|numeric',
            'down_payment' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
            'is_offer' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'payment_plan_file' => 'nullable|file|mimes:pdf,doc,docx,xlsx',
        ]);

        // Convert arrays to JSON
        $data['payment_breakdown'] = $data['payment_breakdown'] ?? [];
        $data['installments'] = $data['installments'] ?? [];

        $paymentPlan = $project->paymentPlans()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'payment_breakdown' => $data['payment_breakdown'],
            'installments' => $data['installments'],
            'down_payment' => $data['down_payment'] ?? null,
            'total_price' => $data['total_price'] ?? null,
            'is_offer' => $request->boolean('is_offer'),
            'is_active' => $request->boolean('is_active', true),
            'file' => null,
        ]);

        if ($request->hasFile('payment_plan_file')) {
            $paymentPlan->addMediaFromRequest('payment_plan_file')
                ->toMediaCollection('payment_plans');
        }

        return redirect()->back()->with('success', 'Payment Plan created successfully.');
    }

    /**
     * AJAX edit
     */
    public function edit(Project $project, PaymentPlan $paymentPlan)
    {
        return response()->json([
            'title' => $paymentPlan->title,
            'description' => $paymentPlan->description,
            'payment_breakdown' => $paymentPlan->payment_breakdown ?? [],
            'installments' => $paymentPlan->installments ?? [],
            'down_payment' => $paymentPlan->down_payment,
            'total_price' => $paymentPlan->total_price,
            'is_offer' => $paymentPlan->is_offer,
            'is_active' => $paymentPlan->is_active,
            'file' => $paymentPlan->hasMedia('payment_plans')
                ? $paymentPlan->getFirstMediaUrl('payment_plans')
                : null,
        ]);
    }
    public function update(Request $request, Project $project, PaymentPlan $paymentPlan)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'payment_breakdown' => 'nullable|array',
            'payment_breakdown.*.name' => 'required_with:payment_breakdown|string|max:255',
            'payment_breakdown.*.percentage' => 'required_with:payment_breakdown|string|max:255',
            'installments' => 'nullable|array',
            'installments.*.due_date' => 'required_with:installments|date',
            'installments.*.amount' => 'required_with:installments|numeric',
            'down_payment' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
            'is_offer' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'payment_plan_file' => 'nullable|file|mimes:pdf,doc,docx,xlsx',
        ]);

        // Handle file upload
        if ($request->hasFile('payment_plan_file')) {
            $paymentPlan->clearMediaCollection('payment_plans');
            $paymentPlan->addMediaFromRequest('payment_plan_file')
                ->toMediaCollection('payment_plans');
        }

        // Update arrays and booleans
        $data['payment_breakdown'] = $data['payment_breakdown'] ?? [];
        $data['installments'] = $data['installments'] ?? [];
        $data['is_offer'] = $request->boolean('is_offer');
        $data['is_active'] = $request->boolean('is_active', true);

        $paymentPlan->update($data);

        return redirect()->back()->with('success', 'Payment Plan updated successfully.');
    }


    public function destroy(Project $project, PaymentPlan $paymentPlan)
    {
        $paymentPlan->delete();

        return back()->with('success', 'Payment plan deleted successfully');
    }
}
