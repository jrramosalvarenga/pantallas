<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Screen;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    public function index()
    {
        $screens = Screen::with('municipality')->get();
        return view('screens.index', compact('screens'));
    }

    public function create()
    {
        $municipalities = Municipality::orderBy('name')->get();
        return view('screens.create', compact('municipalities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:screens,code',
            'municipality_id' => 'required|exists:municipalities,id',
            'address' => 'required|string|max:255',
            'gps_lat' => 'nullable|string|max:20',
            'gps_lng' => 'nullable|string|max:20',
            'total_spaces' => 'required|integer|min:1',
            'spot_duration_seconds' => 'required|integer|min:1',
            'cycle_duration_seconds' => 'required|integer|min:1',
            'operating_hours' => 'required|string|max:255',
            'investment_cost' => 'required|numeric|min:0',
            'book_value' => 'nullable|numeric|min:0',
            'useful_life_months' => 'required|integer|min:1',
            'purchase_date' => 'nullable|date',
            'status' => 'required|in:activa,mantenimiento,inactiva',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['book_value'])) {
            $validated['book_value'] = $validated['investment_cost'];
        }
        $validated['monthly_depreciation'] = $validated['useful_life_months'] > 0
            ? round($validated['investment_cost'] / $validated['useful_life_months'], 2)
            : 0;

        Screen::create($validated);

        return redirect()->route('screens.index')->with('success', 'Pantalla creada exitosamente.');
    }

    public function show(Screen $screen)
    {
        $screen->load(['municipality', 'maintenanceLogs' => function ($q) {
            $q->latest('date');
        }, 'contracts.client']);

        return view('screens.show', compact('screen'));
    }

    public function edit(Screen $screen)
    {
        $municipalities = Municipality::orderBy('name')->get();
        return view('screens.edit', compact('screen', 'municipalities'));
    }

    public function update(Request $request, Screen $screen)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:screens,code,' . $screen->id,
            'municipality_id' => 'required|exists:municipalities,id',
            'address' => 'required|string|max:255',
            'gps_lat' => 'nullable|string|max:20',
            'gps_lng' => 'nullable|string|max:20',
            'total_spaces' => 'required|integer|min:1',
            'spot_duration_seconds' => 'required|integer|min:1',
            'cycle_duration_seconds' => 'required|integer|min:1',
            'operating_hours' => 'required|string|max:255',
            'investment_cost' => 'required|numeric|min:0',
            'book_value' => 'nullable|numeric|min:0',
            'useful_life_months' => 'required|integer|min:1',
            'purchase_date' => 'nullable|date',
            'status' => 'required|in:activa,mantenimiento,inactiva',
            'notes' => 'nullable|string',
        ]);

        $validated['monthly_depreciation'] = $validated['useful_life_months'] > 0
            ? round($validated['investment_cost'] / $validated['useful_life_months'], 2)
            : 0;

        $screen->update($validated);

        return redirect()->route('screens.index')->with('success', 'Pantalla actualizada exitosamente.');
    }

    public function destroy(Screen $screen)
    {
        $screen->delete();
        return redirect()->route('screens.index')->with('success', 'Pantalla eliminada exitosamente.');
    }
}
