@extends('layouts.app')

@section('title', 'Contrato ' . $contract->contract_number)
@section('page-title', 'Contrato ' . $contract->contract_number)
@section('page-subtitle', $contract->client->business_name ?? '')

@section('content')
<div class="max-w-5xl space-y-6">
    {{-- Header card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-xl font-bold text-gray-800">{{ $contract->contract_number }}</h3>
                    @php
                    $colors = ['activo' => 'emerald', 'vencido' => 'red', 'cancelado' => 'gray', 'suspendido' => 'amber'];
                    $c = $colors[$contract->status] ?? 'gray';
                    @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-{{ $c }}-50 text-{{ $c }}-600">{{ ucfirst($contract->status) }}</span>
                </div>
                <p class="text-sm text-gray-500">Cliente: <span class="font-medium text-gray-700">{{ $contract->client->business_name ?? '—' }}</span></p>
                <p class="text-sm text-gray-500">Plan: <span class="font-medium text-gray-700">{{ $contract->plan->name ?? '—' }}</span> ({{ $contract->plan->adType->name ?? '' }})</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('contracts.edit', $contract) }}" class="px-4 py-2 bg-amber-50 text-amber-600 rounded-xl text-sm font-medium hover:bg-amber-100 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined" style="font-size:16px">edit</span> Editar
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-100">
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-semibold">Monto Mensual</p>
                <p class="text-lg font-bold text-gray-800">L {{ number_format($contract->monthly_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-semibold">Total Contrato</p>
                <p class="text-lg font-bold text-gray-800">L {{ number_format($contract->total_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-semibold">Inicio</p>
                <p class="text-sm font-semibold text-gray-700">{{ $contract->start_date->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-semibold">Fin</p>
                <p class="text-sm font-semibold text-gray-700">{{ $contract->end_date->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Screens --}}
    @if($contract->screens->count())
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-brand-500" style="font-size:20px">tv</span>
            Pantallas Asignadas
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($contract->screens as $screen)
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <div class="w-10 h-10 bg-brand-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-brand-600" style="font-size:20px">tv</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">{{ $screen->name }}</p>
                    <p class="text-xs text-gray-400">Espacio #{{ $screen->pivot->space_number }} · {{ $screen->municipality->name ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Payments timeline --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-brand-500" style="font-size:20px">payments</span>
            Historial de Pagos
            <span class="ml-auto text-xs text-gray-400">{{ $contract->payments->count() }} cuota(s)</span>
        </h3>

        <div class="space-y-2">
            @foreach($contract->payments->sortBy('due_date') as $payment)
            @php
            $pColors = ['pendiente' => 'blue', 'pagado' => 'emerald', 'vencido' => 'red', 'parcial' => 'amber'];
            $isOverdue = $payment->status === 'pendiente' && $payment->due_date->isPast();
            $pc = $isOverdue ? 'red' : ($pColors[$payment->status] ?? 'gray');
            @endphp
            <div class="flex items-center gap-4 p-3 rounded-xl border border-gray-100 hover:bg-gray-50/50 transition" x-data="{ showRegister: false }">
                <div class="w-10 h-10 rounded-full bg-{{ $pc }}-100 flex items-center justify-center flex-shrink-0">
                    @if($payment->status === 'pagado')
                    <span class="material-symbols-outlined text-{{ $pc }}-600" style="font-size:20px">check_circle</span>
                    @elseif($isOverdue)
                    <span class="material-symbols-outlined text-{{ $pc }}-600" style="font-size:20px">warning</span>
                    @else
                    <span class="material-symbols-outlined text-{{ $pc }}-600" style="font-size:20px">schedule</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700">
                        Cuota — {{ $payment->due_date->translatedFormat('d M Y') }}
                        @if($isOverdue)
                        <span class="text-[10px] text-red-500 font-semibold ml-1">VENCIDO</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ ucfirst($payment->status) }}
                        @if($payment->paid_date) · Pagado {{ $payment->paid_date->format('d/m/Y') }} @endif
                        @if($payment->payment_method) · {{ $payment->payment_method }} @endif
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-semibold text-gray-800">L {{ number_format($payment->amount, 2) }}</p>
                    @if($payment->amount_paid > 0 && $payment->status !== 'pagado')
                    <p class="text-[10px] text-amber-600">Abonado: L {{ number_format($payment->amount_paid, 2) }}</p>
                    @endif
                </div>
                @if($payment->status !== 'pagado')
                <button @click="showRegister = !showRegister" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-medium hover:bg-emerald-100 transition flex-shrink-0">
                    Registrar Pago
                </button>
                @endif

                {{-- Inline register form --}}
                <template x-if="showRegister">
                    <div class="fixed inset-0 bg-black/30 z-50 flex items-center justify-center p-4" @click.self="showRegister = false">
                        <form method="POST" action="{{ route('payments.register', $payment) }}" class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl" @click.stop>
                            @csrf
                            <h4 class="text-base font-semibold text-gray-800 mb-4">Registrar Pago</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Monto a pagar *</label>
                                    <input type="number" name="amount_paid" step="0.01" value="{{ $payment->amount - $payment->amount_paid }}" required
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Método de pago *</label>
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
                                <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition">Confirmar Pago</button>
                                <button type="button" @click="showRegister = false" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </template>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
