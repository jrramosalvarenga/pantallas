<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Screen;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('category', 'screen')->latest('date');

        if ($request->filled('category')) {
            $query->where('expense_category_id', $request->category);
        }
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $expenses = $query->paginate(20);
        $categories = ExpenseCategory::orderBy('name')->get();
        $totalFiltered = $query->sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'totalFiltered'));
    }

    public function create()
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        $screens = Screen::orderBy('name')->get();
        return view('expenses.create', compact('categories', 'screens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'screen_id' => 'nullable|exists:screens,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'recurrence' => 'required|in:unico,mensual,trimestral,anual',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Egreso registrado correctamente.');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        $screens = Screen::orderBy('name')->get();
        return view('expenses.edit', compact('expense', 'categories', 'screens'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'screen_id' => 'nullable|exists:screens,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'recurrence' => 'required|in:unico,mensual,trimestral,anual',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Egreso actualizado correctamente.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Egreso eliminado.');
    }
}
