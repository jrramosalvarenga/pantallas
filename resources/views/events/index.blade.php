@extends('layouts.app')

@section('title', 'Eventos')
@section('page-title', 'Eventos')
@section('page-subtitle', 'Pantalla móvil y eventos especiales')

@section('content')
{{-- Status cards --}}
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
    @php
        $evColors = [
            'cotizado' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'icon' => 'request_quote'],
            'confirmado' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => 'event_available'],
            'en_curso' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'play_circle'],
            'completado' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => 'check_circle'],
            'cancelado' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'cancel'],
        ];
    @endphp
    @foreach(\App\Models\Event::STATUSES as $key => $label)
        @php $c = $evColors[$key]; @endphp
        <a href="{{ route('events.index', ['status' => $key]) }}"
           class="block {{ $c['bg'] }} border {{ $c['border'] }} rounded-2xl p-4 hover:shadow-md transition {{ request('status') === $key ? 'ring-2 ring-brand-500 shadow-md' : '' }}">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined {{ $c['text'] }}" style="font-size:18px">{{ $c['icon'] }}</span>
                <span class="text-2xl font-bold {{ $c['text'] }}">{{ $statusCounts[$key] ?? 0 }}</span>
            </div>
            <p class="text-xs font-medium text-gray-500">{{ $label }}</p>
        </a>
    @endforeach
</div>

{{-- Filters --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form method="GET" class="flex items-center gap-2 flex-1 max-w-2xl flex-wrap">
        <div class="relative flex-1 min-w-[200px]">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:18px">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar evento, lugar, contacto..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
        </div>
        <select name="type" onchange="this.form.submit()" class="border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">Tipo</option>
            @foreach(\App\Models\Event::TYPES as $k => $v)
                <option value="{{ $k }}" {{ request('type') === $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
        @if(request()->hasAny(['search', 'status', 'type']))
            <a href="{{ route('events.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <span class="material-symbols-outlined" style="font-size:16px">close</span> Limpiar
            </a>
        @endif
    </form>
    <div class="flex items-center gap-2">
        <a href="{{ route('calendar.index') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 transition whitespace-nowrap">
            <span class="material-symbols-outlined" style="font-size:18px">calendar_month</span> Calendario
        </a>
        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm whitespace-nowrap">
            <span class="material-symbols-outlined" style="font-size:18px">celebration</span> Nuevo Evento
        </a>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 text-left">
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Evento</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden md:table-cell">Lugar</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden lg:table-cell">Tipo</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Fecha</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Estado</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden lg:table-cell">Precio</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($events as $event)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-4">
                        <a href="{{ route('events.show', $event) }}" class="font-medium text-gray-800 hover:text-brand-600">{{ $event->name }}</a>
                        @if($event->contact_name)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $event->contact_name }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-600 hidden md:table-cell">{{ $event->venue }}</td>
                    <td class="px-5 py-4 text-gray-600 hidden lg:table-cell">{{ \App\Models\Event::TYPES[$event->type] ?? $event->type }}</td>
                    <td class="px-5 py-4">
                        <span class="text-sm text-gray-700">{{ $event->start_date->format('d/m/Y') }}</span>
                        <p class="text-xs text-gray-400">{{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $stColors = ['cotizado' => 'bg-slate-100 text-slate-700', 'confirmado' => 'bg-blue-100 text-blue-700', 'en_curso' => 'bg-amber-100 text-amber-700', 'completado' => 'bg-emerald-100 text-emerald-700', 'cancelado' => 'bg-red-100 text-red-700'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $stColors[$event->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ \App\Models\Event::STATUSES[$event->status] ?? $event->status }}
                        </span>
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell">
                        @if($event->final_price ?? $event->quoted_price)
                            <span class="text-sm font-medium text-gray-700">L {{ number_format($event->final_price ?? $event->quoted_price, 2) }}</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('events.show', $event) }}" class="p-2 text-gray-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition" title="Ver">
                                <span class="material-symbols-outlined" style="font-size:18px">visibility</span>
                            </a>
                            <a href="{{ route('events.edit', $event) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Editar">
                                <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center">
                        <span class="material-symbols-outlined text-gray-300" style="font-size:48px">celebration</span>
                        <p class="text-sm text-gray-400 mt-2">No hay eventos registrados</p>
                        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium mt-2 hover:text-brand-700">
                            <span class="material-symbols-outlined" style="font-size:16px">add</span> Crear el primero
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($events->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $events->links() }}
    </div>
    @endif
</div>
@endsection
