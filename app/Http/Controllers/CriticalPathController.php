<?php

namespace App\Http\Controllers;

use App\Models\CriticalPathStep;
use Illuminate\Http\Request;

class CriticalPathController extends Controller
{
    public function index()
    {
        $steps = CriticalPathStep::orderBy('order')->get();
        $phases = $steps->groupBy('phase');

        $totalSteps = $steps->count();
        $completedSteps = $steps->where('status', 'completado')->count();
        $progressPercent = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;

        return view('critical-path.index', compact('phases', 'totalSteps', 'completedSteps', 'progressPercent'));
    }

    public function updateStatus(Request $request, CriticalPathStep $step)
    {
        $request->validate([
            'status' => 'required|in:pendiente,en_curso,completado,bloqueado',
        ]);

        $step->update([
            'status' => $request->status,
            'completed_date' => $request->status === 'completado' ? now() : null,
        ]);

        return back()->with('success', "Paso \"{$step->title}\" actualizado.");
    }
}
