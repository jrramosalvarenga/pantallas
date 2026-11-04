<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Prospect;
use App\Services\PitchAdvisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProspectController extends Controller
{
    public function index(Request $request)
    {
        $query = Prospect::with('municipality');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $statusCounts = Prospect::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $prospects = $query->latest()->paginate(20)->withQueryString();
        $categories = Prospect::whereNotNull('category')->distinct()->pluck('category')->sort();

        return view('prospects.index', compact('prospects', 'statusCounts', 'categories'));
    }

    public function create()
    {
        $municipalities = Municipality::orderBy('name')->get();

        return view('prospects.create', compact('municipalities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'municipality_id' => 'nullable|exists:municipalities,id',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:100',
            'data_source' => 'nullable|string|max:100',
            'nearby_screen' => 'nullable|string|max:100',
            'priority' => 'nullable|in:A,B,C',
            'status' => 'required|in:'.implode(',', array_keys(Prospect::STATUSES)),
            'plan_of_interest' => 'nullable|string|max:100',
            'visit_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('prospect-logos', 'public');
        }

        unset($validated['logo']);
        Prospect::create($validated);

        return redirect()->route('prospects.index')->with('success', 'Prospecto creado exitosamente.');
    }

    public function show(Prospect $prospect)
    {
        $prospect->load(['municipality', 'interactions.user']);
        $pitchAdvice = PitchAdvisor::getAdvice($prospect->category ?? '');

        return view('prospects.show', compact('prospect', 'pitchAdvice'));
    }

    public function edit(Prospect $prospect)
    {
        $municipalities = Municipality::orderBy('name')->get();

        return view('prospects.edit', compact('prospect', 'municipalities'));
    }

    public function update(Request $request, Prospect $prospect)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'municipality_id' => 'nullable|exists:municipalities,id',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:100',
            'data_source' => 'nullable|string|max:100',
            'nearby_screen' => 'nullable|string|max:100',
            'priority' => 'nullable|in:A,B,C',
            'status' => 'required|in:'.implode(',', array_keys(Prospect::STATUSES)),
            'plan_of_interest' => 'nullable|string|max:100',
            'visit_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($prospect->logo_path) {
                Storage::disk('public')->delete($prospect->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('prospect-logos', 'public');
        }

        unset($validated['logo']);
        $prospect->update($validated);

        return redirect()->route('prospects.show', $prospect)->with('success', 'Prospecto actualizado exitosamente.');
    }

    public function destroy(Prospect $prospect)
    {
        if ($prospect->logo_path) {
            Storage::disk('public')->delete($prospect->logo_path);
        }

        $prospect->delete();

        return redirect()->route('prospects.index')->with('success', 'Prospecto eliminado exitosamente.');
    }
}
