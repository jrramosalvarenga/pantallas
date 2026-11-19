@extends('layouts.app')

@section('title', 'Nueva Tarea')
@section('page-title', 'Nueva Tarea')
@section('page-subtitle', 'Crear solicitud de cliente')

@section('content')
<div class="max-w-3xl">
    <div class="mb-4">
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600">
            <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver a tareas
        </a>
    </div>

    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-600" style="font-size:20px">add_task</span>
                Detalles de la Tarea
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none"
                           placeholder="Ej: Crear video promocional para campaña navideña">
                    @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente *</label>
                    <select name="client_id" required class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="">— Seleccionar —</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $selectedClient) == $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                    <select name="type" required class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                        @foreach(\App\Models\ClientTask::TYPES as $k => $v)
                            <option value="{{ $k }}" {{ old('type') === $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad *</label>
                    <select name="priority" required class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                        @foreach(\App\Models\ClientTask::PRIORITIES as $k => $v)
                            <option value="{{ $k }}" {{ old('priority', 'media') === $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha límite</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}"
                           class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asignar a</label>
                    <select name="assigned_to" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="">— Sin asignar —</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="description" rows="3" placeholder="Detalles de lo que solicita el cliente..."
                              class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('description') }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas internas</label>
                    <textarea name="notes" rows="2" placeholder="Notas internas del equipo..."
                              class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 bg-brand-600 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
                <span class="material-symbols-outlined" style="font-size:18px">add_task</span> Crear Tarea
            </button>
            <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
        </div>
    </form>
</div>
@endsection
