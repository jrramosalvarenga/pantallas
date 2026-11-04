<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Models\ProspectInteraction;
use Illuminate\Http\Request;

class ProspectInteractionController extends Controller
{
    public function store(Request $request, Prospect $prospect)
    {
        $validated = $request->validate([
            'type' => 'required|in:'.implode(',', array_keys(ProspectInteraction::TYPES)),
            'notes' => 'nullable|string',
            'result' => 'nullable|string|max:255',
            'interaction_date' => 'required|date',
            'next_followup_date' => 'nullable|date',
            'new_status' => 'nullable|in:'.implode(',', array_keys(Prospect::STATUSES)),
        ]);

        $prospect->interactions()->create([
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'notes' => $validated['notes'] ?? null,
            'result' => $validated['result'] ?? null,
            'interaction_date' => $validated['interaction_date'],
            'next_followup_date' => $validated['next_followup_date'] ?? null,
        ]);

        if (! empty($validated['new_status'])) {
            $prospect->update(['status' => $validated['new_status']]);
        }

        if (! empty($validated['next_followup_date'])) {
            $prospect->update(['next_followup_date' => $validated['next_followup_date']]);
        }

        return redirect()->route('prospects.show', $prospect)->with('success', 'Interacción registrada exitosamente.');
    }

    public function destroy(Prospect $prospect, ProspectInteraction $interaction)
    {
        $interaction->delete();

        return redirect()->route('prospects.show', $prospect)->with('success', 'Interacción eliminada.');
    }
}
