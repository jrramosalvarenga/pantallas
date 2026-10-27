<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $totalIncome = Income::whereYear('date', $year)->sum('amount');
        $totalExpenses = Expense::whereYear('date', $year)->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;

        $loan = Loan::where('status', 'activo')->first();

        $monthlyLabels = [];
        $monthlyIncomeData = [];
        $monthlyExpenseData = [];
        $monthlyBreakdown = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthName = \Carbon\Carbon::create($year, $m, 1)->translatedFormat('M');
            $monthlyLabels[] = $monthName;

            $inc = Income::whereYear('date', $year)->whereMonth('date', $m)->sum('amount');
            $exp = Expense::whereYear('date', $year)->whereMonth('date', $m)->sum('amount');

            $monthlyIncomeData[] = (float) $inc;
            $monthlyExpenseData[] = (float) $exp;
            $monthlyBreakdown[] = [
                'month' => $monthName,
                'income' => $inc,
                'expenses' => $exp,
                'profit' => $inc - $exp,
            ];
        }

        $topExpenseCategories = Expense::select('expense_category_id', DB::raw('SUM(amount) as total'))
            ->whereYear('date', $year)
            ->groupBy('expense_category_id')
            ->with('category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $availableYears = collect(range(now()->year - 2, now()->year + 1));

        return view('finances.index', compact(
            'year', 'totalIncome', 'totalExpenses', 'netProfit', 'loan',
            'monthlyLabels', 'monthlyIncomeData', 'monthlyExpenseData',
            'monthlyBreakdown', 'topExpenseCategories', 'availableYears'
        ));
    }
}
