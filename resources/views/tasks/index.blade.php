@extends('layouts.app')

@section('title', 'Tareas')
@section('page-title', 'Tareas de Clientes')
@section('page-subtitle', 'Solicitudes y trabajos pendientes')

@section('content')
{{-- Status cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    @php
        $taskColors = [
            'pendiente' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'pending'],
            'en_progreso' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => 'autorenew'],
            'completada' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => 'check_circle'],
            'cancelada' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-500', 'border' => 'border-gray-200', 'icon' => 'cancel'],
        ];
    @endphp
    @foreach(\App\Models\ClientTask::STATUSES as $key => $label)
        @php $c = $taskColors[$key]; @endphp
        <a href="{{ route('tasks.index', ['status' => $key]) }}"
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
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar tarea o cliente..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>
        <select name="type" onchange="this.form.submit()" class="border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">Tipo</option>
            @foreach(\App\Models\ClientTask::TYPES as $k => $v)
                <option value="{{ $k }}" {{ request('type') === $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
        <select name="priority" onchange="this.form.submit()" class="border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">Prioridad</option>
            @foreach(\App\Models\ClientTask::PRIORITIES as $k => $v)
                <option value="{{ $k }}" {{ request('priority') === $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
        @if(request()->hasAny(['search', 'status', 'type', 'priority']))
            <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <span class="material-symbols-outlined" style="font-size:16px">close</span> Limpiar
            </a>
        @endif
    </form>
    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm whitespace-nowrap">
        <span class="material-symbols-outlined" style="font-size:18px">add_task</span>
        Nueva Tarea
    </a>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 text-left">
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Tarea</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden md:table-cell">Cliente</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden lg:table-cell">Tipo</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Estado</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden md:table-cell">Prioridad</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden lg:table-cell">Fecha límite</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($tasks as $task)
                <tr class="hover:bg-gray-50/50 transition {{ $task->is_overdue ? 'bg-red-50/30' : '' }}">
                    <td class="px-5 py-4">
                        <a href="{{ route('tasks.show', $task) }}" class="font-medium text-gray-800 hover:text-brand-600">{{ $task->title }}</a>
                        @if($task->assignedUser)
                            <p class="text-xs text-gray-400 mt-0.5">Asignado: {{ $task->assignedUser->name }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-600 hidden md:table-cell">{{ $task->client->business_name ?? '—' }}</td>
                    <td class="px-5 py-4 text-gray-600 hidden lg:table-cell">{{ \App\Models\ClientTask::TYPES[$task->type] ?? $task->type }}</td>
                    <td class="px-5 py-4">
                        @php
                            $stColors = ['pendiente' => 'bg-amber-100 text-amber-700', 'en_progreso' => 'bg-blue-100 text-blue-700', 'completada' => 'bg-emerald-100 text-emerald-700', 'cancelada' => 'bg-gray-100 text-gray-500'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $stColors[$task->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ \App\Models\ClientTask::STATUSES[$task->status] ?? $task->status }}
                        </span>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        @php
                            $pColors = ['baja' => 'bg-gray-100 text-gray-600', 'media' => 'bg-blue-100 text-blue-700', 'alta' => 'bg-amber-100 text-amber-700', 'urgente' => 'bg-red-100 text-red-700'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $pColors[$task->priority] ?? '' }}">
                            {{ \App\Models\ClientTask::PRIORITIES[$task->priority] ?? $task->priority }}
                        </span>
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell">
                        @if($task->due_date)
                            <span class="text-xs {{ $task->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                {{ $task->due_date->format('d/m/Y') }}
                                @if($task->is_overdue)
                                    <span class="material-symbols-outlined align-middle" style="font-size:14px">warning</span>
                                @endif
                            </span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            @if(in_array($task->status, ['pendiente', 'en_progreso']))
                            <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="{{ $task->status === 'pendiente' ? 'en_progreso' : 'completada' }}">
                                <button type="submit" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                                        title="{{ $task->status === 'pendiente' ? 'Iniciar' : 'Completar' }}">
                                    <span class="material-symbols-outlined" style="font-size:18px">{{ $task->status === 'pendiente' ? 'play_arrow' : 'check' }}</span>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('tasks.show', $task) }}" class="p-2 text-gray-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition" title="Ver">
                                <span class="material-symbols-outlined" style="font-size:18px">visibility</span>
                            </a>
                            <a href="{{ route('tasks.edit', $task) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Editar">
                                <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center">
                        <span class="material-symbols-outlined text-gray-300" style="font-size:48px">task_alt</span>
                        <p class="text-sm text-gray-400 mt-2">No hay tareas</p>
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium mt-2 hover:text-brand-700">
                            <span class="material-symbols-outlined" style="font-size:16px">add</span> Crear la primera
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tasks->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection
