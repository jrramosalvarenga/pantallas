@extends('layouts.app')

@section('title', 'Resumen Financiero')
@section('page-title', 'Resumen Financiero')
@section('page-subtitle', 'Estado de pérdidas y ganancias — ' . $year)

@section('content')
{{-- Year selector --}}
<div class="flex items-center gap-3 mb-6">
    @foreach($availableYears as $y)
    <a href="{{ route('finances.index', ['year' => $y]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $y == $year ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        {{ $y }}
    </a>
    @endforeach
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600" style="font-size:22px">trending_up</span>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">{{ $year }}</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">L {{ number_format($totalIncome, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Ingresos totales</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600" style="font-size:22px">trending_down</span>
            </div>
            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">Egresos</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">L {{ number_format($totalExpenses, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Gastos totales</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 {{ $netProfit >= 0 ? 'bg-blue-100' : 'bg-orange-100' }} rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined {{ $netProfit >= 0 ? 'text-blue-600' : 'text-orange-600' }}" style="font-size:22px">account_balance</span>
            </div>
            <span class="text-xs font-medium {{ $netProfit >= 0 ? 'text-blue-600 bg-blue-50' : 'text-orange-600 bg-orange-50' }} px-2 py-1 rounded-full">
                {{ $netProfit >= 0 ? 'Ganancia' : 'Pérdida' }}
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">L {{ number_format(abs($netProfit), 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Resultado neto</p>
    </div>

    @if($loan)
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-purple-600" style="font-size:22px">savings</span>
            </div>
            <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Préstamo</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">L {{ number_format($loan->remaining_balance, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Saldo pendiente</p>
    </div>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Ingresos vs Egresos Mensual — {{ $year }}</h3>
        <canvas id="financeChart" height="130"></canvas>
    </div>

    {{-- Top categories --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Top Categorías de Gasto</h3>
        @forelse($topExpenseCategories as $catExpense)
        <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
            <span class="text-sm text-gray-600">{{ $catExpense->category->name ?? 'Sin categoría' }}</span>
            <span class="text-sm font-semibold text-gray-800">L {{ number_format($catExpense->total, 2) }}</span>
        </div>
        @empty
        <p class="text-sm text-gray-400 text-center py-4">Sin datos aún</p>
        @endforelse
    </div>
</div>

{{-- Monthly breakdown table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700">Desglose Mensual — {{ $year }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Mes</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Ingresos</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Egresos</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Resultado</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Margen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($monthlyBreakdown as $row)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3 font-medium text-gray-700">{{ ucfirst($row['month']) }}</td>
                    <td class="px-5 py-3 text-right text-emerald-600 font-medium">L {{ number_format($row['income'], 2) }}</td>
                    <td class="px-5 py-3 text-right text-red-500 font-medium">L {{ number_format($row['expenses'], 2) }}</td>
                    <td class="px-5 py-3 text-right font-semibold {{ $row['profit'] >= 0 ? 'text-gray-800' : 'text-orange-600' }}">
                        L {{ number_format($row['profit'], 2) }}
                    </td>
                    <td class="px-5 py-3">
                        @if($row['income'] > 0)
                            @php $margin = ($row['profit'] / $row['income']) * 100; @endphp
                            <div class="flex items-center gap-2">
                                <div class="w-16 bg-gray-100 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $margin >= 0 ? 'bg-emerald-500' : 'bg-red-400' }}" style="width: {{ min(abs($margin), 100) }}%"></div>
                                </div>
                                <span class="text-xs font-medium {{ $margin >= 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ number_format($margin, 0) }}%</span>
                            </div>
                        @else
                            <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-gray-200 bg-gray-50/50">
                    <td class="px-5 py-3 font-bold text-gray-700">Total Anual</td>
                    <td class="px-5 py-3 text-right font-bold text-emerald-600">L {{ number_format($totalIncome, 2) }}</td>
                    <td class="px-5 py-3 text-right font-bold text-red-500">L {{ number_format($totalExpenses, 2) }}</td>
                    <td class="px-5 py-3 text-right font-bold {{ $netProfit >= 0 ? 'text-gray-800' : 'text-orange-600' }}">L {{ number_format($netProfit, 2) }}</td>
                    <td class="px-5 py-3">
                        @if($totalIncome > 0)
                            <span class="text-xs font-bold {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ number_format(($netProfit / $totalIncome) * 100, 0) }}%</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Loan Details --}}
@if($loan)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-purple-600" style="font-size:22px">account_balance</span>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-gray-700">Préstamo — {{ $loan->bank_name }}</h3>
            <p class="text-xs text-gray-400">Estado: {{ ucfirst($loan->status) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-[10px] text-gray-400 uppercase font-semibold">Monto original</p>
            <p class="text-lg font-bold text-gray-800 mt-1">L {{ number_format($loan->principal_amount, 2) }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-[10px] text-gray-400 uppercase font-semibold">Cuota mensual</p>
            <p class="text-lg font-bold text-gray-800 mt-1">L {{ number_format($loan->monthly_payment, 2) }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-[10px] text-gray-400 uppercase font-semibold">Total a pagar</p>
            <p class="text-lg font-bold text-gray-800 mt-1">L {{ number_format($loan->total_to_pay, 2) }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-[10px] text-gray-400 uppercase font-semibold">Intereses totales</p>
            <p class="text-lg font-bold text-red-600 mt-1">L {{ number_format($loan->total_interest, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4">
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-[10px] text-gray-400 uppercase font-semibold">Tasa de interés</p>
            <p class="text-lg font-bold text-gray-800 mt-1">{{ number_format($loan->interest_rate * 100, 2) }}%</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-[10px] text-gray-400 uppercase font-semibold">Plazo total</p>
            <p class="text-lg font-bold text-gray-800 mt-1">{{ $loan->term_months }} meses</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-[10px] text-gray-400 uppercase font-semibold">Meses restantes</p>
            <p class="text-lg font-bold text-purple-600 mt-1">{{ $loan->remaining_months }}</p>
        </div>
    </div>

    {{-- Progress bar --}}
    <div class="mt-6">
        <div class="flex justify-between text-xs text-gray-500 mb-1.5">
            <span>Progreso del préstamo</span>
            <span>{{ $loan->term_months - $loan->remaining_months }} de {{ $loan->term_months }} cuotas</span>
        </div>
        @php $loanProgress = $loan->term_months > 0 ? (($loan->term_months - $loan->remaining_months) / $loan->term_months) * 100 : 0; @endphp
        <div class="w-full bg-gray-100 rounded-full h-3">
            <div class="h-3 rounded-full bg-gradient-to-r from-purple-500 to-brand-500 transition-all duration-500" style="width: {{ $loanProgress }}%"></div>
        </div>
    </div>

    @if($loan->notes)
    <div class="mt-4 p-3 bg-amber-50 rounded-xl">
        <p class="text-xs text-amber-700">{{ $loan->notes }}</p>
    </div>
    @endif
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('financeChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($monthlyLabels),
                datasets: [
                    {
                        label: 'Ingresos',
                        data: @json($monthlyIncomeData),
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderRadius: 6,
                        borderSkipped: false,
                    },
                    {
                        label: 'Egresos',
                        data: @json($monthlyExpenseData),
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderRadius: 6,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { usePointStyle: true, pointStyle: 'circle', padding: 20, font: { size: 11 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return ctx.dataset.label + ': L ' + ctx.raw.toLocaleString(); }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            callback: function(v) { return 'L ' + v.toLocaleString(); },
                            font: { size: 10 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }
});
</script>
@endpush
