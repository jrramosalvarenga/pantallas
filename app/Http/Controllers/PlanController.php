<?php

namespace App\Http\Controllers;

use App\Models\AdType;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('adType')->latest()->get();
        return view('plans.index', compact('plans'));
    }

    public function create()
    {
        $adTypes = AdType::where('active', true)->get();
        return view('plans.create', compact('adTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:individual,combo',
            'ad_type_id' => 'required|exists:ad_types,id',
            'screens_count' => 'required|integer|min:1',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'duration_months' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $adType = AdType::findOrFail($validated['ad_type_id']);
        $basePrice = $adType->price_per_spot * $validated['screens_count'];
        $validated['final_price'] = $basePrice * (1 - $validated['discount_percent'] / 100);
        $validated['active'] = true;

        Plan::create($validated);

        return redirect()->route('plans.index')
            ->with('success', 'Plan creado exitosamente.');
    }

    public function edit(Plan $plan)
    {
        $adTypes = AdType::where('active', true)->get();
        return view('plans.edit', compact('plan', 'adTypes'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:individual,combo',
            'ad_type_id' => 'required|exists:ad_types,id',
            'screens_count' => 'required|integer|min:1',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'duration_months' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'active' => 'sometimes|boolean',
        ]);

        $adType = AdType::findOrFail($validated['ad_type_id']);
        $basePrice = $adType->price_per_spot * $validated['screens_count'];
        $validated['final_price'] = $basePrice * (1 - $validated['discount_percent'] / 100);
        $validated['active'] = $request->has('active');

        $plan->update($validated);

        return redirect()->route('plans.index')
            ->with('success', 'Plan actualizado exitosamente.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('plans.index')
            ->with('success', 'Plan eliminado exitosamente.');
    }
}
