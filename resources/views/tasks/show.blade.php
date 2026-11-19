@extends('layouts.app')

@section('title', $task->title)
@section('page-title', 'Detalle de Tarea')
@section('page-subtitle', $task->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600">
        <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver a tareas
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        {{-- Task header --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    @php
                        $typeIcons = ['crear_anuncio' => 'movie', 'modificar_anuncio' => 'edit_note', 'renovar_contrato' => 'autorenew', 'soporte_tecnico' => 'build', 'cobranza' => 'payments', 'diseno' => 'palette', 'instalacion' => 'construction', 'otro' => 'task_alt'];
                    @endphp
                    <div class="w-12 h-12 bg-brand-100 text-brand-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size:24px">{{ $typeIcons[$task->type] ?? 'task_alt' }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $task->title }}</h3>
                        <p class="text-sm text-gray-500">{{ \App\Models\ClientTask::TYPES[$task->type] ?? $task->type }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if(in_array($task->status, ['pendiente', 'en_progreso']))
                    <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="{{ $task->status === 'pendiente' ? 'en_progreso' : 'completada' }}">
                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 bg-emerald-600 text-white rounded-xl text-xs font-semibold hover:bg-emerald-700 transition">
                            <span class="material-symbols-outlined" style="font-size:16px">{{ $task->status === 'pendiente' ? 'play_arrow' : 'check' }}</span>
                            {{ $task->status === 'pendiente' ? 'Iniciar' : 'Completar' }}
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center gap-1 px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                        <span class="material-symbols-outlined" style="font-size:16px">edit</span> Editar
                    </a>
                </div>
            </div>

            @php
                $stColors = ['pendiente' => 'bg-amber-100 text-amber-700', 'en_progreso' => 'bg-blue-100 text-blue-700', 'completada' => 'bg-emerald-100 text-emerald-700', 'cancelada' => 'bg-gray-100 text-gray-500'];
                $pColors = ['baja' => 'bg-gray-100 text-gray-600', 'media' => 'bg-blue-100 text-blue-700', 'alta' => 'bg-amber-100 text-amber-700', 'urgente' => 'bg-red-100 text-red-700'];
            @endphp
            <div class="flex items-center gap-2 mb-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $stColors[$task->status] ?? '' }}">
                    {{ \App\Models\ClientTask::STATUSES[$task->status] ?? $task->status }}
                </span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $pColors[$task->priority] ?? '' }}">
                    {{ \App\Models\ClientTask::PRIORITIES[$task->priority] ?? $task->priority }}
                </span>
                @if($task->is_overdue)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                        <span class="material-symbols-outlined" style="font-size:14px">warning</span> Vencida
                    </span>
                @endif
            </div>

            @if($task->description)
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-600 mb-1">Descripción</h4>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $task->description }}</p>
            </div>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-400 text-xs">Fecha límite</span>
                    <p class="font-medium text-gray-700">{{ $task->due_date?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-xs">Creada</span>
                    <p class="font-medium text-gray-700">{{ $task->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($task->completed_at)
                <div>
                    <span class="text-gray-400 text-xs">Completada</span>
                    <p class="font-medium text-emerald-600">{{ $task->completed_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
                <div>
                    <span class="text-gray-400 text-xs">Creada por</span>
                    <p class="font-medium text-gray-700">{{ $task->creator->name ?? '—' }}</p>
                </div>
            </div>

            @if($task->notes)
            <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                <h4 class="text-xs font-semibold text-gray-500 mb-1">Notas internas</h4>
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $task->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px">business</span> Cliente
            </h4>
            @if($task->client)
                <p class="font-medium text-gray-800">{{ $task->client->business_name }}</p>
                @if($task->client->phone)
                    <p class="text-sm text-gray-500 mt-1">{{ $task->client->phone }}</p>
                @endif
                <a href="{{ route('clients.show', $task->client) }}" class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium mt-2 hover:text-brand-700">
                    Ver ficha del cliente <span class="material-symbols-outlined" style="font-size:14px">arrow_forward</span>
                </a>
            @else
                <p class="text-sm text-gray-400">Sin cliente</p>
            @endif
        </div>

        @if($task->assignedUser)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px">person</span> Asignado a
            </h4>
            <p class="font-medium text-gray-800">{{ $task->assignedUser->name }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
