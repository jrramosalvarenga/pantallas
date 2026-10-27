@extends('layouts.app')

@section('title', $screen->name)
@section('page-title', $screen->name)
@section('page-subtitle', $screen->code . ' · ' . ($screen->municipality->name ?? ''))

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
    {{-- Info principal --}}
    <div class="lg:w-1/3 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-brand-600 to-brand-700 p-6 text-white">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size:28px">tv</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">{{ $screen->name }}</h3>
                        <p class="text-sm text-brand-200">{{ $screen->code }}</p>
                    </div>
                </div>
                @php
                    $screenStatusColors = [
                        'activa' => 'bg-emerald-400/20 text-emerald-100',
                        'mantenimiento' => 'bg-amber-400/20 text-amber-100',
                        'inactiva' => 'bg-red-400/20 text-red-100',
                    ];
                @endphp
                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $screenStatusColors[$screen->status] ?? '' }}">
                    {{ ucfirst($screen->status) }}
                </span>
            </div>

            <div class="p-6 space-y-4 text-sm">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:18px">location_on</span>
                    <div>
                        <p class="text-xs text-gray-400">Ubicación</p>
                        <p class="text-gray-700">{{ $screen->address }}, {{ $screen->municipality->name ?? '' }}</p>
                    </div>
                </div>

                {{-- Ocupación --}}
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-xs font-medium text-gray-500">Ocupación</span>
                        <span class="text-xs font-bold">{{ $screen->occupied_spaces }}/{{ $screen->total_spaces }} ({{ $screen->occupancy_percent }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="h-3 rounded-full {{ $screen->occupancy_percent > 75 ? 'bg-emerald-500' : ($screen->occupancy_percent > 40 ? 'bg-amber-500' : 'bg-brand-500') }}"
                             style="width: {{ $screen->occupancy_percent }}%"></div>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:18px">schedule</span>
                    <div>
                        <p class="text-xs text-gray-400">Horario</p>
                        <p class="text-gray-700">{{ $screen->operating_hours }} · Spot {{ $screen->spot_duration_seconds }}s · Ciclo {{ $screen->cycle_duration_seconds }}s</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:18px">replay</span>
                    <div>
                        <p class="text-xs text-gray-400">Reproducciones por día</p>
                        <p class="text-gray-700 font-semibold">{{ number_format($screen->daily_plays) }} veces cada anuncio</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Financiero --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Información financiera</h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Inversión</span>
                    <span class="font-semibold text-gray-800">L {{ number_format($screen->investment_cost, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Valor contable</span>
                    <span class="font-semibold text-gray-800">L {{ number_format($screen->book_value, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Depreciación mensual</span>
                    <span class="font-semibold text-red-600">- L {{ number_format($screen->monthly_depreciation, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Vida útil</span>
                    <span class="font-medium text-gray-700">{{ $screen->useful_life_months }} meses</span>
                </div>
                @if($screen->purchase_date)
                <div class="flex justify-between">
                    <span class="text-gray-500">Fecha de compra</span>
                    <span class="font-medium text-gray-700">{{ $screen->purchase_date->format('d/m/Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('screens.edit', $screen) }}" class="flex-1 inline-flex items-center justify-center gap-1 bg-gray-100 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                <span class="material-symbols-outlined" style="font-size:16px">edit</span> Editar
            </a>
            <form method="POST" action="{{ route('screens.destroy', $screen) }}" onsubmit="return confirm('¿Eliminar esta pantalla?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center gap-1 bg-red-50 text-red-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-100 transition">
                    <span class="material-symbols-outlined" style="font-size:16px">delete</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Tabs: contratos y mantenimiento --}}
    <div class="lg:w-2/3 space-y-6">
        {{-- Contratos asignados --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-500" style="font-size:18px">description</span>
                Espacios ocupados (contratos activos)
            </h3>
            @forelse($screen->contracts->where('status', 'activo') as $contract)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand-100 text-brand-600 rounded-lg flex items-center justify-center text-xs font-bold">
                        #{{ $contract->pivot->space_number }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $contract->client->business_name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $contract->contract_number }} · Hasta {{ $contract->end_date->format('d/m/Y') }}</p>
                    </div>
                </div>
                <span class="text-sm font-semibold text-gray-700">L {{ number_format($contract->monthly_amount, 2) }}/mes</span>
            </div>
            @empty
            <div class="text-center py-6">
                <span class="material-symbols-outlined text-gray-300" style="font-size:40px">event_available</span>
                <p class="text-sm text-gray-400 mt-2">No hay contratos activos para esta pantalla</p>
            </div>
            @endforelse
        </div>

        {{-- Bitácora de mantenimiento --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500" style="font-size:18px">build</span>
                    Bitácora de Mantenimiento
                </h3>
                <a href="{{ route('maintenance.create', ['screen_id' => $screen->id]) }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium flex items-center gap-1">
                    <span class="material-symbols-outlined" style="font-size:14px">add</span> Registrar
                </a>
            </div>
            @forelse($screen->maintenanceLogs as $log)
            <div class="flex items-start gap-3 py-3 border-b border-gray-50 last:border-0">
                @php
                    $typeColors = [
                        'preventivo' => 'bg-blue-100 text-blue-600',
                        'correctivo' => 'bg-amber-100 text-amber-600',
                        'emergencia' => 'bg-red-100 text-red-600',
                    ];
                @endphp
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $typeColors[$log->type] ?? 'bg-gray-100 text-gray-600' }}">
                    <span class="material-symbols-outlined" style="font-size:16px">
                        {{ $log->type === 'preventivo' ? 'verified' : ($log->type === 'correctivo' ? 'handyman' : 'warning') }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-700">{{ $log->title }}</p>
                        <span class="text-xs text-gray-400">{{ $log->date->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $log->description }}</p>
                    <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                        <span>{{ ucfirst($log->type) }}</span>
                        @if($log->cost > 0)
                        <span>L {{ number_format($log->cost, 2) }}</span>
                        @endif
                        @if($log->technician)
                        <span>{{ $log->technician }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-6">
                <span class="material-symbols-outlined text-gray-300" style="font-size:40px">build_circle</span>
                <p class="text-sm text-gray-400 mt-2">Sin registros de mantenimiento</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
