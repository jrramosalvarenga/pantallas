@extends('layouts.app')

@section('title', 'Pantallas')
@section('page-title', 'Pantallas LED')
@section('page-subtitle', 'Gestión de pantallas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $screens->count() }} pantalla{{ $screens->count() !== 1 ? 's' : '' }} registrada{{ $screens->count() !== 1 ? 's' : '' }}</p>
    <a href="{{ route('screens.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
        <span class="material-symbols-outlined" style="font-size:18px">add_circle</span>
        Nueva Pantalla
    </a>
</div>

@if($screens->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <span class="material-symbols-outlined text-gray-300" style="font-size:64px">tv_off</span>
    <p class="text-gray-500 mt-3">No hay pantallas registradas</p>
    <a href="{{ route('screens.create') }}" class="inline-flex items-center gap-1 text-brand-600 font-semibold text-sm mt-2 hover:text-brand-700">
        <span class="material-symbols-outlined" style="font-size:16px">add</span> Agregar la primera
    </a>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach($screens as $screen)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow overflow-hidden">
        {{-- Header con gradiente --}}
        <div class="bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size:22px">tv</span>
                    </div>
                    <div>
                        <h3 class="font-semibold">{{ $screen->name }}</h3>
                        <p class="text-xs text-brand-200">{{ $screen->code }}</p>
                    </div>
                </div>
                @php
                    $screenStatusColors = [
                        'activa' => 'bg-emerald-400/20 text-emerald-100',
                        'mantenimiento' => 'bg-amber-400/20 text-amber-100',
                        'inactiva' => 'bg-red-400/20 text-red-100',
                    ];
                @endphp
                <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full {{ $screenStatusColors[$screen->status] ?? '' }}">
                    {{ ucfirst($screen->status) }}
                </span>
            </div>
        </div>

        <div class="p-6 space-y-4">
            {{-- Ubicación --}}
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="material-symbols-outlined" style="font-size:16px">location_on</span>
                {{ $screen->municipality->name ?? '—' }} · {{ $screen->address }}
            </div>

            {{-- Ocupación --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-medium text-gray-500">Ocupación</span>
                    <span class="text-xs font-bold {{ $screen->occupancy_percent > 75 ? 'text-emerald-600' : ($screen->occupancy_percent > 40 ? 'text-amber-600' : 'text-brand-600') }}">
                        {{ $screen->occupied_spaces }}/{{ $screen->total_spaces }} espacios ({{ $screen->occupancy_percent }}%)
                    </span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full transition-all {{ $screen->occupancy_percent > 75 ? 'bg-emerald-500' : ($screen->occupancy_percent > 40 ? 'bg-amber-500' : 'bg-brand-500') }}"
                         style="width: {{ $screen->occupancy_percent }}%"></div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <p class="text-lg font-bold text-gray-800">L {{ number_format($screen->investment_cost, 0) }}</p>
                    <p class="text-[10px] text-gray-400 font-medium">Inversión</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <p class="text-lg font-bold text-gray-800">L {{ number_format($screen->book_value, 0) }}</p>
                    <p class="text-[10px] text-gray-400 font-medium">Valor contable</p>
                </div>
            </div>

            <div class="text-xs text-gray-400">
                <span class="material-symbols-outlined" style="font-size:14px">schedule</span>
                {{ $screen->operating_hours }} · Spot {{ $screen->spot_duration_seconds }}s · Ciclo {{ $screen->cycle_duration_seconds }}s
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 pt-2 border-t border-gray-100">
                <a href="{{ route('screens.show', $screen) }}" class="flex-1 inline-flex items-center justify-center gap-1 bg-brand-50 text-brand-600 px-3 py-2 rounded-xl text-xs font-semibold hover:bg-brand-100 transition">
                    <span class="material-symbols-outlined" style="font-size:16px">visibility</span> Ver Detalle
                </a>
                <a href="{{ route('screens.edit', $screen) }}" class="inline-flex items-center justify-center gap-1 bg-gray-100 text-gray-600 px-3 py-2 rounded-xl text-xs font-semibold hover:bg-gray-200 transition">
                    <span class="material-symbols-outlined" style="font-size:16px">edit</span>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
