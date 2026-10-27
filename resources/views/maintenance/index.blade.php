@extends('layouts.app')

@section('title', 'Bitácora de Mantenimiento')
@section('page-title', 'Bitácora de Mantenimiento')
@section('page-subtitle', 'Historial de mantenimientos de pantallas')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <select name="screen_id" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-brand-500 outline-none" onchange="this.form.submit()">
            <option value="">Todas las pantallas</option>
            @foreach($screens as $screen)
            <option value="{{ $screen->id }}" {{ request('screen_id') == $screen->id ? 'selected' : '' }}>{{ $screen->name }}</option>
            @endforeach
        </select>
        <select name="type" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-brand-500 outline-none" onchange="this.form.submit()">
            <option value="">Todos los tipos</option>
            <option value="preventivo" {{ request('type') === 'preventivo' ? 'selected' : '' }}>Preventivo</option>
            <option value="correctivo" {{ request('type') === 'correctivo' ? 'selected' : '' }}>Correctivo</option>
            <option value="emergencia" {{ request('type') === 'emergencia' ? 'selected' : '' }}>Emergencia</option>
        </select>
    </form>
    <a href="{{ route('maintenance.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
        <span class="material-symbols-outlined" style="font-size:18px">add</span>
        Nuevo registro
    </a>
</div>

{{-- Timeline --}}
<div class="space-y-0">
    @forelse($logs as $log)
    <div class="flex gap-4">
        {{-- Timeline line & dot --}}
        <div class="flex flex-col items-center">
            <div class="w-4 h-4 rounded-full border-2 flex-shrink-0 mt-1
                {{ $log->type === 'preventivo' ? 'bg-blue-500 border-blue-500' : ($log->type === 'correctivo' ? 'bg-amber-500 border-amber-500' : 'bg-red-500 border-red-500') }}"></div>
            @if(!$loop->last)
            <div class="w-0.5 flex-1 bg-gray-200 min-h-[40px]"></div>
            @endif
        </div>

        {{-- Content card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4 flex-1 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full uppercase
                            {{ $log->type === 'preventivo' ? 'bg-blue-50 text-blue-600' : ($log->type === 'correctivo' ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">
                            {{ $log->type }}
                        </span>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                            {{ $log->status === 'completado' ? 'bg-emerald-50 text-emerald-600' : ($log->status === 'en_curso' ? 'bg-blue-50 text-blue-600' : ($log->status === 'programado' ? 'bg-gray-100 text-gray-600' : 'bg-red-50 text-red-600')) }}">
                            {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                        </span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800">{{ $log->title }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($log->description, 120) }}</p>
                </div>
                <div class="text-right flex-shrink-0 ml-4">
                    <p class="text-xs font-semibold text-gray-700">{{ $log->date->format('d/m/Y') }}</p>
                    <p class="text-[10px] text-gray-400">{{ $log->screen->name ?? '' }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    @if($log->technician)
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size:14px">person</span>
                        {{ $log->technician }}
                    </span>
                    @endif
                    @if($log->cost > 0)
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size:14px">payments</span>
                        L {{ number_format($log->cost, 2) }}
                    </span>
                    @endif
                    @if($log->next_maintenance_date)
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size:14px">event</span>
                        Próximo: {{ $log->next_maintenance_date->format('d/m/Y') }}
                    </span>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('maintenance.edit', $log) }}" class="text-gray-400 hover:text-brand-600">
                        <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                    </a>
                    <form method="POST" action="{{ route('maintenance.destroy', $log) }}" onsubmit="return confirm('¿Eliminar este registro?')" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-gray-400 hover:text-red-600"><span class="material-symbols-outlined" style="font-size:18px">delete</span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
        <span class="material-symbols-outlined text-gray-300" style="font-size:64px">build</span>
        <p class="text-gray-400 mt-3">No hay registros de mantenimiento</p>
        <a href="{{ route('maintenance.create') }}" class="inline-flex items-center gap-2 mt-4 text-sm text-brand-600 font-medium hover:text-brand-700">
            <span class="material-symbols-outlined" style="font-size:16px">add</span> Crear primer registro
        </a>
    </div>
    @endforelse
</div>

<div class="mt-6">{{ $logs->withQueryString()->links() }}</div>
@endsection
