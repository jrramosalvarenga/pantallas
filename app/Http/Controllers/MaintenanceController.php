<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Screen;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceLog::with('screen')->latest('date');

        if ($request->filled('screen_id')) {
            $query->where('screen_id', $request->screen_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $logs = $query->paginate(20);
        $screens = Screen::orderBy('name')->get();

        return view('maintenance.index', compact('logs', 'screens'));
    }

    public function create()
    {
        $screens = Screen::orderBy('name')->get();
        return view('maintenance.create', compact('screens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'screen_id' => 'required|exists:screens,id',
            'date' => 'required|date',
            'type' => 'required|in:preventivo,correctivo,emergencia',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technician' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'next_maintenance_date' => 'nullable|date|after:date',
            'status' => 'required|in:programado,en_curso,completado,cancelado',
            'notes' => 'nullable|string',
        ]);

        MaintenanceLog::create($data);

        return redirect()->route('maintenance.index')->with('success', 'Registro de mantenimiento creado.');
    }

    public function show(MaintenanceLog $maintenance)
    {
        $maintenance->load('screen');
        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(MaintenanceLog $maintenance)
    {
        $screens = Screen::orderBy('name')->get();
        return view('maintenance.edit', compact('maintenance', 'screens'));
    }

    public function update(Request $request, MaintenanceLog $maintenance)
    {
        $data = $request->validate([
            'screen_id' => 'required|exists:screens,id',
            'date' => 'required|date',
            'type' => 'required|in:preventivo,correctivo,emergencia',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technician' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'next_maintenance_date' => 'nullable|date',
            'status' => 'required|in:programado,en_curso,completado,cancelado',
            'notes' => 'nullable|string',
        ]);

        $maintenance->update($data);

        return redirect()->route('maintenance.index')->with('success', 'Registro de mantenimiento actualizado.');
    }

    public function destroy(MaintenanceLog $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenance.index')->with('success', 'Registro eliminado.');
    }
}
