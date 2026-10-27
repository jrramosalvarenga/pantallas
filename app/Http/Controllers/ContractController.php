<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Screen;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $contracts = Contract::with('client', 'plan', 'screens')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->search, fn($q, $s) => $q->whereHas('client', fn($c) => $c->where('business_name', 'like', "%{$s}%")))
            ->latest()
            ->paginate(15);

        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        $clients = Client::where('status', 'activo')->orderBy('business_name')->get();
        $plans = Plan::where('active', true)->with('adType')->get();
        $screens = Screen::where('status', 'activa')->with('municipality')->get();

        return view('contracts.create', compact('clients', 'plans', 'screens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'payment_frequency' => 'required|in:mensual,trimestral,semestral,anual',
            'notes' => 'nullable|string',
            'screens' => 'required|array|min:1',
            'screens.*.id' => 'required|exists:screens,id',
            'screens.*.space_number' => 'required|integer|min:1',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $months = $startDate->diffInMonths($endDate);
        if ($months < 1) $months = 1;

        $contract = Contract::create([
            'contract_number' => Contract::generateContractNumber(),
            'client_id' => $validated['client_id'],
            'plan_id' => $validated['plan_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'monthly_amount' => $plan->final_price,
            'total_amount' => $plan->final_price * $months,
            'payment_frequency' => $validated['payment_frequency'],
            'status' => 'activo',
            'notes' => $validated['notes'],
        ]);

        foreach ($validated['screens'] as $screenData) {
            $contract->screens()->attach($screenData['id'], [
                'space_number' => $screenData['space_number'],
            ]);
        }

        for ($i = 0; $i < $months; $i++) {
            Payment::create([
                'contract_id' => $contract->id,
                'client_id' => $contract->client_id,
                'amount' => $plan->final_price,
                'due_date' => $startDate->copy()->addMonths($i),
                'status' => 'pendiente',
            ]);
        }

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contrato creado exitosamente con ' . $months . ' pagos generados.');
    }

    public function show(Contract $contract)
    {
        $contract->load('client', 'plan.adType', 'screens.municipality', 'payments');
        return view('contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $clients = Client::where('status', 'activo')->orderBy('business_name')->get();
        $plans = Plan::where('active', true)->with('adType')->get();
        $screens = Screen::where('status', 'activa')->with('municipality')->get();
        $contract->load('screens');

        return view('contracts.edit', compact('contract', 'clients', 'plans', 'screens'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'payment_frequency' => 'required|in:mensual,trimestral,semestral,anual',
            'status' => 'required|in:activo,vencido,cancelado,suspendido',
            'notes' => 'nullable|string',
            'screens' => 'nullable|array',
            'screens.*.id' => 'required|exists:screens,id',
            'screens.*.space_number' => 'required|integer|min:1',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        $months = Carbon::parse($validated['start_date'])->diffInMonths(Carbon::parse($validated['end_date']));
        if ($months < 1) $months = 1;

        $contract->update([
            'client_id' => $validated['client_id'],
            'plan_id' => $validated['plan_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'monthly_amount' => $plan->final_price,
            'total_amount' => $plan->final_price * $months,
            'payment_frequency' => $validated['payment_frequency'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        if (!empty($validated['screens'])) {
            $syncData = [];
            foreach ($validated['screens'] as $screenData) {
                $syncData[$screenData['id']] = ['space_number' => $screenData['space_number']];
            }
            $contract->screens()->sync($syncData);
        }

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contrato actualizado exitosamente.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('contracts.index')
            ->with('success', 'Contrato eliminado exitosamente.');
    }
}
