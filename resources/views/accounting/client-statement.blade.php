@extends('layouts.app')

@section('title', 'Estado de Cuenta — ' . $client->business_name)
@section('page-title', 'Estado de Cuenta')
@section('page-subtitle', $client->business_name)

@section('content')
<div class="mb-4">
    <a href="{{ route('accounting.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600">
        <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver a contabilidad
    </a>
</div>

{{-- Summary --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400 mb-1">Total facturado</p>
        <p class="text-xl font-bold text-gray-800">L {{ number_format($totalBilled, 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400 mb-1">Total pagado</p>
        <p class="text-xl font-bold text-emerald-600">L {{ number_format($totalPaid, 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400 mb-1">Saldo pendiente</p>
        <p class="text-xl font-bold text-amber-600">L {{ number_format($pendingBalance, 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400 mb-1">Vencido</p>
        <p class="text-xl font-bold text-red-600">L {{ number_format($overdueBalance, 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Payments --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Pagos</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500">Fecha</th>
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500">Contrato</th>
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500">Estado</th>
                    <th class="text-right px-5 py-2.5 text-xs font-semibold text-gray-500">Monto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-2.5 text-gray-600">{{ $payment->due_date->format('d/m/Y') }}</td>
                    <td class="px-5 py-2.5 text-gray-600">{{ $payment->contract->contract_number ?? '—' }}</td>
                    <td class="px-5 py-2.5">
                        @php
                            $pColors = ['pendiente' => 'bg-amber-100 text-amber-700', 'pagado' => 'bg-emerald-100 text-emerald-700', 'vencido' => 'bg-red-100 text-red-700', 'parcial' => 'bg-blue-100 text-blue-700'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $pColors[$payment->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-2.5 text-right font-semibold text-gray-700">L {{ number_format($payment->amount, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-6 text-center text-gray-400 text-sm">Sin pagos registrados</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Incomes --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Ingresos registrados</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500">Fecha</th>
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500">Descripción</th>
                    <th class="text-right px-5 py-2.5 text-xs font-semibold text-gray-500">Monto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($incomes as $income)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-2.5 text-gray-600">{{ $income->date->format('d/m/Y') }}</td>
                    <td class="px-5 py-2.5 text-gray-600">{{ $income->description }}</td>
                    <td class="px-5 py-2.5 text-right font-semibold text-emerald-600">L {{ number_format($income->amount, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-5 py-6 text-center text-gray-400 text-sm">Sin ingresos registrados</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
