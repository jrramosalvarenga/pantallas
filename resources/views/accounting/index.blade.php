@extends('layouts.app')

@section('title', 'Contabilidad')
@section('page-title', 'Contabilidad')
@section('page-subtitle', 'Estado financiero — ' . $year)

@section('content')
{{-- Year / Month filters --}}
<div class="flex items-center gap-3 mb-6 flex-wrap">
    @foreach($availableYears as $y)
    <a href="{{ route('accounting.index', ['year' => $y]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $y == $year ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        {{ $y }}
    </a>
    @endforeach
    <span class="text-gray-300">|</span>
    <a href="{{ route('accounting.index', ['year' => $year]) }}"
       class="px-3 py-2 rounded-xl text-xs font-semibold transition {{ !$month ? 'bg-brand-100 text-brand-700' : 'text-gray-500 hover:bg-gray-50' }}">
        Todo el año
    </a>
    @for($m = 1; $m <= 12; $m++)
    <a href="{{ route('accounting.index', ['year' => $year, 'month' => $m]) }}"
       class="px-3 py-2 rounded-xl text-xs font-semibold transition {{ $month == $m ? 'bg-brand-100 text-brand-700' : 'text-gray-500 hover:bg-gray-50' }}">
        {{ \Carbon\Carbon::create($year, $m, 1)->translatedFormat('M') }}
    </a>
    @endfor
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mb-3">
            <span class="material-symbols-outlined text-emerald-600" style="font-size:22px">trending_up</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">L {{ number_format($totalIncome, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Ingresos</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mb-3">
            <span class="material-symbols-outlined text-red-600" style="font-size:22px">trending_down</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">L {{ number_format($totalExpenses, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Egresos</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 {{ $netProfit >= 0 ? 'bg-blue-100' : 'bg-orange-100' }} rounded-xl flex items-center justify-center mb-3">
            <span class="material-symbols-outlined {{ $netProfit >= 0 ? 'text-blue-600' : 'text-orange-600' }}" style="font-size:22px">account_balance</span>
        </div>
        <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-gray-800' : 'text-orange-600' }}">L {{ number_format(abs($netProfit), 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $netProfit >= 0 ? 'Ganancia neta' : 'Pérdida neta' }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mb-3">
            <span class="material-symbols-outlined text-amber-600" style="font-size:22px">schedule</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">L {{ number_format($pendingPayments, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Por cobrar</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mb-3">
            <span class="material-symbols-outlined text-red-600" style="font-size:22px">warning</span>
        </div>
        <p class="text-2xl font-bold text-red-600">L {{ number_format($overduePayments, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Vencidos</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Ingresos vs Egresos — {{ $year }}</h3>
        <canvas id="accountingChart" height="130"></canvas>
    </div>

    {{-- Expense categories --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Gastos por Categoría</h3>
        @forelse($expensesByCategory as $cat)
        <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
            <span class="text-sm text-gray-600">{{ $cat->category->name ?? 'Sin categoría' }}</span>
            <span class="text-sm font-semibold text-gray-800">L {{ number_format($cat->total, 2) }}</span>
        </div>
        @empty
        <p class="text-sm text-gray-400 text-center py-4">Sin datos</p>
        @endforelse
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Top clients --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Top 10 Clientes por Ingreso</h3>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-50">
                @forelse($clientRevenue as $cr)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3">
                        @if($cr->client)
                        <a href="{{ route('accounting.client-statement', $cr->client) }}" class="font-medium text-gray-700 hover:text-brand-600">{{ $cr->client->business_name }}</a>
                        @else
                        <span class="text-gray-400">Sin cliente</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-emerald-600">L {{ number_format($cr->total, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="px-5 py-6 text-center text-gray-400 text-sm">Sin datos</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Monthly breakdown --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Desglose Mensual</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500">Mes</th>
                    <th class="text-right px-5 py-2.5 text-xs font-semibold text-gray-500">Ingresos</th>
                    <th class="text-right px-5 py-2.5 text-xs font-semibold text-gray-500">Egresos</th>
                    <th class="text-right px-5 py-2.5 text-xs font-semibold text-gray-500">Resultado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($monthlyData as $row)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-2.5 font-medium text-gray-700">{{ ucfirst($row['month_name']) }}</td>
                    <td class="px-5 py-2.5 text-right text-emerald-600">L {{ number_format($row['income'], 2) }}</td>
                    <td class="px-5 py-2.5 text-right text-red-500">L {{ number_format($row['expenses'], 2) }}</td>
                    <td class="px-5 py-2.5 text-right font-semibold {{ $row['profit'] >= 0 ? 'text-gray-800' : 'text-orange-600' }}">L {{ number_format($row['profit'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Recent transactions --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Últimos Ingresos</h3>
            <a href="{{ route('incomes.index') }}" class="text-xs text-brand-600 font-medium hover:text-brand-700">Ver todos</a>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-50">
                @foreach($recentIncomes as $income)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-700">{{ $income->description }}</p>
                        <p class="text-xs text-gray-400">{{ $income->client->business_name ?? '—' }} · {{ $income->date->format('d/m/Y') }}</p>
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-emerald-600">L {{ number_format($income->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Últimos Egresos</h3>
            <a href="{{ route('expenses.index') }}" class="text-xs text-brand-600 font-medium hover:text-brand-700">Ver todos</a>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-50">
                @foreach($recentExpenses as $expense)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-700">{{ $expense->description }}</p>
                        <p class="text-xs text-gray-400">{{ $expense->category->name ?? '—' }} · {{ $expense->date->format('d/m/Y') }}</p>
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-red-500">L {{ number_format($expense->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('accountingChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json(array_column($monthlyData, 'month_name')),
                datasets: [
                    { label: 'Ingresos', data: @json(array_column($monthlyData, 'income')), backgroundColor: 'rgba(16, 185, 129, 0.8)', borderRadius: 6, borderSkipped: false },
                    { label: 'Egresos', data: @json(array_column($monthlyData, 'expenses')), backgroundColor: 'rgba(239, 68, 68, 0.8)', borderRadius: 6, borderSkipped: false }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                plugins: { legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', padding: 20, font: { size: 11 } } },
                    tooltip: { callbacks: { label: function(c) { return c.dataset.label + ': L ' + c.raw.toLocaleString(); } } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { callback: function(v) { return 'L ' + v.toLocaleString(); }, font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    }
});
</script>
@endpush
