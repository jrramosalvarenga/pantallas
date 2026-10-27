<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\Screen;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $totalClients = Client::where('status', 'activo')->count();
        $totalScreens = Screen::count();
        $activeContracts = Contract::where('status', 'activo')->count();

        $monthlyIncome = Income::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $monthlyExpenses = Expense::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $monthlyProfit = $monthlyIncome - $monthlyExpenses;

        $pendingPayments = Payment::where('status', 'pendiente')->count();
        $overduePayments = Payment::where('status', 'pendiente')
            ->where('due_date', '<', now())
            ->count();

        $upcomingPayments = Payment::with('client', 'contract')
            ->where('status', 'pendiente')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(15))
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        $screens = Screen::with('municipality')->get();

        $monthlyIncomeData = [];
        $monthlyExpenseData = [];
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');
            $monthlyIncomeData[] = Income::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $monthlyExpenseData[] = Expense::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
        }

        $loan = Loan::where('status', 'activo')->first();

        $recentContracts = Contract::with('client', 'plan')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalClients', 'totalScreens', 'activeContracts',
            'monthlyIncome', 'monthlyExpenses', 'monthlyProfit',
            'pendingPayments', 'overduePayments', 'upcomingPayments',
            'screens', 'labels', 'monthlyIncomeData', 'monthlyExpenseData',
            'loan', 'recentContracts'
        ));
    }
}
