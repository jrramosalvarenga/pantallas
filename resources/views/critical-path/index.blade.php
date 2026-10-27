@extends('layouts.app')

@section('title', 'Ruta Crítica')
@section('page-title', 'Ruta Crítica')
@section('page-subtitle', 'Pasos para echar a andar tu negocio')

@section('content')
{{-- Progreso general --}}
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Progreso General</h3>
            <p class="text-sm text-gray-400">{{ $completedSteps }} de {{ $totalSteps }} pasos completados</p>
        </div>
        <div class="text-3xl font-bold {{ $progressPercent >= 80 ? 'text-emerald-600' : ($progressPercent >= 40 ? 'text-amber-600' : 'text-brand-600') }}">
            {{ $progressPercent }}%
        </div>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-4">
        <div class="h-4 rounded-full transition-all duration-700 bg-gradient-to-r {{ $progressPercent >= 80 ? 'from-emerald-400 to-emerald-600' : ($progressPercent >= 40 ? 'from-amber-400 to-amber-600' : 'from-brand-400 to-brand-600') }}"
             style="width: {{ $progressPercent }}%"></div>
    </div>
</div>

{{-- Fases --}}
@foreach($phases as $phaseName => $phaseSteps)
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 bg-brand-100 text-brand-600 rounded-lg flex items-center justify-center text-sm font-bold">
            {{ $loop->iteration }}
        </div>
        <h3 class="text-base font-semibold text-gray-800">{{ $phaseName }}</h3>
        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
            {{ $phaseSteps->where('status', 'completado')->count() }}/{{ $phaseSteps->count() }}
        </span>
    </div>

    <div class="space-y-3 ml-4 border-l-2 border-gray-200 pl-6">
        @foreach($phaseSteps as $step)
        <div class="relative bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-shadow" x-data="{ editing: false }">
            {{-- Dot on timeline --}}
            <div class="absolute -left-[31px] top-5 w-4 h-4 rounded-full border-2 border-white shadow
                {{ $step->status === 'completado' ? 'bg-emerald-500' : ($step->status === 'en_curso' ? 'bg-amber-500' : ($step->status === 'bloqueado' ? 'bg-red-500' : 'bg-gray-300')) }}">
            </div>

            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="text-sm font-semibold text-gray-700">{{ $step->title }}</h4>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                            {{ $step->status === 'completado' ? 'bg-emerald-50 text-emerald-600' :
                               ($step->status === 'en_curso' ? 'bg-amber-50 text-amber-600' :
                               ($step->status === 'bloqueado' ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-500')) }}">
                            {{ ucfirst(str_replace('_', ' ', $step->status)) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mb-2">{{ $step->description }}</p>
                    <div class="flex items-center gap-4 text-[11px] text-gray-400">
                        @if($step->responsible)
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px">person</span>
                            {{ $step->responsible }}
                        </span>
                        @endif
                        @if($step->target_date)
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px">event</span>
                            Meta: {{ $step->target_date->format('d/m/Y') }}
                        </span>
                        @endif
                        @if($step->completed_date)
                        <span class="flex items-center gap-1 text-emerald-500">
                            <span class="material-symbols-outlined" style="font-size:14px">check_circle</span>
                            {{ $step->completed_date->format('d/m/Y') }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Status change --}}
                <div class="flex-shrink-0" x-show="!editing">
                    <button @click="editing = true" class="text-gray-400 hover:text-gray-600">
                        <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                    </button>
                </div>
                <div x-show="editing" x-cloak class="flex-shrink-0">
                    <form method="POST" action="{{ route('critical-path.update-status', $step) }}" class="flex items-center gap-2">
                        @csrf
                        <select name="status" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-brand-500 outline-none">
                            <option value="pendiente" {{ $step->status === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_curso" {{ $step->status === 'en_curso' ? 'selected' : '' }}>En curso</option>
                            <option value="completado" {{ $step->status === 'completado' ? 'selected' : '' }}>Completado</option>
                            <option value="bloqueado" {{ $step->status === 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                        </select>
                        <button type="submit" class="bg-brand-600 text-white px-2 py-1.5 rounded-lg text-xs hover:bg-brand-700">
                            <span class="material-symbols-outlined" style="font-size:14px">check</span>
                        </button>
                        <button type="button" @click="editing = false" class="text-gray-400 hover:text-gray-600">
                            <span class="material-symbols-outlined" style="font-size:14px">close</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
@endsection
