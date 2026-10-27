@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen general de tu negocio')

@section('content')
{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
    {{-- Ingresos del mes --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600" style="font-size:22px">trending_up</span>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Este mes</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">L {{ number_format($monthlyIncome, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Ingresos del mes</p>
    </div>

    {{-- Egresos del mes --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600" style="font-size:22px">trending_down</span>
            </div>
            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">Egresos</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">L {{ number_format($monthlyExpenses, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Gastos del mes</p>
    </div>

    {{-- Ganancia --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 {{ $monthlyProfit >= 0 ? 'bg-blue-100' : 'bg-orange-100' }} rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined {{ $monthlyProfit >= 0 ? 'text-blue-600' : 'text-orange-600' }}" style="font-size:22px">account_balance</span>
            </div>
            <span class="text-xs font-medium {{ $monthlyProfit >= 0 ? 'text-blue-600 bg-blue-50' : 'text-orange-600 bg-orange-50' }} px-2 py-1 rounded-full">
                {{ $monthlyProfit >= 0 ? 'Ganancia' : 'Pérdida' }}
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">L {{ number_format(abs($monthlyProfit), 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Resultado del mes</p>
    </div>

    {{-- Clientes activos --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-purple-600" style="font-size:22px">groups</span>
            </div>
            <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-full">{{ $activeContracts }} contratos</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $totalClients }}</p>
        <p class="text-xs text-gray-400 mt-1">Clientes activos</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Gráfico ingresos vs egresos --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Ingresos vs Egresos (6 meses)</h3>
        <canvas id="revenueChart" height="120"></canvas>
    </div>

    {{-- Estado de pantallas --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Ocupación de Pantallas</h3>
        @forelse($screens as $screen)
        <div class="mb-4 last:mb-0">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-sm font-medium text-gray-700">{{ $screen->name }}</span>
                <span class="text-xs text-gray-500">{{ $screen->occupied_spaces }}/{{ $screen->total_spaces }}</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3">
                <div class="h-3 rounded-full transition-all duration-500 {{ $screen->occupancy_percent > 75 ? 'bg-emerald-500' : ($screen->occupancy_percent > 40 ? 'bg-amber-500' : 'bg-brand-500') }}"
                     style="width: {{ $screen->occupancy_percent }}%"></div>
            </div>
            <div class="flex justify-between mt-1">
                <span class="text-[10px] text-gray-400">{{ $screen->municipality->name ?? '' }}</span>
                <span class="text-[10px] font-semibold {{ $screen->occupancy_percent > 75 ? 'text-emerald-600' : ($screen->occupancy_percent > 40 ? 'text-amber-600' : 'text-brand-600') }}">{{ $screen->occupancy_percent }}%</span>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <span class="material-symbols-outlined text-gray-300" style="font-size:48px">tv_off</span>
            <p class="text-sm text-gray-400 mt-2">No hay pantallas registradas</p>
        </div>
        @endforelse

        @if($loan)
        <div class="mt-6 pt-4 border-t border-gray-100">
            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Préstamo {{ $loan->bank_name }}</h4>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Cuota mensual</span>
                <span class="font-semibold text-gray-700">L {{ number_format($loan->monthly_payment, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="text-gray-500">Meses restantes</span>
                <span class="font-semibold text-gray-700">{{ $loan->remaining_months }}</span>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Pagos próximos --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Pagos Próximos</h3>
            @if($overduePayments > 0)
            <span class="text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-full animate-pulse">
                {{ $overduePayments }} vencido{{ $overduePayments > 1 ? 's' : '' }}
            </span>
            @endif
        </div>
        @forelse($upcomingPayments as $payment)
        <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-xs font-bold
                    {{ $payment->due_date->isPast() ? 'bg-red-100 text-red-600' : ($payment->due_date->diffInDays(now()) <= 3 ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-600') }}">
                    {{ $payment->due_date->format('d') }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">{{ $payment->client->business_name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-400">Vence {{ $payment->due_date->format('d/m/Y') }}</p>
                </div>
            </div>
            <span class="text-sm font-semibold text-gray-800">L {{ number_format($payment->amount, 2) }}</span>
        </div>
        @empty
        <div class="text-center py-6">
            <span class="material-symbols-outlined text-gray-300" style="font-size:40px">event_available</span>
            <p class="text-sm text-gray-400 mt-2">No hay pagos próximos</p>
        </div>
        @endforelse
    </div>

    {{-- Últimos contratos --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Contratos Recientes</h3>
            <a href="{{ route('contracts.index') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium">Ver todos</a>
        </div>
        @forelse($recentContracts as $contract)
        <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
            <div>
                <p class="text-sm font-medium text-gray-700">{{ $contract->client->business_name ?? 'N/A' }}</p>
                <p class="text-xs text-gray-400">{{ $contract->plan->name ?? '' }} · {{ $contract->contract_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold text-gray-800">L {{ number_format($contract->monthly_amount, 2) }}/mes</p>
                <span class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-full
                    {{ $contract->status === 'activo' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                    {{ ucfirst($contract->status) }}
                </span>
            </div>
        </div>
        @empty
        <div class="text-center py-6">
            <span class="material-symbols-outlined text-gray-300" style="font-size:40px">description</span>
            <p class="text-sm text-gray-400 mt-2">No hay contratos aún</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [
                    {
                        label: 'Ingresos',
                        data: @json($monthlyIncomeData),
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                    {
                        label: 'Egresos',
                        data: @json($monthlyExpenseData),
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderRadius: 8,
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
