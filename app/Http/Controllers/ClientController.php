<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Municipality;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with('municipality');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $municipalities = Municipality::orderBy('name')->get();
        return view('clients.create', compact('municipalities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'rtn' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'municipality_id' => 'nullable|exists:municipalities,id',
            'status' => 'required|in:activo,inactivo,suspendido',
            'notes' => 'nullable|string',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Client $client)
    {
        $client->load(['municipality', 'contracts.plan', 'contracts.screens', 'payments' => function ($q) {
            $q->latest('due_date');
        }]);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $municipalities = Municipality::orderBy('name')->get();
        return view('clients.edit', compact('client', 'municipalities'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'rtn' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'municipality_id' => 'nullable|exists:municipalities,id',
            'status' => 'required|in:activo,inactivo,suspendido',
            'notes' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
