<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientTask;
use App\Models\User;
use Illuminate\Http\Request;

class ClientTaskController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientTask::with(['client', 'assignedUser', 'contract']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($c) => $c->where('business_name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        $statusCounts = ClientTask::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $tasks = $query->latest()->paginate(20)->withQueryString();

        return view('tasks.index', compact('tasks', 'statusCounts'));
    }

    public function create(Request $request)
    {
        $clients = Client::where('status', 'activo')->orderBy('business_name')->get();
        $users = User::orderBy('name')->get();
        $selectedClient = $request->get('client_id');

        return view('tasks.create', compact('clients', 'users', 'selectedClient'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:'.implode(',', array_keys(ClientTask::TYPES)),
            'priority' => 'required|in:'.implode(',', array_keys(ClientTask::PRIORITIES)),
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $data['status'] = 'pendiente';
        $data['created_by'] = auth()->id();

        ClientTask::create($data);

        return redirect()->route('tasks.index')->with('success', 'Tarea creada exitosamente.');
    }

    public function show(ClientTask $task)
    {
        $task->load(['client', 'contract', 'assignedUser', 'creator']);

        return view('tasks.show', compact('task'));
    }

    public function edit(ClientTask $task)
    {
        $clients = Client::where('status', 'activo')->orderBy('business_name')->get();
        $users = User::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'clients', 'users'));
    }

    public function update(Request $request, ClientTask $task)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:'.implode(',', array_keys(ClientTask::TYPES)),
            'priority' => 'required|in:'.implode(',', array_keys(ClientTask::PRIORITIES)),
            'status' => 'required|in:'.implode(',', array_keys(ClientTask::STATUSES)),
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($data['status'] === 'completada' && $task->status !== 'completada') {
            $data['completed_at'] = now();
        }

        $task->update($data);

        return redirect()->route('tasks.show', $task)->with('success', 'Tarea actualizada.');
    }

    public function updateStatus(Request $request, ClientTask $task)
    {
        $data = $request->validate([
            'status' => 'required|in:'.implode(',', array_keys(ClientTask::STATUSES)),
        ]);

        if ($data['status'] === 'completada' && $task->status !== 'completada') {
            $data['completed_at'] = now();
        }

        $task->update($data);

        return back()->with('success', 'Estado actualizado.');
    }

    public function destroy(ClientTask $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada.');
    }
}
