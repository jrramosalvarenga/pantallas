<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Income;
use App\Models\Payment;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Income::with('client', 'contract')->latest('date');

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $incomes = $query->paginate(20);
        $clients = Client::orderBy('business_name')->get();
        $totalFiltered = $query->sum('amount');

        return view('incomes.index', compact('incomes', 'clients', 'totalFiltered'));
    }

    public function create()
    {
        $clients = Client::where('status', 'activo')->orderBy('business_name')->get();
        $contracts = Contract::where('status', 'activo')->with('client')->get();
        return view('incomes.create', compact('clients', 'contracts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Income::create($data);

        return redirect()->route('incomes.index')->with('success', 'Ingreso registrado correctamente.');
    }

    public function edit(Income $income)
    {
        $clients = Client::orderBy('business_name')->get();
        $contracts = Contract::with('client')->get();
        return view('incomes.edit', compact('income', 'clients', 'contracts'));
    }

    public function update(Request $request, Income $income)
    {
        $data = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $income->update($data);

        return redirect()->route('incomes.index')->with('success', 'Ingreso actualizado correctamente.');
    }

    public function destroy(Income $income)
    {
        $income->delete();
        return redirect()->route('incomes.index')->with('success', 'Ingreso eliminado.');
    }
}
