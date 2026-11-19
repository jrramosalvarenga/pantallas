<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month');

        $incomeQuery = Income::whereYear('date', $year);
        $expenseQuery = Expense::whereYear('date', $year);

        if ($month) {
            $incomeQuery->whereMonth('date', $month);
            $expenseQuery->whereMonth('date', $month);
        }

        $totalIncome = (float) $incomeQuery->sum('amount');
        $totalExpenses = (float) $expenseQuery->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;

        $pendingPayments = Payment::where('status', 'pendiente')->sum('amount');
        $overduePayments = Payment::where('status', 'pendiente')
            ->where('due_date', '<', now())
            ->sum('amount');

        $clientRevenue = Income::select('client_id', DB::raw('SUM(amount) as total'))
            ->whereYear('date', $year)
            ->whereNotNull('client_id')
            ->groupBy('client_id')
            ->with('client')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $expensesByCategory = Expense::select('expense_category_id', DB::raw('SUM(amount) as total'))
            ->whereYear('date', $year)
            ->groupBy('expense_category_id')
            ->with('category')
            ->orderByDesc('total')
            ->get();

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $inc = (float) Income::whereYear('date', $year)->whereMonth('date', $m)->sum('amount');
            $exp = (float) Expense::whereYear('date', $year)->whereMonth('date', $m)->sum('amount');
            $monthlyData[] = [
                'month' => $m,
                'month_name' => Carbon::create($year, $m, 1)->translatedFormat('M'),
                'income' => $inc,
                'expenses' => $exp,
                'profit' => $inc - $exp,
            ];
        }

        $recentIncomes = Income::with('client')->latest('date')->limit(10)->get();
        $recentExpenses = Expense::with('category')->latest('date')->limit(10)->get();

        $availableYears = collect(range(now()->year - 2, now()->year + 1));

        return view('accounting.index', compact(
            'year', 'month', 'totalIncome', 'totalExpenses', 'netProfit',
            'pendingPayments', 'overduePayments', 'clientRevenue',
            'expensesByCategory', 'monthlyData', 'recentIncomes', 'recentExpenses',
            'availableYears'
        ));
    }

    public function clientStatement(Request $request, Client $client)
    {
        $payments = $client->payments()->with('contract')->orderBy('due_date')->get();
        $incomes = Income::where('client_id', $client->id)->orderBy('date')->get();

        $totalBilled = $payments->sum('amount');
        $totalPaid = $payments->where('status', 'pagado')->sum('amount');
        $pendingBalance = $payments->whereIn('status', ['pendiente', 'parcial'])->sum('amount');
        $overdueBalance = $payments->where('status', 'pendiente')
            ->filter(fn ($p) => $p->due_date->isPast())
            ->sum('amount');

        return view('accounting.client-statement', compact(
            'client', 'payments', 'incomes', 'totalBilled', 'totalPaid',
            'pendingBalance', 'overdueBalance'
        ));
    }
}
