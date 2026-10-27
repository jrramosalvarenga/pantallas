@extends('layouts.app')

@section('title', $client->business_name)
@section('page-title', $client->business_name)
@section('page-subtitle', 'Detalle del cliente')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
    {{-- Info principal --}}
    <div class="lg:w-1/3">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 bg-brand-100 text-brand-600 rounded-2xl flex items-center justify-center font-bold text-lg">
                    {{ strtoupper(substr($client->business_name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $client->business_name }}</h3>
                    @php
                        $statusColors = [
                            'activo' => 'bg-emerald-50 text-emerald-700',
                            'inactivo' => 'bg-gray-100 text-gray-600',
                            'suspendido' => 'bg-red-50 text-red-600',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$client->status] ?? '' }} mt-1">
                        {{ ucfirst($client->status) }}
                    </span>
                </div>
            </div>

            <div class="space-y-4 text-sm">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:18px">person</span>
                    <div>
                        <p class="text-xs text-gray-400">Contacto</p>
                        <p class="text-gray-700 font-medium">{{ $client->contact_name }}</p>
                    </div>
                </div>
                @if($client->email)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:18px">mail</span>
                    <div>
                        <p class="text-xs text-gray-400">Correo</p>
                        <p class="text-gray-700">{{ $client->email }}</p>
                    </div>
                </div>
                @endif
                @if($client->phone)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:18px">call</span>
                    <div>
                        <p class="text-xs text-gray-400">Teléfono</p>
                        <p class="text-gray-700">{{ $client->phone }}</p>
                    </div>
                </div>
                @endif
                @if($client->rtn)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:18px">badge</span>
                    <div>
                        <p class="text-xs text-gray-400">RTN</p>
                        <p class="text-gray-700">{{ $client->rtn }}</p>
                    </div>
                </div>
                @endif
                @if($client->address)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:18px">location_on</span>
                    <div>
                        <p class="text-xs text-gray-400">Dirección</p>
                        <p class="text-gray-700">{{ $client->address }}</p>
                    </div>
                </div>
                @endif
                @if($client->municipality)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:18px">map</span>
                    <div>
                        <p class="text-xs text-gray-400">Municipio</p>
                        <p class="text-gray-700">{{ $client->municipality->name }}</p>
                    </div>
                </div>
                @endif
                @if($client->notes)
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:18px">notes</span>
                    <div>
                        <p class="text-xs text-gray-400">Notas</p>
                        <p class="text-gray-700">{{ $client->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="flex gap-2 mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('clients.edit', $client) }}" class="flex-1 inline-flex items-center justify-center gap-1 bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                    <span class="material-symbols-outlined" style="font-size:16px">edit</span> Editar
                </a>
                <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('¿Eliminar este cliente?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center gap-1 bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-red-100 transition">
                        <span class="material-symbols-outlined" style="font-size:16px">delete</span> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Contratos y pagos --}}
    <div class="lg:w-2/3 space-y-6">
        {{-- Contratos --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-500" style="font-size:18px">description</span>
                Contratos
            </h3>
            @forelse($client->contracts as $contract)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-700">{{ $contract->contract_number }}</p>
                    <p class="text-xs text-gray-400">{{ $contract->plan->name ?? '' }} · {{ $contract->start_date->format('d/m/Y') }} — {{ $contract->end_date->format('d/m/Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-800">L {{ number_format($contract->monthly_amount, 2) }}/mes</p>
                    <span class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $contract->status === 'activo' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($contract->status) }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Sin contratos registrados</p>
            @endforelse
        </div>

        {{-- Pagos --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-500" style="font-size:18px">payments</span>
                Historial de Pagos
            </h3>
            @forelse($client->payments->take(10) as $payment)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    @php
                        $payStatusColors = [
                            'pagado' => 'bg-emerald-100 text-emerald-600',
                            'pendiente' => 'bg-amber-100 text-amber-600',
                            'vencido' => 'bg-red-100 text-red-600',
                            'parcial' => 'bg-blue-100 text-blue-600',
                        ];
                    @endphp
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $payStatusColors[$payment->status] ?? 'bg-gray-100 text-gray-600' }}">
                        <span class="material-symbols-outlined" style="font-size:16px">
                            {{ $payment->status === 'pagado' ? 'check_circle' : ($payment->status === 'vencido' ? 'warning' : 'schedule') }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700">Vencimiento: {{ $payment->due_date->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst($payment->status) }}</p>
                    </div>
                </div>
                <span class="text-sm font-semibold text-gray-800">L {{ number_format($payment->amount, 2) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Sin pagos registrados</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
