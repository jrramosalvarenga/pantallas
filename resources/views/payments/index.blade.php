@extends('layouts.app')

@section('title', 'Pagos')
@section('page-title', 'Pagos')
@section('page-subtitle', 'Control de cobros y pagos de clientes')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600" style="font-size:20px">schedule</span>
            </div>
            <div>
                <p class="text-xs text-gray-400">Por cobrar</p>
                <p class="text-lg font-bold text-gray-800">L {{ number_format($stats['pendiente'], 2) }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600" style="font-size:20px">warning</span>
            </div>
            <div>
                <p class="text-xs text-gray-400">Vencido</p>
                <p class="text-lg font-bold text-red-600">L {{ number_format($stats['vencido'], 2) }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600" style="font-size:20px">paid</span>
            </div>
            <div>
                <p class="text-xs text-gray-400">Cobrado este mes</p>
                <p class="text-lg font-bold text-emerald-600">L {{ number_format($stats['cobrado_mes'], 2) }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-[10px] text-gray-500 uppercase font-semibold mb-1">Buscar</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cliente..."
                   class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none w-48">
        </div>
        <div>
            <label class="block text-[10px] text-gray-500 uppercase font-semibold mb-1">Estado</label>
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="pagado" {{ request('status') === 'pagado' ? 'selected' : '' }}>Pagado</option>
                <option value="parcial" {{ request('status') === 'parcial' ? 'selected' : '' }}>Parcial</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] text-gray-500 uppercase font-semibold mb-1">Desde</label>
            <input type="date" name="from" value="{{ request('from') }}" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
        </div>
        <div>
            <label class="block text-[10px] text-gray-500 uppercase font-semibold mb-1">Hasta</label>
            <input type="date" name="to" value="{{ request('to') }}" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
        </div>
        <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-xl text-sm font-medium hover:bg-brand-700 transition">Filtrar</button>
        <a href="{{ route('payments.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition">Limpiar</a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Cliente</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Contrato</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Vencimiento</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Monto</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Pagado</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                @php
                $isOverdue = $payment->status === 'pendiente' && $payment->due_date->isPast();
                $pColors = ['pendiente' => 'blue', 'pagado' => 'emerald', 'vencido' => 'red', 'parcial' => 'amber'];
                $pc = $isOverdue ? 'red' : ($pColors[$payment->status] ?? 'gray');
                $label = $isOverdue ? 'Vencido' : ucfirst($payment->status);
                @endphp
                <tr class="hover:bg-gray-50/50 transition {{ $isOverdue ? 'bg-red-50/30' : '' }}">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $payment->client->business_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $payment->contract->contract_number ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-600">
                        {{ $payment->due_date->format('d/m/Y') }}
                        @if($isOverdue)
                        <span class="text-[10px] text-red-500 block">hace {{ $payment->due_date->diffForHumans() }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-800">L {{ number_format($payment->amount, 2) }}</td>
                    <td class="px-5 py-3 text-right text-gray-600">L {{ number_format($payment->amount_paid, 2) }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-block text-[10px] font-semibold px-2.5 py-1 rounded-full bg-{{ $pc }}-50 text-{{ $pc }}-600">{{ $label }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        @if($payment->status !== 'pagado')
                        <div x-data="{ showModal: false }">
                            <button @click="showModal = true" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-medium hover:bg-emerald-100 transition">
                                Cobrar
                            </button>
                            <template x-if="showModal">
                                <div class="fixed inset-0 bg-black/30 z-50 flex items-center justify-center p-4" @click.self="showModal = false">
                                    <form method="POST" action="{{ route('payments.register', $payment) }}" class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl" @click.stop>
                                        @csrf
                                        <h4 class="text-base font-semibold text-gray-800 mb-1">Registrar Pago</h4>
                                        <p class="text-sm text-gray-400 mb-4">{{ $payment->client->business_name ?? '' }} — Vence {{ $payment->due_date->format('d/m/Y') }}</p>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Monto *</label>
                                                <input type="number" name="amount_paid" step="0.01" value="{{ $payment->amount - $payment->amount_paid }}" required
                                                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Método *</label>
                                                <select name="payment_method" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                                                    <option value="efectivo">Efectivo</option>
                                                    <option value="transferencia">Transferencia</option>
                                                    <option value="cheque">Cheque</option>
                                                    <option value="tarjeta">Tarjeta</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Referencia</label>
                                                <input type="text" name="reference" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Nº comprobante">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                                                <input type="text" name="notes" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                                            </div>
                                        </div>
                                        <div class="flex gap-3 mt-5">
                                            <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition">Confirmar</button>
                                            <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </template>
                        </div>
                        @else
                        <span class="text-xs text-gray-400">
                            <span class="material-symbols-outlined text-emerald-500" style="font-size:16px">check</span>
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center">
                        <span class="material-symbols-outlined text-gray-300" style="font-size:48px">payments</span>
                        <p class="text-gray-400 mt-2">No hay pagos registrados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $payments->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
