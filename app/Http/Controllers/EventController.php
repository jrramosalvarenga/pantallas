<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Event;
use App\Models\Municipality;
use App\Models\Screen;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['client', 'screen', 'municipality']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        $statusCounts = Event::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $events = $query->latest('start_date')->paginate(20)->withQueryString();

        return view('events.index', compact('events', 'statusCounts'));
    }

    public function create()
    {
        $clients = Client::where('status', 'activo')->orderBy('business_name')->get();
        $screens = Screen::orderBy('name')->get();
        $municipalities = Municipality::orderBy('name')->get();

        return view('events.create', compact('clients', 'screens', 'municipalities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'screen_id' => 'nullable|exists:screens,id',
            'venue' => 'required|string|max:255',
            'venue_address' => 'nullable|string|max:255',
            'municipality_id' => 'nullable|exists:municipalities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:'.implode(',', array_keys(Event::TYPES)),
            'status' => 'required|in:'.implode(',', array_keys(Event::STATUSES)),
            'quoted_price' => 'nullable|numeric|min:0',
            'final_price' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'deposit_paid' => 'boolean',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'technical_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['created_by'] = auth()->id();
        $data['deposit_paid'] = $request->boolean('deposit_paid');

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Evento creado exitosamente.');
    }

    public function show(Event $event)
    {
        $event->load(['client', 'screen', 'municipality', 'creator']);

        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $clients = Client::where('status', 'activo')->orderBy('business_name')->get();
        $screens = Screen::orderBy('name')->get();
        $municipalities = Municipality::orderBy('name')->get();

        return view('events.edit', compact('event', 'clients', 'screens', 'municipalities'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'screen_id' => 'nullable|exists:screens,id',
            'venue' => 'required|string|max:255',
            'venue_address' => 'nullable|string|max:255',
            'municipality_id' => 'nullable|exists:municipalities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:'.implode(',', array_keys(Event::TYPES)),
            'status' => 'required|in:'.implode(',', array_keys(Event::STATUSES)),
            'quoted_price' => 'nullable|numeric|min:0',
            'final_price' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'deposit_paid' => 'boolean',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'technical_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['deposit_paid'] = $request->boolean('deposit_paid');

        $event->update($data);

        return redirect()->route('events.show', $event)->with('success', 'Evento actualizado.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Evento eliminado.');
    }
}
